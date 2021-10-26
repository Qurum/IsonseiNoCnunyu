<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

/**
 * Директор для сервисов, созданных из файлов на диске.
 */
class AutowiredServiceDirector extends ServiceDirector
{
    public function createService($name, $data)
    {
        $class_inspector = new ClassDependenciesInspector($data['class_name']);
        $this->builder->reset();
        $this->builder->setName($name);
        $this->builder->setFactory(
            $data['class_name'],
            $class_inspector->getConstructorDependencies()
        );
        return $this->builder->build();
    }
}