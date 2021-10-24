<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

use ReflectionClass;

class ClassAnalyzer
{
    protected $reflection_class;

    public function __construct(
        protected $class_name
    )
    {
        $this->reflection_class = new ReflectionClass($this->class_name);
    }

    public function getParentClasses()
    {
        $result = [];
        $parent = $this->reflection_class->getParentClass();
        while (false !== $parent) {
            $result [] = [
                'name' => '\\' . $parent->getName(),
                'isAbstract' => $parent->isAbstract()
            ];
            $parent = $parent->getParentClass();
        }
        return $result;
    }

    public function getInterfaces()
    {
        return array_map(fn($name) => '\\' . $name, $this->reflection_class->getInterfaceNames());
    }

    public function isAbstract(){
        return $this->reflection_class->isAbstract();
    }

    public function isConcrete(){
        return $this->reflection_class->isInstantiable();
    }

    public function isInterface(){
        return $this->reflection_class->isInterface();
    }
}