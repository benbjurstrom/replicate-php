<?php

use BenBjurstrom\Replicate\Data\PredictionsData;
use BenBjurstrom\Replicate\Requests\GetPredictions;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

test('get predictions endpoint', function () {
    $mockClient = new MockClient([
        GetPredictions::class => MockResponse::fixture('getPredictions'),
    ]);

    $connector = getConnector();
    $connector->withMockClient($mockClient);

    $request = new GetPredictions();
    $response = $connector->send($request);

    /* @var PredictionsData $data */
    $data = $response->dtoOrFail();

    expect($response->ok())
        ->toBeTrue()
        ->and($data->results[0]->id)
        ->toBe('yfv4cakjzvh2lexxv7o5qzymqy');
});
