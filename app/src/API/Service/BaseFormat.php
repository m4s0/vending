<?php

declare(strict_types=1);

namespace API\Service;

use Symfony\Component\Serializer\Serializer;

class BaseFormat
{
    protected $serializer;
    protected $format;

    protected $formats = [
        'json' => 'application/json',
        'xml' => 'application/xml',
    ];

    public function __construct(
        Serializer $serializer
    ) {
        $this->format = 'json';
        $this->serializer = $serializer;
    }

    public function setFormat(string $format = 'json'): void
    {
        if (!isset($this->formats[$format])) {
            throw new \InvalidArgumentException('Format not valid');
        }
        $this->format = $format;
    }

    public function getFormat(): string
    {
        return $this->format;
    }
}
