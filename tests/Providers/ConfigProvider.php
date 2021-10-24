<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu\Tests\Providers;

class ConfigProvider
{
    public function provider()
    {
        return [
            'specimen1' =>
                [
                    'config/specimen1.yaml',
                    ['services' => ['alpha', 'beta', 'gamma', 'delta', 'epsilon', 'zeta']]
                ]
            ];
    }
}



