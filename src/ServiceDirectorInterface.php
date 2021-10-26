<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

/**
 * Интерфейс для директоров, создающих сервисы с помощью строителя.
 */
interface ServiceDirectorInterface
{
    /**
     * Внедрить Строителя.
     *
     * @param $builder
     * @return mixed
     */
    public function setBuilder($builder);

    /**
     * Создать сервис с заданным именем на основе указанных данных.
     *
     * @param $name
     * @param $data
     * @return mixed
     */
    public function createService($name, $data);
}