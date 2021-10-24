<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

class ServiceProvider
{
    protected $services;
    protected $implementation_mapper;

    public function __construct(
        $config_path,
        protected $namespace,
        $path = ''
    )
    {
        $config = new Config($config_path);
        $this->services = [];
        $director = new ServiceDirector();
        $director->setBuilder(new ServiceBuilder());
        foreach ($config['services'] as $name => $data) {
            $this->services[$name] = $director->createService($name, $data);
        }
        $this->implementation_mapper = new ImplementationMapper($path, $namespace);
    }

    public function has($name)
    {
        return isset($this->services[$name]);
    }

    protected function getServiceType(string $name)
    {
        if (! empty($this->services[$name])){
            $factory = $this->services[$name]->factory;
            if (class_exists($factory)) {
                return $factory;
            } else {
                return $this->namespace . '\\' . $factory;
            }
        }

        if (class_exists($name)) {
            $reflection = new \ReflectionClass($name);
            if ($reflection->isInstantiable()) {
                return $name;
            }
        };
        throw new \Exception("Service $name has no factory");
    }

    public function get(string $name){

        // TODO: replace string return type to assembly

        $type = $this->getServiceType($name);
        $impl = $this->implementation_mapper->getImplementationsByType($type);
        if (empty($impl)){
            throw new \Exception("Factory $type has no implementation");
        }

        if (count($impl) != 1){
            throw new \Exception("Factory $type has multiple implementations");
        }

        return array_pop($impl);
    }
}