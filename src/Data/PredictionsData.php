<?php

namespace BenBjurstrom\Replicate\Data;

use Exception;
use Saloon\Http\Response;

final class PredictionsData
{
    /**
     * @param  array<int, PredictionData>  $results
     */
    public function __construct(
        public string|null $previous,
        public string|null $next,
        public array $results
    ) {
    }

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();
        if (! is_array($data)) {
            throw new Exception('Invalid response');
        }

        $results = [];
        foreach ($data['results'] as $result) {
            $results[] = new PredictionData(
                id: $result['id'],
                version: $result['version'],
                createdAt: $result['created_at'],
                completedAt: $result['completed_at'],
                startedAt: $result['started_at'],
                status: $result['status'],
                webhookCompleted: $result['webhook_completed'],
                input: $result['input'],
                metrics: $result['metrics'],
                urls: $result['urls'],
                error: $result['error'],
                output: $result['output'],
            );
        }

        return new static(
            previous: $data['previous'],
            next: $data['next'],
            results: $results,
        );
    }
}
