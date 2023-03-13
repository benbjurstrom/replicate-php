<?php

use BenBjurstrom\Replicate\Data\PredictionData;
use BenBjurstrom\Replicate\Requests\GetPrediction;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    echo 'beforeEach';
});

test('get prediction endpoint', function () {
    $mockClient = new MockClient([
        GetPrediction::class => MockResponse::fixture('getPrediction'),
    ]);

    $connector = getConnector();
    $connector->withMockClient($mockClient);

    $request = new GetPrediction('123');
    $response = $connector->send($request);

    /* @var PredictionData $data */
    $data = $response->dtoOrFail();

    expect($response->ok())
        ->toBeTrue()
        ->and($data->id)
        ->toBe('123');
});

test('get prediction endpoint failed', function () {
    $mockClient = new MockClient([
        GetPrediction::class => MockResponse::fixture('getPredictionFailed'),
    ]);

    $connector = getConnector();
    $connector->withMockClient($mockClient);

    $id = 'c6fng3kkerguvg3aobpv2lquaa';
    $request = new GetPrediction($id);
    $response = $connector->send($request);

    /* @var PredictionData $data */
    $data = $response->dtoOrFail();

    expect($response->ok())
        ->toBeTrue()
        ->and($data->id)
        ->toBe('c6fng3kkerguvg3aobpv2lquaa');
});

test('get prediction endpoint multiple outputs', function () {
    $mockClient = new MockClient([
        GetPrediction::class => MockResponse::fixture('getPredictionMultiple'),
    ]);

    $connector = getConnector();
    $connector->withMockClient($mockClient);

    $id = '3uff6ygnljbr7htjs2lkefcx3a';
    $request = new GetPrediction($id);
    $response = $connector->send($request);

    /* @var PredictionData $data */
    $data = $response->dtoOrFail();

    expect($response->ok())
        ->toBeTrue()
        ->and($data->id)
        ->toBe('3uff6ygnljbr7htjs2lkefcx3a');
});

test('get prediction astronaut', function () {
    $mockClient = new MockClient([
        GetPrediction::class => MockResponse::fixture('getPredictionAstronaut'),
    ]);

    $connector = getConnector();
    $connector->withMockClient($mockClient);

    $id = 'la5xlbbrfzg57ip5jlx6obmm5y';
    $request = new GetPrediction($id);
    $response = $connector->send($request);

    /* @var PredictionData $data */
    $data = $response->dtoOrFail();

    expect($response->ok())
        ->toBeTrue()
        ->and($data->id)
        ->toBe('la5xlbbrfzg57ip5jlx6obmm5y');
});
