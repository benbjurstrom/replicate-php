<?php

namespace BenBjurstrom\Replicate\Requests;

use BenBjurstrom\Replicate\Data\PredictionsData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetPredictions extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/predictions';
    }

    public function createDtoFromResponse(Response $response): PredictionsData
    {
        return PredictionsData::fromResponse($response);
    }
}
