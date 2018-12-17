<?php

namespace MBO;

abstract class MBOBuilder
{
    private $select = [];

    private $delete = [];

    private $update = [];

    private $insert = [];

    private $where = [];

    public function SELECT(...$selected): MBOBuilder
    {
        $actualSelect = $this->getSelect();
        foreach ($selected as $item) {
            if (in_array($item, $this->getCol())) {
                $actualSelect[] = $item;
            }
        }
        return $this;
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

    public function INSERT(...$inserted) : MBOBuilder
    {
        $actualInsert = $this->getSelect();
        foreach ($inserted as $item) {
            if (in_array($item[0], $this->getCol())) {
                $actualInsert[] = [$item[0], $item[1]];
            }
        }
        return $this->setInsert($actualInsert);

    }

    public function WHERE(...$conditions) : MBOBuilder
    {
        $actualWhere = $this->getWhere();
        foreach ($conditions as $condition) {
            if (in_array($condition[0], $this->getCol())) {
                $actualWhere[] = [$condition[0], $condition[1]];
            }
        }
        return $this->setWhere($actualWhere);
    }

    public function getSelect()
    {
        return $this->select;
    }

    public function setSelect($select) : MBOBuilder
    {
        $this->select = $select;
        return $this;
    }

    public function getDelete()
    {
        return $this->delete;
    }

    public function setDelete($delete) : MBOBuilder
    {
        $this->delete = $delete;
        return $this;
    }

    public function getUpdate()
    {
        return $this->update;
    }

    public function setUpdate($update) : MBOBuilder
    {
        $this->update = $update;
        return $this;
    }

    public function getInsert()
    {
        return $this->insert;
    }

    public function setInsert($insert) : MBOBuilder
    {
        $this->insert = $insert;
        return $this;
    }

    public function getWhere(): array
    {
        return $this->where;
    }

    public function setWhere(array $where): MBOBuilder
    {
        $this->where = $where;
        return $this;
    }
}
