<?php

use BenBjurstrom\Replicate\Requests\GetPrediction;
use BenBjurstrom\Replicate\Requests\GetPredictions;
use BenBjurstrom\Replicate\Requests\PostPrediction;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

test('predictions list', function () {
    $mockClient = new MockClient([
        GetPredictions::class => MockResponse::fixture('getPredictions'),
    ]);

    $connector = getConnector();
    $connector->withMockClient($mockClient);

    $cursor = '123';
    $data = $connector->predictions()->list($cursor);

    expect($data->results[0]->id)
        ->toBe('yfv4cakjzvh2lexxv7o5qzymqy');
});

test('predictions get', function () {
    $mockClient = new MockClient([
        GetPrediction::class => MockResponse::fixture('getPrediction'),
    ]);

    $connector = getConnector();
    $connector->withMockClient($mockClient);

    $data = $connector->predictions()->get('123');

    expect($data->id)
        ->toBe('123');
});

test('predictions create', function () {
    $mockClient = new MockClient([
        PostPrediction::class => MockResponse::fixture('postPredictionAlice'),
    ]);

    $connector = getConnector();
    $connector->withMockClient($mockClient);

    $data = $connector->predictions()
        ->withWebhook('https://example.com/webhook')
        ->create('123', [
            'text' => 'Alice',
        ]);

    expect($data->id)
        ->toBe('123');
});
