<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

/**
 * Глобальный хелпер для работы с именами классов.
 */
class NameHelper
{
    protected static $namespace = '';

    /**
     * @return string
     */
    public static function getNamespace()
    {
        return self::$namespace;
    }

    /**
     *
     * @param $ns
     */
    public static function setNamespace($ns)
    {
        self::$namespace = $ns;
    }

    /**
     * Создаёт из частично квалифицированного имени класса краткое имя и полностью квалифицированное имя.
     * Возвращает stdObject с полями short и full.
     *
     * @param $name
     * @return object
     */
    public static function get($name)
    {
        $short = str_starts_with($name, self::$namespace)
            ? substr($name, strlen(self::$namespace) + 1)
            : $name;
        $full = self::$namespace . '\\' . $short;
        return (object)['short' => $short, 'full' => $full];
    }
}