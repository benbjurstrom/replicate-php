<?php

namespace BenBjurstrom\Replicate;

use BenBjurstrom\Replicate\Data\PredictionData;
use BenBjurstrom\Replicate\Data\PredictionsData;
use BenBjurstrom\Replicate\Requests\GetPrediction;
use BenBjurstrom\Replicate\Requests\GetPredictions;
use BenBjurstrom\Replicate\Requests\PostPrediction;
use BenBjurstrom\Replicate\Requests\PostDeploymentPrediction;
use Exception;

class PredictionsResource extends Resource
{
    protected ?string $webhookUrl = null;

    /**
     * @var array<string>
     */
    protected ?array $webhookEvents;

    public function list(?string $cursor = null): PredictionsData
    {
        $request = new GetPredictions();

        if ($cursor) {
            $request->query()->add('cursor', $cursor);
        }

        $response = $this->connector->send($request);
        $data = $response->dtoOrFail();
        if (! $data instanceof PredictionsData) {
            throw new Exception('Unexpected data type');
        }

        return $data;
    }

    public function get(string $id): PredictionData
    {
        $request = new GetPrediction($id);
        $response = $this->connector->send($request);

        $data = $response->dtoOrFail();
        if (! $data instanceof PredictionData) {
            throw new Exception('Unexpected data type');
        }

        return $data;
    }

    /**
     * @param  array<string, float|int|string|null>  $input
     *
     * @throws Exception
     */
    public function create(string $version, array $input): PredictionData
    {
        $request = new PostPrediction($version, $input);
        if ($this->webhookUrl) {
            // https://replicate.com/changelog/2023-02-10-improved-webhook-events-and-event-filtering
            $request->body()->merge([
                'webhook' => $this->webhookUrl,
                'webhook_events_filter' => $this->webhookEvents,
            ]);
        }

        $response = $this->connector->send($request);

        $data = $response->dtoOrFail();
        if (! $data instanceof PredictionData) {
            throw new Exception('Unexpected data type');
        }

        return $data;
    }

    /**
     * @param  array<string>  $events
     */
    public function withWebhook(string $url, ?array $events = ['completed']): self
    {
        $this->webhookUrl = $url;
        $this->webhookEvents = $events;

        return $this;
    }
    /**
     * Creates a prediction for a specific deployment.
     *
     * @param  string $fullDeploymentName Full name of the deployment.
     * @param  array<string, mixed>  $input The input data for the prediction.
     * @return PredictionData The data of the created prediction.
     * @throws Exception If the response does not contain the expected data.
     */
    public function createForDeployment(string $fullDeploymentName, array $input): PredictionData
    {
        $request = new PostDeploymentPrediction($input, $fullDeploymentName);

        if ($this->webhookUrl) {
            $request->body()->merge([
                'webhook' => $this->webhookUrl,
                'webhook_events_filter' => $this->webhookEvents,
            ]);
        }

        $response = $this->connector->send($request);

        $data = $response->dtoOrFail();
        if (! $data instanceof PredictionData) {
            throw new Exception('Unexpected data type');
        }

        return $data;
    }



}
