<?php

declare(strict_types=1);

namespace API\Service;

use Prooph\ServiceBus\Exception\CommandDispatchException;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

final class FormatResponseService extends BaseFormat implements BaseFormatInterface
{
    public function setFormatFromRequest(Request $request, string $default = 'json'): void
    {
        $this->setFormat($default);
        $acceptHeaders = AcceptHeader::fromString(
            $request->headers->get('Accept')
        )->all();
        foreach ($acceptHeaders as $acceptHeader) {
            $minetype = $acceptHeader->getValue();
            $format = $request->getFormat($minetype);
            if (isset($this->formats[$format])) {
                $this->setFormat($format);
                break;
            }
        }
    }

    public function setFormatToResponse(Response $response): Response
    {
        $format = $this->getFormat();
        if (isset($this->formats[$format])) {
            $response->headers->set('Content-Type', $this->formats[$format]);
        }

        return $response;
    }

    public function responseException(\Throwable $exception): Response
    {
        $data = [
            'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => $exception->getMessage(),
        ];

        if ($exception instanceof \Exception) {
            $data['code'] = Response::HTTP_INTERNAL_SERVER_ERROR;
        }
        if ($exception instanceof \InvalidArgumentException) {
            $data['code'] = Response::HTTP_BAD_REQUEST;
        }
        if ($exception instanceof CommandDispatchException) {
            $e = $exception->getPrevious();

            return $this->responseException($e);
        }
        if ($exception instanceof HttpExceptionInterface) {
            $data['code'] = $exception->getStatusCode();
        }
        $response = $this->response($data, $data['code']);

        return $response;
    }

    public function response(?array $response, $code = Response::HTTP_OK): Response
    {
        $data = '';
        if ($response) {
            $data = $this->serializer->encode($response, $this->getFormat(), []);
        }

        $response = new Response(
            $data,
            $code
        );

        return $this->setFormatToResponse($response);
    }
}
