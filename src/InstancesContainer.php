<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

class InstancesContainer
{
    protected array $instances = [];

    public function add($name, $instance)
    {
        $this->instances[$name] = $instance;
    }

    public function get($name){
        return $this->instances[$name];
    }

    public function has($name){
        return isset($this->instances[$name]);
    }
}