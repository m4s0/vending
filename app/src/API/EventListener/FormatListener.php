<?php

namespace API\EventListener;

use API\Service\FormatRequestService;
use API\Service\FormatResponseService;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class FormatListener
{
    /**
     * @var FormatRequestService
     */
    private $formatRequestService;

    /**
     * @var FormatResponseService
     */
    private $formatResponseService;

    public function __construct(
        FormatRequestService $formatRequestService,
        FormatResponseService $formatResponseService
    ) {
        $this->formatRequestService = $formatRequestService;
        $this->formatResponseService = $formatResponseService;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        $this->formatRequestService->setFormatFromRequest($request);
        $this->formatResponseService->setFormatFromRequest($request);
    }
}
