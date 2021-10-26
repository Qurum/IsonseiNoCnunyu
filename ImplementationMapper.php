<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

/**
 * Инкапсулирует сопоставление имя интерфейса => массив имён имплементирующих классов.
 * Обнаруживает классы с помощью провайдера.
 * Используются полностью квалифицированные имена классов (т.е. с указанным неймспейсом).
 */
class ImplementationMapper
{
    protected ClassNamesProvider $provider;
    protected $map = [];
    protected $implementations = [];

    public function __construct(
        protected $path,
        protected $namespace
    )
    {
        $this->provider = new ClassNamesProvider($path, $this->namespace);
        $this->createMap();
    }

    /**
     * С помощью провайдера создаёт массив имплементаций.
     */
    protected function createMap()
    {
        foreach ($this->provider->nextClass() as $class) {
            if (
                class_exists($class, true) ||
                interface_exists($class, true)
            ) {
                $a = new ClassAnalyzer($class);
                if (!$a->isConcrete()) {
                    continue;
                }

                $this->implementations[] = $class;

                foreach ($a->getInterfaces() as $interface) {
                    $this->map[$interface][] = $class;
                }

                foreach ($a->getParentClasses() as $superclass) {
                    if ($superclass['isAbstract']) {
                        $this->map[$superclass['name']][] = $class;
                    }
                }

                $this->map[$class][] = $class;
            }
        }
    }

    /**
     * Отображение интерфейс => массив имплементаций.
     *
     * @return array
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * Возвращает массив имён конкретных классов - тех классов, экземпляры которых могут быть созданы.
     *
     * @return array
     */
    public function getImplementations()
    {
        return $this->implementations;
    }

    /**
     * Возвращает массив имплементаций для указанного типа.
     *
     * @param $type
     * @return mixed
     */
    public function getImplementationsByType($type)
    {
        return $this->map[$type];
    }

    public function has($type)
    {
        return !empty($this->map[$type]);
    }
}