<?php

namespace BenBjurstrom\Replicate\Data;

use Exception;
use Saloon\Http\Response;

final class PredictionData
{
    /**
     * @param  array<string, string|int|float>  $input
     * @param  array<string, string|int|float>  $metrics
     * @param  array<string, string>  $urls
     * @param  string|array<int, string>  $output
     * @param  null|array<string, string>  $error
     */
    public function __construct(
        public string $id,
        public string $version,
        public string $createdAt,
        public string|null $completedAt,
        public string|null $startedAt,
        public string $status,
        public bool|null $webhookCompleted,
        public array $input,
        public array|null $metrics,
        public array $urls,
        public array|string|null $error,
        public string|array|null $output,
    ) {
    }

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();
        if (! is_array($data)) {
            throw new Exception('Invalid response');
        }

        return new static(
            id: $data['id'],
            version: $data['version'],
            createdAt: $data['created_at'],
            completedAt: $data['completed_at'] ?? null,
            startedAt: $data['started_at'] ?? null,
            status: $data['status'],
            webhookCompleted: $data['webhook_completed'] ?? null,
            input: $data['input'],
            metrics: $data['metrics'] ?? null,
            urls: $data['urls'],
            error: $data['error'] ?? null,
            output: $data['output'] ?? null,
        );
    }
}
