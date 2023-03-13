<?php

use BenBjurstrom\Replicate\Requests\PostPredictionCancel;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

test('post predictions cancel endpoint', function () {
    $mockClient = new MockClient([
        PostPredictionCancel::class => MockResponse::fixture('postPredictionCancel'),
    ]);

    $connector = getConnector();
    $connector->withMockClient($mockClient);

    $id = 'rrwu2qktznb7feez4slr2o67qm';
    $request = new PostPredictionCancel($id);
    $response = $connector->send($request);

    expect($response->ok())
        ->toBeTrue();
})->skip('Cancellation endpoint always returns a 404');
