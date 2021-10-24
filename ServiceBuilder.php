<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

class ServiceBuilder
{
    protected $service;

    public function __construct(){
        $this->reset();
    }

    public function reset(){
        $this->service = [
            'name' => '',
            'factory' => '',
            'args' => [],
            'setup' => []
        ];
    }

    public function setName($name){
        $this->service['name'] = $name;
    }

    public function setFactory($factory, $args = []){
        $this->service['factory'] = $factory;
        $this->service['args'] = $this->wrapArgs($args);
    }

    public function addSetup($method, $args = []){
        $this->service['setup'][] = [
                'method' => $method,
                'args' => $this->wrapArgs($args)
            ];
    }

    public function build(){
        return (object) $this->service;
    }

    protected function wrapArgs($args){
        return is_array($args)?$args:[$args];
    }
}