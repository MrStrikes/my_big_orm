<?php

namespace MBO;


use MBO\EntityInterface;

abstract class MBOBuilder
{
    private $select = [];

    private $delete = [];

    private $update = [];

    private $insert = [];

    public function SELECT(...$selected): MBOBuilder
    {
        $actualSelect = $this->getSelect();
        foreach($selected as $item) {
            if (in_array($item, $this->getCol())) {
                $actualSelect[] = $item;
            }
        }
        return $this->setSelect($actualSelect);

    }

    public function DELETE(...$deleted): MBOBuilder
    {
        $actualDelete = $this->getDelete();
        foreach ($deleted as $item) {
            if (in_array($item, $this->getCol())) {
                $actualDelete[] = $item;
            }
        }
        return $this->setDelete($actualDelete);

    }

    public function UPDATE(...$updated): MBOBuilder
    {
        $actualUpdate = $this->getUpdate();
        foreach ($updated as $item) {
            if (in_array($item, $this->getCol())) {
                $actualUpdate[] = $item;
            }
        }
        return $this->setUpdate($actualUpdate);

    }

    public function INSERT(...$inserted)
    {
        $actualInsert = $this->getSelect();
        foreach ($inserted as $item) {
            if (in_array($item, $this->getCol())) {
                $actualInsert[] = $item;
            }
        }
        return $this->setInsert($actualInsert);

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
