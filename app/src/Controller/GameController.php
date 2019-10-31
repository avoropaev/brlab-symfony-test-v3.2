<?php

declare(strict_types=1);

namespace App\Controller;

use App\ReadModel\Game\Filter;
use App\ReadModel\Game\GameFetcher;
use JSend\JSendResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Model\UseCase\GameSource\Create;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Annotations as OA;

class GameController extends AbstractController
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var ValidationTransformer
     */
    private $validationTransformer;

    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * @var DenormalizerInterface
     */
    private $denormalizer;

    /**
     * GameController constructor.
     * @param ValidatorInterface $validator
     * @param ValidationTransformer $validationTransformer
     * @param NormalizerInterface $normalizer
     * @param DenormalizerInterface $denormalizer
     */
    public function __construct(
        ValidatorInterface $validator,
        ValidationTransformer $validationTransformer,
        NormalizerInterface $normalizer,
        DenormalizerInterface $denormalizer
    ) {
        $this->validator = $validator;
        $this->validationTransformer = $validationTransformer;
        $this->normalizer = $normalizer;
        $this->denormalizer = $denormalizer;
    }

    /**
     * @OA\Post(
     *     path="/games",
     *     tags={"Games"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="games", type="array", @OA\Items(
     *                 @OA\Property(property="language", type="string"),
     *                 @OA\Property(property="sport", type="string"),
     *                 @OA\Property(property="league", type="string"),
     *                 @OA\Property(property="team_one", type="string"),
     *                 @OA\Property(property="team_two", type="string"),
     *                 @OA\Property(property="source", type="string"),
     *                 @OA\Property(property="start_date", type="string")
     *             ))
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success response",
     *     )
     * )
     *
     * @Route("/games", name="games.create", methods={"POST"})
     * @param Request $request
     * @param Create\Handler $handler
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \JSend\InvalidJSendException
     * @throws \Jawira\CaseConverter\CaseConverterException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function create(Request $request, Create\Handler $handler): Response
    {
        $body = $request->request->all();

        $violations = $this->validator->validate($body, new Assert\Collection([
            'games' => [
                new Assert\NotNull(),
                new Assert\Type('array'),
                new Assert\NotBlank(),
                new Assert\All([
                    new Assert\NotNull(),
                    new Assert\Type('array'),
                    new Assert\NotBlank(),
                    new Assert\Collection([
                        'language' => [
                            new Assert\NotNull(),
                            new Assert\NotBlank(),
                            new Assert\Type('string'),
                        ],
                        'sport' => [
                            new Assert\NotNull(),
                            new Assert\NotBlank(),
                            new Assert\Type('string'),
                        ],
                        'league' => [
                            new Assert\NotNull(),
                            new Assert\NotBlank(),
                            new Assert\Type('string'),
                        ],
                        'team_one' => [
                            new Assert\NotNull(),
                            new Assert\NotBlank(),
                            new Assert\Type('string'),
                        ],
                        'team_two' => [
                            new Assert\NotNull(),
                            new Assert\NotBlank(),
                            new Assert\Type('string'),
                        ],
                        'start_date' => [
                            new Assert\NotNull(),
                            new Assert\NotBlank(),
                            new Assert\Type('string'),
                        ],
                        'source' => [
                            new Assert\NotNull(),
                            new Assert\NotBlank(),
                            new Assert\Type('string'),
                        ]
                    ])
                ])
            ]
        ]));

        if ($violations->count() > 0) {
            $errors = $this->validationTransformer->transform($violations);
            $response = JSendResponse::fail($errors);

            return new JsonResponse($response, Response::HTTP_BAD_REQUEST);
        }

        /** @var Create\Command $command */
        $command = $this->denormalizer->denormalize($body, Create\Command::class);

        $handler->handle($command);

        return new Response(null, Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/games/random",
     *     tags={"Games"},
     *     @OA\Parameter(
     *         name="filter[source]",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         style="form"
     *     ),
     *     @OA\Parameter(
     *         name="filter[start_date_start]",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         style="form"
     *     ),
     *     @OA\Parameter(
     *         name="filter[start_date_end]",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         style="form"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(property="league", type="string"),
     *                 @OA\Property(property="team_one", type="string"),
     *                 @OA\Property(property="team_two", type="string"),
     *                 @OA\Property(property="buffer_count", type="integer"),
     *                 @OA\Property(property="start_date", type="string")
     *             )
     *         )
     *     )
     * )
     *
     * @Route("/games/random", name="games.get.random", methods={"GET"})
     * @param Request $request
     * @param GameFetcher $fetcher
     * @return Response
     * @throws \JSend\InvalidJSendException
     * @throws \Jawira\CaseConverter\CaseConverterException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function random(Request $request, GameFetcher $fetcher): Response
    {
        /** @var Filter $filter */
        $filter = $this->denormalizer->denormalize($request->query->get('filter', []), Filter::class);

        $violations = $this->validator->validate($filter);
        if ($violations->count() > 0) {
            $errors = $this->validationTransformer->transform($violations);
            $response = JSendResponse::fail($errors);

            return new JsonResponse($response, Response::HTTP_BAD_REQUEST);
        }

        $gameView = $fetcher->findOneRandom($filter);

        if ($gameView === null) {
            throw new NotFoundHttpException('Game not found.');
        }

        $data = $this->normalizer->normalize($gameView);
        $data = JSendResponse::success($data);

        return new JsonResponse($data);
    }
}
