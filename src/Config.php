<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

use ArrayAccess;
use Symfony\Component\Yaml\Yaml;

/**
 * Простая обёртка для файлов в yaml на основе компонента Symphony\Yaml
 */
class Config implements ArrayAccess
{
    protected $container = [];

    public function __construct($path)
    {
        $this->container = Yaml::parseFile($path);
        if (!isset($this->container['services'])) {
            $this->container['services'] = [];
        }
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }
}