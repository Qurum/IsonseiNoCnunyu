<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

use Abethropalle\Utils\Graph\DepthFirstSearchCycleDetector;
use Abethropalle\Utils\Graph\GraphBuilder;

class Container
{
    public function __construct($path){
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

    public function setAssembler(Assembler $asm){
        $this->assembler = $asm;
    }

    protected function getDependenciesTuples($service_name){
        $service = $this->service_provider->get($service_name);
        $args = $service->getConstructorArgs();
        if(empty($args)){
            return [];
        } else{
            $result = [];
            foreach ($args as $arg){
                if(str_starts_with($arg, 'str:')){ continue;}
                if(in_array($arg, ['bool','int','float','string','array','object','callable','iterable','resource','NULL'])){ continue;}
                $result[] = [$service_name, $arg];
                $result = array_merge($result, $this->getDependenciesTuples($arg));
            }
            return $result;
        }
    }

    protected function checkCircularDependencies($name)
    {
        $graph = new GraphBuilder();
        $callback = fn($arrow) => $graph->arrow($arrow[0], $arrow[1]);
        $dependencies = $this->getDependenciesTuples($name);
        array_walk($dependencies,$callback);
        $d = new DepthFirstSearchCycleDetector($graph->build());
        $result = ['result' => $d->detect()];
        if($result['result']){$result['cycle'] = $d->getCycle();}
        return (object)$result;
    }

    public function has($name){
        return $this->service_provider->has($name);
    }

    public function get($name){
        if(is_null($this->service_provider)){
            throw new \Exception('Провайдер сервисов не установлен');
        }

        if(!isset($this->instances[$name])){
            $assembly = $this->service_provider->get($name);
            $has_circular = $this->checkCircularDependencies($name);
            if($has_circular->result){
                throw new \Exception("Циклическая зависимость:" . $has_circular->cycle);
            } else {
                if(is_null($this->assembler)){
                    throw new \Exception('Ассемблер не установлен');
                }
                $this->instances[$name] = $this->assembler->assemble($assembly);
            }
        }
        return $this->instances[$name];
    }
}