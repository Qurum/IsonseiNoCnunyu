<?php
/*
 * Copyright (c) Ronia Rebane 2021.
 * Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted.
 */

namespace Abethropalle\IsonseiNoChunyu;

interface ServiceDirectorInterface
{
    public function setBuilder($builder);

    public function createService($name, $data);
}