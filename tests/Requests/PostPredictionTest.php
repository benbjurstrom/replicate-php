<?php

use BenBjurstrom\Replicate\Data\PredictionData;
use BenBjurstrom\Replicate\Requests\PostPrediction;

test('post prediction endpoint hello', function () {
    $connector = getMockConnector(
        PostPrediction::class,
        'postPredictionAlice'
    );

    $version = '5c7d5dc6dd8bf75c1acaa8565735e7986bc5b66206b55cca93cb72c9bf15ccaa';
    $input = [
        'text' => 'Alice',
    ];
    $request = new PostPrediction($version, $input);
    $response = $connector->send($request);

    /* @var PredictionData $data */
    $data = $response->dtoOrFail();

    expect($data->id)
        ->toBe('123');
});

it('sends a post prediction request for a stable diffusion model', function () {
    $connector = getMockConnector(
        PostPrediction::class,
        'postPredictionAstronaut'
    );

    $version = 'db21e45d3f7023abc2a46ee38a23973f6dce16bb082a930b0c49861f96d1e5bf';
    $input = [
        'model' => 'stable-diffusion-2-1',
        'prompt' => 'a photo of an astronaut riding a horse on mars',
        'negative_prompt' => 'moon, alien, spaceship',
        'width' => 768,
        'height' => 768,
        'num_inference_steps' => 50,
        'guidance_scale' => 7.5,
        'scheduler' => 'DPMSolverMultistep',
        'seed' => null,
    ];
    $request = new PostPrediction($version, $input);
    $response = $connector->send($request);

    /* @var PredictionData $data */
    $data = $response->dtoOrFail();

    expect($data->id)
        ->toBe('la5xlbbrfzg57ip5jlx6obmm5y');
});
