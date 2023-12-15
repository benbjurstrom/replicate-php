<?php

namespace BenBjurstrom\Replicate\Requests;

use BenBjurstrom\Replicate\Data\PredictionData;
use Saloon\Http\Response;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetPrediction extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $id,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return sprintf('/predictions/%s', $this->id);
    }

    public function createDtoFromResponse(Response $response): PredictionData
    {
        return PredictionData::fromResponse($response);
    }
}
