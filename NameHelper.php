<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

class NameHelper
{
    protected static $namespace = '';

    public static function setNamespace($ns)
    {
        self::$namespace = $ns;
    }

    public static function getNamespace()
    {
        return self::$namespace;
    }

    public static function get($name)
    {
        $short = str_starts_with($name, self::$namespace)
            ? substr($name, strlen(self::$namespace)+1)
            : $name;
        $full = self::$namespace . '\\' . $short;
        return (object) ['short' => $short, 'full' => $full];
    }
}