<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

use Abethropalle\IsonseiNoChunyu\ImplementationMapper;
use PHPUnit\Framework\TestCase;

class ImplementationMapperTest extends TestCase
{

    /**
     * @dataProvider provider
     */
    public function testGetMap($expected_map)
    {
        $im = new ImplementationMapper(PATH_TO_DATATRANSFERPROJECTSTUB, 'Abethropalle\DataTransferProjectStub');
        $actual_map = $im->getMap();
        $this->assertEquals($expected_map, $actual_map);
    }

    public function provider()
    {
        $map_DataTransferProjectStub = [
            "\Abethropalle\DataTransferProjectStub\LoggerInterface" => [
                "Abethropalle\DataTransferProjectStub\EchoLogger",
                "Abethropalle\DataTransferProjectStub\FileLogger"
            ],
            "Abethropalle\DataTransferProjectStub\EchoLogger" => [
                "Abethropalle\DataTransferProjectStub\EchoLogger"
            ],
            "Abethropalle\DataTransferProjectStub\FileLogger" => [
                "Abethropalle\DataTransferProjectStub\FileLogger"
            ],
            "\Abethropalle\DataTransferProjectStub\FileManagerInterface" => [
                "Abethropalle\DataTransferProjectStub\FileManager"
            ],
            "Abethropalle\DataTransferProjectStub\FileManager" => [
                "Abethropalle\DataTransferProjectStub\FileManager"
            ],
            "\Abethropalle\DataTransferProjectStub\AdapterInterface" => [
                "Abethropalle\DataTransferProjectStub\IdentityAdapter",
                "Abethropalle\DataTransferProjectStub\JsonXmlAdapter",
                "Abethropalle\DataTransferProjectStub\XmlJsonAdapter"
            ],
            "Abethropalle\DataTransferProjectStub\IdentityAdapter" => [
                "Abethropalle\DataTransferProjectStub\IdentityAdapter"
            ],
            "\Abethropalle\DataTransferProjectStub\ProviderInterface" => [
                "Abethropalle\DataTransferProjectStub\JsonProvider",
                "Abethropalle\DataTransferProjectStub\XmlProvider"
            ],
            "Abethropalle\DataTransferProjectStub\JsonProvider" => [
                "Abethropalle\DataTransferProjectStub\JsonProvider"
            ],
            "\Abethropalle\DataTransferProjectStub\BasicAdapter" => [
                "Abethropalle\DataTransferProjectStub\JsonXmlAdapter"
            ],
            "Abethropalle\DataTransferProjectStub\JsonXmlAdapter" => [
                "Abethropalle\DataTransferProjectStub\JsonXmlAdapter"
            ],
            "Abethropalle\DataTransferProjectStub\Main" => [
                "Abethropalle\DataTransferProjectStub\Main"
            ],
            "\Abethropalle\DataTransferProjectStub\ResolverInterface" => [
                "Abethropalle\DataTransferProjectStub\Resolver"
            ],
            "Abethropalle\DataTransferProjectStub\Resolver" => [
                "Abethropalle\DataTransferProjectStub\Resolver"
            ],
            "Abethropalle\DataTransferProjectStub\XmlJsonAdapter" => [
                "Abethropalle\DataTransferProjectStub\XmlJsonAdapter"
            ],
            "Abethropalle\DataTransferProjectStub\XmlProvider" => [
                "Abethropalle\DataTransferProjectStub\XmlProvider"
            ]
        ];

        return [
            'DataTransferProjectStub' => [$map_DataTransferProjectStub]
        ];
    }
}
