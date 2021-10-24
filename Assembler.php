<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

class Assembler
{
    public function __construct(
        protected ServiceProvider $service_provider
    ){
    }

    public function assemble(Assembly $item)
    {
        $factory = $item->getClass();
        $args = [];
        foreach($item->getConstructorArgs() as $arg_name){
            $arg = $this->service_provider->getAssembly($arg_name);
            $args[] = $this->assemble($arg);
        }
        $class = new \ReflectionClass($factory);
        return $class->newInstanceArgs($args);
    }
}