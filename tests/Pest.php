<?php

use BenBjurstrom\Replicate\Replicate;
use Saloon\Exceptions\DirectoryNotFoundException;
use Saloon\Exceptions\InvalidMockResponseCaptureMethodException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

function getConnector(): Replicate
{
    $apiToken = getenv('REPLICATE_API_TOKEN');

    return new Replicate(
        apiToken: $apiToken ? $apiToken : '',
    );
}

/**
 * @throws DirectoryNotFoundException
 * @throws InvalidMockResponseCaptureMethodException
 */
function getMockClient(string $class, string $fixture): MockClient
{
    return new MockClient([
        $class => MockResponse::fixture($fixture),
    ]);
}

function getMockConnector(string $class, string $fixture): Replicate
{
    $mockClient = getMockClient($class, $fixture);
    $connector = getConnector();

    return $connector->withMockClient($mockClient);
}
