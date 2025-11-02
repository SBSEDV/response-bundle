<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ApiResponseFactory
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly NormalizerInterface $normalizer,
        private readonly RequestStack $requestStack,
    ) {
    }

    /**
     * Create an API response.
     *
     * @param ApiResponseDto                 $apiResponseDto The ApiResponse DTO object.
     * @param array<string, string|string[]> $headers        [optional] An array of headers that will be added to the response.
     * @param array<array-key, mixed>        $context        [optional] The serialization context.
     */
    public function createApiResponse(ApiResponseDto $apiResponseDto, array $headers = [], array $context = []): Response
    {
        $request = $this->requestStack->getCurrentRequest();

        $response = new Response('', $apiResponseDto->status, $headers);

        $format = match ($request?->getPreferredFormat('json')) {
            'xml' => 'xml',
            default => 'json',
        };

        $normalized = $this->normalizer->normalize($apiResponseDto, $format, $context);

        switch ($format) {
            case 'xml':
                $xml = $this->serializer->serialize($normalized, 'xml', $context);

                $response->setContent($xml);
                $response->headers->set('Content-Type', 'application/xml');

                break;
            default:
                $json = $this->serializer->serialize($normalized, 'json', \array_merge([
                    JsonEncode::OPTIONS => JsonResponse::DEFAULT_ENCODING_OPTIONS,
                ], $context));

                $response->setContent($json);
                $response->headers->set('Content-Type', 'application/json');
        }

        return $response;
    }
}
