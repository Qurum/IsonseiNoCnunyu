<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

use Abethropalle\IsonseiNoChunyu\ClassDependenciesInspector;
use PHPUnit\Framework\TestCase;

class ClassDependenciesInspectorTest extends TestCase
{
    /**
     * @dataProvider providerConstructorDependencies
     */
    public function testGetConstructorDependencies(string $class_name, array $dependencies)
    {
        $cdi = new ClassDependenciesInspector($class_name);
        $this->assertEquals($dependencies, $cdi->getConstructorDependencies());
    }

    public function providerConstructorDependencies()
    {
        return [
            'EchoLogger' =>
                [
                    '\Abethropalle\DataTransferProjectStub\EchoLogger',
                    []
                ],

            'FileLogger' =>
                [
                    '\Abethropalle\DataTransferProjectStub\FileLogger',
                    [
                        '\Abethropalle\DataTransferProjectStub\FileManagerInterface',
                        'string'
                    ]
                ],

            'Main' =>
                [
                    '\Abethropalle\DataTransferProjectStub\Main',
                    [
                        '\Abethropalle\DataTransferProjectStub\ProviderInterface',
                        '\Abethropalle\DataTransferProjectStub\ResolverInterface',
                        '\Abethropalle\DataTransferProjectStub\LoggerInterface'
                    ]
                ],

            'Resolver' =>
                [
                    '\Abethropalle\DataTransferProjectStub\Resolver',
                    [
                        '\Abethropalle\DataTransferProjectStub\JsonXmlAdapter',
                        '\Abethropalle\DataTransferProjectStub\XmlJsonAdapter',
                        '\Abethropalle\DataTransferProjectStub\IdentityAdapter'
                    ]
                ]
        ];
    }
}
