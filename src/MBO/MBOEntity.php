<?php

namespace MBO;

abstract class MBOEntity extends MBOBuilder implements EntityInterface
{
    public function __construct()
    {
        parent::__construct();
    }

}