<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu\Tests\Providers;

class ContainerProvider
{
    protected $num = 1;

    public function provider_DataTransferProjectStub()
    {
        $path = PATH_TO_RESOURCES;
        $config_file_path = fn($i) => $path . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'datatransferprojectstub_spec' . $i .'.yaml';
        $container_file_path = fn($i) => $path . DIRECTORY_SEPARATOR . 'container' . DIRECTORY_SEPARATOR . 'datatransferprojectstub_spec' . $i .'_container.dat';
        $items_file_path = fn($i) => $path. DIRECTORY_SEPARATOR . 'container' . DIRECTORY_SEPARATOR . 'datatransferprojectstub_spec' . $i .'_entities.dat';

        $result = [];
        for($i = 1; $i<=$this->num; $i++){
            $container = base64_decode(file_get_contents($container_file_path($i)));
            $items = unserialize(file_get_contents($items_file_path($i)));
            $result[] = [$config_file_path($i), $items, $container];
        }
        return $result;
    }
}