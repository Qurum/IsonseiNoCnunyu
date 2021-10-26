<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

/**
 * Строитель объекта stdObject, описывающего сервис
 */
class ServiceBuilder
{
    protected $service;

    public function __construct()
    {
        $this->reset();
    }

    /**
     * Сбросить строитель.
     */
    public function reset()
    {
        $this->service = [
            'name' => '',
            'factory' => '',
            'args' => [],
            'setup' => []
        ];
    }

    /**
     * Установить имя сервиса.
     *
     * @param $name
     */
    public function setName($name)
    {
        $this->service['name'] = $name;
    }

    /**
     * Установить фабрику для сервиса
     *
     * @param $factory
     * @param array $args
     */
    public function setFactory($factory, $args = [])
    {
        $this->service['factory'] = $factory;
        $this->service['args'] = $this->wrapArgs($args);
    }

    protected function wrapArgs($args)
    {
        return is_array($args) ? $args : [$args];
    }

    /**
     * Добавить метод, который будет вызван после создания объекта
     * с указанным именем будет вызван с указанными аргументами.
     *
     * @param $method
     * @param array $args
     */
    public function addSetup($method, $args = [])
    {
        $this->service['setup'][] = [
            'method' => $method,
            'args' => $this->wrapArgs($args)
        ];
    }

    /**
     * Построить сервис.
     *
     * @return object
     */
    public function build()
    {
        return (object)$this->service;
    }
}