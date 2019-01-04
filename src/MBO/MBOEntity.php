<?php

namespace MBO;

abstract class MBOEntity extends MBOBuilder implements EntityInterface
{
    public function __construct()
    {
        parent::__construct();
    }

    public function buildEntity(array $data) {
        foreach ($this->getCol() as $col) {
            $func = 'set' . ucfirst($col);
            $pos = strpos($func, '_');
            while (!($pos === false)) {
                $func[$pos + 1] = ucfirst($func[$pos + 1]);
                $func = substr($func, 0, $pos) . substr($func, $pos + 1);
                $pos = strpos($func, '_');
            }
            $this->$func($data[$col]);
        }
        return $this;
    }

    public function getById($id, $fetchStyle = 2) {
        $this
            ->clear()
            ->SELECT('*')
            ->WHERE(['id', '=', $id])
            ->buildQuery();
        return $this->buildEntity($this->execute($fetchStyle)[0]);
    }

}