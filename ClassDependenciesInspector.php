<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

class ClassDependenciesInspector
{
    protected $reflection_class;

    public function __construct(
        protected $class_name,
        protected $setup_methods = []
    )
    {
        $this->reflection_class = new \ReflectionClass($this->class_name);
    }

    protected function getMethodDependencies(\ReflectionMethod $method)
    {
        $result = [];
        foreach ($method->getParameters() as $parameter) {
            if ($parameter->isOptional()) {
                continue;
            }
            if (!$parameter->hasType()) {
                throw new \Exception("Parameter {$parameter->name} must have a type");
            }
            $result[] = '\\' . $parameter->getType()->getName();
        }
        return $result;
    }

    public function getSetupDependencies()
    {
        $result = [];
        foreach ($this->setup_methods as $method_name) {
            if (!$this->reflection_class->hasMethod($method_name)) {
                throw new \Exception("There is no method $method_name");
            }
            $method = $this->reflection_class->getMethod($method_name);
            $result = array_merge($result, $this->getMethodDependencies($method));
        }
        return $result;
    }

    public function getConstructorDependencies()
    {
        $constructor = $this->reflection_class->getConstructor();
        if (is_null($constructor)) {
            return [];
        }
        return $this->getMethodDependencies($constructor);
    }

    public function getDependencies()
    {
        return array_merge(
            $this->getConstructorDependencies(),
            $this->getSetupDependencies()
        );
    }
}