<?php

declare(strict_types=1);

namespace API\Service;

use Symfony\Component\HttpFoundation\Request;

final class FormatRequestService extends BaseFormat implements BaseFormatInterface
{
    public function setFormatFromRequest(Request $request, string $default = 'json'): void
    {
        $this->setFormat($default);
        if (($format = $request->getContentType()) && (isset($this->formats[$format]))) {
            $this->setFormat($format);
        }
    }

    public function request(Request $request): array
    {
        $data = $this->serializer->decode($request->getContent(), $this->getFormat());

        return (is_array($data)) ? $data : [];
    }
}
