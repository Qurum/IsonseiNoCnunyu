<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

use Exception;
use ReflectionClass;

class ServiceAssemblyProvider
{
    protected $services;
    protected $implementation_mapper;
    protected $config;

    public function __construct(
        $config_path,
        protected $namespace,
        $path = ''
    )
    {
        $this->implementation_mapper = new ImplementationMapper($path, $namespace);
        $this->registerServices($path, new Config($config_path));
    }

    protected function registerServices($path, $config)
    {
        $this->services = [];
        $builder = new ServiceBuilder();

        // services from disk
        $autowir_director = new AutowiredServiceDirector();
        $autowir_director->setBuilder($builder);
        $provider = new ClassNamesProvider($path, $this->namespace);
        foreach ($provider->nextClass() as $class_name) {
            $service_name = substr($class_name, strlen($this->namespace) + 1);
            $this->services[$service_name] = $autowir_director->createService($service_name, ['class_name' => $class_name]);
        }

        // services from config
        $expl_director = new ExplicityServiceDirector();
        $expl_director->setBuilder($builder);
        foreach ($config['services'] as $name => $data) {
            $this->services[$name] = $expl_director->createService($name, $data);
        }
    }

    public function has($name)
    {
        return isset($this->services[$name]);
    }

    protected function getServiceType(string $name)
    {
        if (!empty($this->services[$name])) {
            $factory = $this->services[$name]->factory;

            if(class_exists($factory, true)){
                return $factory;
            }

            $qualified_name = str_starts_with($factory, $this->namespace)
                ? $factory
                : $this->namespace . '\\' . $factory;
            return $qualified_name;
        }

        if (class_exists($name)) {
            $reflection = new ReflectionClass($name);
            if ($reflection->isInstantiable()) {
                return $name;
            }
        }
        throw new Exception("Service $name has no factory");
    }

    protected function getServiceArgs($name)
    {
        $cdi = new ClassDependenciesInspector($this->getServiceType($name));
        $args = $cdi->getConstructorDependencies();
        $passed_args = $this->services[$name]?->args ?? [];

        array_walk($passed_args, function ($value, $key) use (&$args) {
            $args[$key] = $value;
        });
        return $args;
    }

    protected function getServiceSetup($name)
    {
        return $this->services[$name]?->setup ?? [];
    }

    public function get(string $name): Assembly
    {
        $type = $this->getServiceType($name);
        $impl = $this->implementation_mapper->getImplementationsByType($type);
        if (empty($impl)) {
            throw new Exception("Factory $type has no implementation");
        }

        if (count($impl) != 1) {
            throw new Exception("Factory $type has multiple implementations");
        }

        return new Assembly(array_pop($impl), $this->getServiceArgs($name), $this->getServiceSetup($name));
    }
}