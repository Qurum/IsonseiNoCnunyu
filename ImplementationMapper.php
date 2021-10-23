<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

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

    protected function createMap()
    {
        foreach ($this->provider->nextClass() as $class) {
            if (
                class_exists($class, true) ||
                interface_exists($class, true)
            ) {
                $a = new ClassAnalyzer($class);
                if(! $a->isConcrete()){
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

    public function getMap(){
        return $this->map;
    }

    public function getImplementations(){
        return $this->implementations;
    }

    public function getImplementationsByType($type){
        return $this->map[$type];
    }
}