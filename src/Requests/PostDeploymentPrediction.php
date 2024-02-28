<?php

namespace BenBjurstrom\Replicate\Requests;

use BenBjurstrom\Replicate\Data\PredictionData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class PostDeploymentPrediction extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;
    protected string $fullDeploymentName;


    /**
     * @param  array<string, mixed>  $input
     * @param  string $fullDeploymentName
     */
    public function __construct(
        protected array $input,
        string $fullDeploymentName,

    ) {
        $this->fullDeploymentName = $fullDeploymentName;

    }

    public function resolveEndpoint(): string
    {
        return sprintf('/deployments/%s/predictions', $this->fullDeploymentName);
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'input' => $this->input,
        ];
    }

    public function createDtoFromResponse(Response $response): PredictionData
    {
        return PredictionData::fromResponse($response);
    }
}