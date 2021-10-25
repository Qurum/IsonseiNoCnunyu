<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

use Abethropalle\IsonseiNoChunyu\ClassNamesProvider;
use PHPUnit\Framework\TestCase;

class ClassNamesProviderTest extends TestCase
{

    /**
     * @dataProvider provider
     */
    public function testCreateClassNamesList($expected_list)
    {
        $provider = new ClassNamesProvider(PATH_TO_DATATRANSFERPROJECTSTUB, '\Abethropalle\DataTransferProjectStub');
        $classes = [];
        foreach ($provider->nextClass() as $class) {
            $classes[] = $class;
        }
        sort($classes);
        $this->assertEquals($expected_list, $classes);
    }

    public function provider()
    {
        $classes_DataTransferProjectStub = [
            '\Abethropalle\DataTransferProjectStub\AdapterInterface',
            '\Abethropalle\DataTransferProjectStub\BasicAdapter',
            '\Abethropalle\DataTransferProjectStub\EchoLogger',
            '\Abethropalle\DataTransferProjectStub\FileLogger',
            '\Abethropalle\DataTransferProjectStub\FileManager',
            '\Abethropalle\DataTransferProjectStub\FileManagerInterface',
            '\Abethropalle\DataTransferProjectStub\IdentityAdapter',
            '\Abethropalle\DataTransferProjectStub\JsonProvider',
            '\Abethropalle\DataTransferProjectStub\JsonXmlAdapter',
            '\Abethropalle\DataTransferProjectStub\LoggerInterface',
            '\Abethropalle\DataTransferProjectStub\Main',
            '\Abethropalle\DataTransferProjectStub\ProviderInterface',
            '\Abethropalle\DataTransferProjectStub\Resolver',
            '\Abethropalle\DataTransferProjectStub\ResolverInterface',
            '\Abethropalle\DataTransferProjectStub\XmlJsonAdapter',
            '\Abethropalle\DataTransferProjectStub\XmlProvider'
        ];
        sort($classes_DataTransferProjectStub);
        return [
            'DataTransferProjectStub' => [$classes_DataTransferProjectStub]
        ];
    }
}
