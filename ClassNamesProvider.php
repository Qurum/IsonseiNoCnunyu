<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

use Exception;
use Generator;

/**
 * Провайдер для имён классов.
 *
 * Ищет классы в соответствии с PSR-4.
 * Принимает на вход путь к каталогу с файлами классов и пространство имён.
 */
class ClassNamesProvider
{
    /**
     * @param string $path
     * @param string $namespace
     * @throws Exception
     */
    public function __construct(
        protected string $path,
        protected string $namespace = ''
    )
    {
        $this->check();
    }

    /**
     * Проверка доступности директории.
     * @throws Exception
     */
    protected function check()
    {
        if (!is_dir($this->path)) {
            throw new Exception("Directory {$this->path} does not exist");
        }

        if(!is_readable($this->path)){
            throw new Exception("{$this->path} is not readable");
        }
    }

    /**
     * Генератор имен классов.
     * @return Generator
     */
    public function nextClass()
    {
        $directory = dir($this->path);
        while (false !== ($filename = $directory->read())) {
            if ($filename == '.' or $filename == '..') {
                continue;
            }

            $current_path = $directory->path . DIRECTORY_SEPARATOR . $filename;

            // Если файл - класс
            if (is_file($current_path) &&
                (pathinfo($current_path, PATHINFO_EXTENSION) == 'php')
            ) {
                yield $this->namespace . '\\' . basename($filename, '.php');
            }

            // Если файл - папка классов
            if (is_dir($current_path)) {
                $new_provider = new (get_class($this))($current_path, $this->namespace . '\\' . $filename);
                foreach ($new_provider->nextClass() as $f) {
                    yield $f;
                }
            }
        }
    }
}