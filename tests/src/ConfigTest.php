<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;


use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

class ConfigTest extends TestCase
{

    /**
     * @dataProvider \Abethropalle\IsonseiNoChunyu\Tests\Providers\ConfigProvider::provider
     */
    public function testConfigStructure($path, $data)
    {
        $conf_object = new Config(PATH_TO_TESTS_RESOURCES . DIRECTORY_SEPARATOR . $path);
        $conf_array = (array)$conf_object;
        $conf_array = array_pop($conf_array);
        $this->assertEquals($data, $conf_array);
    }
}
