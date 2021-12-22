<?php

declare(strict_types=1);

namespace API\Service;

use Symfony\Component\HttpFoundation\Request;

interface BaseFormatInterface
{
    public function setFormat(string $format = 'json'): void;

    public function getFormat(): string;

    public function setFormatFromRequest(Request $request, string $default = 'json'): void;
}
