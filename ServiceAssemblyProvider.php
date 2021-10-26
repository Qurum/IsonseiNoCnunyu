<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

use Exception;
use ReflectionClass;

/**
 * Предоставляет объекты типа Assembly, соответствующие именам сервисоы
 */
class ServiceAssemblyProvider
{
    protected $services;
    protected $implementation_mapper;
    protected $name_service_mapper;
    protected $config;

    /**
     * @param $config_path
     * @param $namespace
     * @param string $path
     */
    public function __construct(
        $config_path,
        protected $namespace,
        $path = ''
    )
    {
        $this->name_service_mapper = new NameServiceMapper($config_path, $path);
        $this->implementation_mapper = new ImplementationMapper($path, $namespace);
    }

    /**
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        return $this->name_service_mapper->has($name);
    }

    /**
     * Возвращает полностью квалифицированное имя класса, отвечающего имени сервиса.
     *
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    protected function getServiceImplementation(string $name)
    {
        $name = NameHelper::get($name)->short;
        if (!$this->has($name)) {
            throw new Exception("Service $name has no factory");
        }

        $service = $this->name_service_mapper->get($name);
        $factory = NameHelper::get($service->factory)->full;
        $impl = $this->implementation_mapper->getImplementationsByType($factory);
        if (empty($impl)) {
            throw new Exception("Factory $factory has no implementation");
        }
        if (count($impl) != 1) {
            throw new Exception("Factory $factory has multiple implementations");
        }

        return $impl[0];
    }

    /**
     * Список имен сервисов, поставляющих аргументы конструктора.
     *
     * @param $name
     * @return array
     * @throws Exception
     */
    protected function getServiceArgs($name)
    {
        $service = $this->name_service_mapper->get($name);
        $impl = $this->getServiceImplementation($name);
        $cdi = new ClassDependenciesInspector($impl);
        $args = $cdi->getConstructorDependencies();
        $passed_args = $service->args ?? [];

        array_walk($passed_args, function ($value, $key) use (&$args) {
            $args[$key] = $value;
        });
        return $args;
    }

    /**
     * Массив описаний setup-методов.
     *
     * @param $name
     * @return array
     */
    protected function getServiceSetup($name)
    {
        return $this->name_service_mapper->get($name)?->setup ?? [];
    }

    /**
     * @param string $name
     * @return Assembly
     * @throws Exception
     */
    public function get(string $name): Assembly
    {
        $impl = $this->getServiceImplementation($name);
        return new Assembly($impl, $this->getServiceArgs($name), $this->getServiceSetup($name));
    }
}