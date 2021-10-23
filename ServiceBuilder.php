<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

class ServiceBuilder
{
    protected $service = [
        'name' => '',
        'factory' => '',
        'setup' => []
    ];

    public function setName($name){
        $this->service['name'] = $name;
    }

    public function setFactory($type){
        $this->service['factory'] = $type;
    }

    public function addSetup($method, $args = []){
        $this->service['setup'][] = [
                'method' => $method,
                'args' => $args
            ];
    }

    public function build(){
        return (object) $this->service;
    }
}