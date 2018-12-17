<?php

namespace MBO;

abstract class MBOBuilder
{
    private $select = [];

    private $delete = [];

    private $update = [];

    private $insert = [];

    public function SELECT(...$selected)
    {
        $actualSelect = $this->getSelect();
        foreach ($selected as $item) {
            if (in_array($item, $this->getCol())) {
                $actualSelect[] = $item;
            }
        }
        if (empty($actualSelect)) {
            $this->setSelect("*");
        } else {
            $this->setSelect($actualSelect);
        }

        return $this;
    }

    public function DELETE(...$deleted)
    {
        $actualDelete = $this->getDelete();
        foreach ($deleted as $item) {
            if (in_array($item, $this->getCol())) {
                $actualDelete[] = $item;
            }
        }
        $this->setDelete($actualDelete);
        return $this;
    }

    public function UPDATE(...$updated)
    {
        $actualUpdate = $this->getUpdate();
        foreach ($updated as $item) {
            if (in_array($item, $this->getCol())) {
                $actualUpdate[] = $item;
            }
        }
        $this->setUpdate($actualUpdate);
        return $this;
    }

    public function INSERT(...$inserted)
    {
        $actualInsert = $this->getSelect();
        foreach ($inserted as $item) {
            if (in_array($item, $this->getCol())) {
                $actualInsert[] = $item;
            }
        }
        $this->setInsert($actualInsert);
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
