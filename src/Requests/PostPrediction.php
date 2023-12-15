<?php

namespace BenBjurstrom\Replicate\Requests;

use BenBjurstrom\Replicate\Data\PredictionData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Http\Response;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class PostPrediction extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, float|int|string|null>  $input
     */
    public function __construct(
        protected string $version,
        protected array $input,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/predictions';
    }

    /**
     * @return array<string, array<string, float|int|string|null>|string>
     */
    protected function defaultBody(): array
    {
        return [
            'version' => $this->version,
            'input' => $this->input,
        ];
    }

    public function createDtoFromResponse(Response $response): PredictionData
    {
        return PredictionData::fromResponse($response);
    }
}
