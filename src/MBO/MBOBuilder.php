<?php

namespace MBO;

class MBOBuilder
{
    private $select = [];

    public function SELECT(...$selected)
    {
        $this->setSelect(array_push($this->getSelect(), $selected));
        var_dump($this->getSelect());
    }

    public function getSelect()
    {
        return $this->select;
    }

    public function setSelect($select)
    {
        $this->select = $select;
        return $this;
    }
}
