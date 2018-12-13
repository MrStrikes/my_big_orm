<?php

namespace MBO;

class MBOBuilder
{
    private $select = [];

    private $delete = [];

    private $update = [];

    private $insert = [];

    public function SELECT(...$selected)
    {
        $actualSelect = $this->getSelect();
        foreach($selected as $item) {
            if (in_array($item, $this->getCol())) {
                $actualSelect[] = $item;
            }
        }
        $this->setSelect($actualSelect);
        return $this;
    }

    public function DELETE(...$deleted)
    {
        $this->setDelete(array_push($this->getDelete(), $deleted));
        return $this;
    }

    public function UPDATE(...$updated)
    {
        $this->setInsert(array_push($this->getUpdate(), $updated));
        return $this;
    }

    public function INSERT(...$inserted)
    {
        $this->setInsert(array_push($this->getInsert(), $inserted));
        return $this;
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

    public function getDelete()
    {
        return $this->delete;
    }

    public function setDelete($delete)
    {
        $this->delete = $delete;
        return $this;
    }

    public function getUpdate()
    {
        return $this->update;
    }

    public function setUpdate($update)
    {
        $this->update = $update;
        return $this;
    }

    public function getInsert()
    {
        return $this->insert;
    }

    public function setInsert($insert)
    {
        $this->insert = $insert;
        return $this;
    }
}
