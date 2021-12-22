<?php

namespace API\EventListener;

use API\Service\FormatResponseService;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    /**
     * @var FormatResponseService
     */
    private $formatResponseService;

    public function __construct(
        FormatResponseService $formatResponseService
    ) {
        $this->formatResponseService = $formatResponseService;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $response = $this->formatResponseService->responseException($exception);

        $event->setResponse($response);
    }
}
