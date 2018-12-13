<?php

namespace MBO;

class MBOBuilder
{
    private $select = [];

    public function SELECT(...$selected)
    {
        $this->setSelect(array_push($select, $selected));
        var_dump($select);
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
