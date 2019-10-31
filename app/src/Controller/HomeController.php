<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Sport API",
 *     description="HTTP JSON API",
 * )
 */
class HomeController extends AbstractController
{
    /**
     * @OA\Get(
     *     path="/",
     *     tags={"API"},
     *     description="API Home",
     *     @OA\Response(
     *         response="200",
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string")
     *         )
     *     )
     * )
     *
     * @Route("", name="home", methods={"GET"})
     * @return Response
     */
    public function home(): Response
    {
        return $this->json([
            'name' => 'JSON API',
        ]);
    }
}
