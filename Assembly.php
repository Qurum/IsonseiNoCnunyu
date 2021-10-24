<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

class Assembly
{
    public function __construct(
        protected string $class,
        protected array $args = [],
        protected array $setup = []
    ){
    }

    public function getClass(){
        return $this->class;
    }

    public function getConstructorArgs(){
        return $this->args;
    }
}