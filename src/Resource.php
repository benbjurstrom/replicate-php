<?php

namespace BenBjurstrom\Replicate;

use Saloon\Contracts\Connector;

class Resource
{
    public function __construct(protected Connector $connector)
    {
        //
    }
}
