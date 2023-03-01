<?php


use BenBjurstrom\Replicate\Example;

it('foo', function () {
    $example = new Example();

    $result = $example->foo();

    expect($result)->toBe('bar');
});
