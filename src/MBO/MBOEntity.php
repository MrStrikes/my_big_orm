<?php

namespace MBO;

abstract class MBOEntity extends MBOBuilder implements EntityInterface
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getById($id, $fetchStyle = 2) {
        $this
            ->clear()
            ->SELECT('*')
            ->WHERE(['id', '=', $id])
            ->buildQuery();
        return $this->execute($fetchStyle);
    }

}