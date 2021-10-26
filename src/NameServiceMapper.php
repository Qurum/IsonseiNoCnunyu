<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

use PhpParser\Node\Name;
use TheSeer\Tokenizer\Exception;

/**
 * Инкапсулирует сопоставление имя => объект сервиса.
 * См. описание используемого Строителя
 */
class NameServiceMapper
{
    protected $services = [];
    protected $builder;
    protected $namespace = '';

    /**
     * @param string $config_path
     * Путь к файлу конфигурации
     *
     * @param string $path
     * Путь к папке с классами
     */
    public function __construct(
        string $config_path = 'config.php',
        string $path = ''
    )
    {
        $this->builder = new ServiceBuilder();
        $this->namespace = NameHelper::getNamespace();
        $this->fromDisk($path);
        $this->fromConfig(new Config($config_path));
    }

    /**
     * @param $path
     * @throws \Exception
     */
    protected function fromDisk($path)
    {
        $autowir_director = new AutowiredServiceDirector();
        $autowir_director->setBuilder($this->builder);
        $provider = new ClassNamesProvider($path, $this->namespace);
        foreach ($provider->nextClass() as $class_name) {
            $service_name = NameHelper::get($class_name)->short;
            $this->services[$service_name] = $autowir_director->createService($service_name, ['class_name' => $class_name]);
        }
    }

    /**
     * @param $config
     */
    protected function fromConfig($config)
    {
        $expl_director = new ExplicityServiceDirector();
        $expl_director->setBuilder($this->builder);
        foreach ($config['services'] as $name => $data) {
            $this->services[$name] = $expl_director->createService($name, $data);
        }
    }

    public function has($name)
    {
        $name = NameHelper::get($name)->short;
        return isset($name);
    }

    /**
     * Возвращает объект сервиса, соответствующий имени
     *
     * @param $name
     * @return mixed
     */
    public function get($name)
    {
        $name = NameHelper::get($name)->short;
        return $this->services[$name];
    }

}