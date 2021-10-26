<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

use Abethropalle\Utils\Graph\DepthFirstSearchCycleDetector;
use Abethropalle\Utils\Graph\GraphBuilder;

/**
 * Основной класс - реализация контейнера.
 * Строит зависимости автоматически.
 * Каждый сервис инстанцируется при первом получении.
 */
class Container
{
    /**
     * @param $path
     * путь к конфигурационному файлу
     */
    public function __construct($path)
    {
        $this->config = new Config($path);
        $namespace = isset($this->config['namespace']) ? '\\' . $this->config['namespace'] : '';
        $dir = $this->config['dir'] ?? '';
        NameHelper::setNamespace($namespace);
        $this->service_provider = new ServiceAssemblyProvider($path, $namespace, $dir);
        $this->assembler = new Assembler($this->service_provider);
    }

    protected Config $config;
    protected ServiceAssemblyProvider $service_provider;
    protected Assembler $assembler;
    protected array $instances = [];

    public function setServiceAssemblyProvider(ServiceAssemblyProvider $sp)
    {
        $this->service_provider = $sp;
    }

    public function setAssembler(Assembler $asm)
    {
        $this->assembler = $asm;
    }

    /**
     * Строит для данного сервиса массив с описанием графа зависимостей
     * в виде ["сервис", "зависимость"].
     * Встроенные типы не учитываются.
     *
     * @param $service_name
     * @return array
     */
    protected function getDependenciesTuples($service_name): array
    {
        $service = $this->service_provider->get($service_name);
        $args = $service->getConstructorArgs();
        if (empty($args)) {
            return [];
        } else {
            $result = [];
            foreach ($args as $arg) {
                if (str_starts_with($arg, 'str:')) {
                    continue;
                }
                if (in_array($arg, ['bool', 'int', 'float', 'string', 'array', 'object', 'callable', 'iterable', 'resource', 'NULL'])) {
                    continue;
                }
                $result[] = [$service_name, $arg];
                $result = array_merge($result, $this->getDependenciesTuples($arg));
            }
            return $result;
        }
    }

    /**
     * Проверяет, нет ли циклических зависимостей.
     * Возвращает объект stdClass с полем result типа bool.
     * Если цикл присутствует, то он будет описан в поле cycle.
     *
     * @param $name
     * @return object
     */
    protected function checkCircularDependencies($name)
    {
        $graph = new GraphBuilder();
        $callback = fn($arrow) => $graph->arrow($arrow[0], $arrow[1]);
        $dependencies = $this->getDependenciesTuples($name);
        array_walk($dependencies, $callback);
        $d = new DepthFirstSearchCycleDetector($graph->build());
        $result = ['result' => $d->detect()];
        if ($result['result']) {
            $result['cycle'] = $d->getCycle();
        }
        return (object)$result;
    }

    /**
     * Проверяет, зарегистрирован ли сервис в контейнере.
     *
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        return $this->service_provider->has($name);
    }

    /**
     * Возвращает инстанс по имени сервиса.
     *
     * @param $name
     * @return mixed|object
     * @throws \Exception
     */
    public function get($name)
    {
        if (is_null($this->service_provider)) {
            throw new \Exception('Провайдер сервисов не установлен');
        }

        if (!isset($this->instances[$name])) {
            $assembly = $this->service_provider->get($name);
            $has_circular = $this->checkCircularDependencies($name);
            if ($has_circular->result) {
                throw new \Exception("Циклическая зависимость:" . $has_circular->cycle);
            } else {
                if (is_null($this->assembler)) {
                    throw new \Exception('Ассемблер не установлен');
                }
                $this->instances[$name] = $this->assembler->assemble($assembly);
            }
        }
        return $this->instances[$name];
    }
}