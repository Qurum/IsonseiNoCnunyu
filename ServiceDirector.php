<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

/**
 * Выделена общая для всех директоров установка Строителя.
 */
abstract class ServiceDirector implements ServiceDirectorInterface
{
    protected $builder;

    /**
     * @param $builder
     */
    public function setBuilder($builder)
    {
        $this->builder = $builder;
    }
}