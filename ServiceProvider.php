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
        $builder = new ServiceBuilder();
        $director->setBuilder($builder);

        $provider = new ClassNamesProvider($path, $this->namespace);
        foreach ($provider->nextClass() as $class_name){
            $class_inspector = new ClassDependenciesInspector($class_name);
            $service_name = substr($class_name, strlen($this->namespace)+1);
            $builder->reset();
            $builder->setName($service_name);
            $builder->setFactory(
                $class_name,
                $class_inspector->getConstructorDependencies());
            $this->services[$service_name] = $builder->build();
        }

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

    protected function getServiceArgs($name){
        $cdi = new ClassDependenciesInspector($this->getServiceType($name));
        $args = $cdi->getConstructorDependencies();
        $passed_args = $this->services[$name]?->args??[];

        array_walk($passed_args, function($value, $key) use (&$args){
            $args[$key] = $value;
        });
         return $args;
    }

    public function getAssembly(string $name): Assembly{
        $type = $this->getServiceType($name);
        $impl = $this->implementation_mapper->getImplementationsByType($type);
        if (empty($impl)){
            throw new \Exception("Factory $type has no implementation");
        }

        if (count($impl) != 1){
            throw new \Exception("Factory $type has multiple implementations");
        }

        return new Assembly(array_pop($impl), $this->getServiceArgs($name));
    }
}