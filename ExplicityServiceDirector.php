<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

use Exception;

class ExplicityServiceDirector extends ServiceDirector
{
    public function createService($name, $data)
    {
        $this->builder->reset();
        $this->builder->setName($name);
        switch (gettype($data)) {
            case 'string':
                $this->builder->setFactory($data);
                break;

            case 'array':
                empty($data['factory'])
                    ? $this->addFactory($name)
                    : $this->addFactory($data['factory']);

                empty($data['setup'])
                    ?: array_walk($data['setup'], fn($item) => $this->addSetup($item));
                break;
        }
        return $this->builder->build();
    }

    protected function addFactory($item)
    {
        switch (gettype($item)) {
            case 'string':
                $this->builder->setFactory($item);
                break;

            case 'array':
                $callback = fn($data, $key) => $this->builder->setFactory($key, $data);
                array_walk($item, $callback);
                break;
        }
    }

    protected function addSetup($item)
    {
        switch (gettype($item)) {
            case 'string':
                $this->builder->addSetup($item);
                break;

            case 'array':
                $callback = fn($data, $key) => $this->builder->addSetup($key, $data);
                array_walk($item, $callback);
                break;
        }
    }
}