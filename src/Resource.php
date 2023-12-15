<?php

namespace BenBjurstrom\Replicate;

use Saloon\Http\Connector;

class Resource
{
    public function __construct(protected Connector $connector)
    {
        //
    }
}
