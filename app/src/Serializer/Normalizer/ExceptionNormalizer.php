<?php

declare(strict_types=1);

namespace App\Serializer\Normalizer;

use FOS\RestBundle\Util\ExceptionValueMap;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use JSend\JSendResponse;

class ExceptionNormalizer implements NormalizerInterface
{
    /**
     * @var ExceptionValueMap
     */
    private $messagesMap;

    /**
     * @var bool
     */
    private $debug;

    /**
     * ExceptionNormalizer constructor.
     * @param ExceptionValueMap $messagesMap
     * @param $debug
     */
    public function __construct(ExceptionValueMap $messagesMap, $debug)
    {
        $this->messagesMap = $messagesMap;
        $this->debug = $debug;
    }

    /**
     * @inheritDoc
     * @return array
     * @throws \JSend\InvalidJSendException
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        $statusCode = $context['template_data']['status_code'] ?? null;
        $code = $statusCode;
        $message = $this->getExceptionMessage($object, $statusCode ?? null);

        $trace = null;
        if (isset($context['template_data']['exception'])) {
            $trace = $context['template_data']['exception']->getTrace();
        }

        if ($_ENV['APP_ENV'] == 'dev' && $trace !== null) {
            $data['exceptions'] = $trace;
        }

        $data = new JSendResponse(JSendResponse::ERROR, $data ?? null, $message, $code);

        return $data->jsonSerialize();
    }

    /**
     * @inheritDoc
     * @return bool
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof \Exception;
    }

    /**
     * @param \Exception $exception
     * @param null $statusCode
     * @return string
     */
    private function getExceptionMessage(\Exception $exception, $statusCode = null)
    {
        $showMessage = $this->messagesMap->resolveException($exception);

        if ($showMessage || $this->debug) {
            return $exception->getMessage();
        }

        return array_key_exists($statusCode, Response::$statusTexts) ? Response::$statusTexts[$statusCode] : 'error';
    }
}
