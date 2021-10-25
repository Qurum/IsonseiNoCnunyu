<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

use ReflectionClass;
use ReflectionMethod;

class Assembler
{
    public function __construct(
        protected ServiceAssemblyProvider $service_provider
    )
    {
    }

    protected function assembleArgs($arg_names)
    {
        $args = [];
        foreach ($arg_names as $arg_name) {
            $arg = '';
            $prefix = 'str:';
            if (str_starts_with($arg_name, $prefix)) {
                $arg = trim(substr($arg_name, strlen($prefix)));
            } else {
                $arg = $this->service_provider->get($arg_name);
                $arg = $this->assemble($arg);
            }
            $args[] = $arg;
        }
        return $args;
    }

    public function assemble(Assembly $item)
    {
        $factory = $item->getClass();
        $args = $this->assembleArgs($item->getConstructorArgs());
        $class = new ReflectionClass($factory);
        $instance = $class->newInstanceArgs($args);
        $setup_callback = function ($setup) use ($instance) {
            $method = new ReflectionMethod($instance, $setup['method']);

            $method->invokeArgs($instance, $this->assembleArgs($setup['args']));
        };
        $setup = $item->getSetup();
        array_walk($setup, $setup_callback);
        return $instance;
    }
}