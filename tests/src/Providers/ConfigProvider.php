<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu\Tests\Providers;

class ConfigProvider
{
    private $specimen1 = ['services' => ['alpha', 'beta', 'gamma', 'delta', 'epsilon', 'zeta']];

    private $specimen2 = [
        'services' => [
            'ProviderInterface' => [
                'factory' => [
                    'OldProvider' => [
                        'ImprovedXMLAdapter', 'str: Hello World! '
                    ]
                ],
                'setup' => [
                    ['proceed' => "str:ok"]
                ]
            ],

            'ImprovedXMLAdapter' => [
                'setup' => [
                    ['proceed' => "str: XML Config"]
                ]
            ]
        ]
    ];

    private $specimen3 = [
        'services' => [
            'ProviderInterface' => [
                'factory' => [
                    'OldProvider' => [
                        'ImprovedXMLAdapter', 'str: Hello World! '
                    ]
                ],
                'setup' => [
                    ['proceed' => ["str:ok"]]
                ]
            ],

            'ImprovedXMLAdapter' => [
                'setup' => [
                    ['proceed' => ["str: XML Config"]]
                ]
            ]
        ]
    ];

    public function provider()
    {
        return [
            'specimen1' => ['config/specimen1.yaml', $this->specimen1],
            'specimen2' => ['config/specimen2.yaml', $this->specimen2],
            'specimen3' => ['config/specimen3.yaml', $this->specimen3],
        ];
    }
}



