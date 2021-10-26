<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

use Abethropalle\IsonseiNoChunyu\Container;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    /**
     * @dataProvider \Abethropalle\IsonseiNoChunyu\Tests\Providers\ContainerProvider::provider_DataTransferProjectStub
     */
    public function testBasicFunctionality($config, $items, $serialized_object)
    {
        $container = new Container($config);
        array_walk($items, fn($item) => $container->get($item));
        $this->assertEquals($serialized_object, serialize($container));
    }
}
