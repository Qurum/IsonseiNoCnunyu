<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

use Abethropalle\Utils\Graph\GraphBuilder;

class Container
{
    protected ServiceAssemblyProvider$service_provider;

    public function setServiceAssemblyProvider(ServiceAssemblyProvider $sp)
    {
        $this->service_provider = $sp;
    }
}