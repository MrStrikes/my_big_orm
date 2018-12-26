<?php

namespace MBO;

class MBOBuilder extends DBManager
{
    private $select = [];

    private $delete = [];

    private $update = [];

    private $insert = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function buildQuery()
    {
        $query = '';
        if (!empty($this->getSelect())) {
            $query = $this->buildSelect();
        } else if (!empty($this->getInsert())) {
            $query = $this->buildInsert();
        } else if (!empty($this->getUpdate())) {
            $query = $this->buildUpdate();
        } else if (!empty($this->getDelete())) {
            $query = $this->buildDelete();
        }
        return $query;
    }

    public function buildSelect() : \PDOStatement
    {
        $stmt = "SELECT ";
        foreach ($this->getSelect() as $selects) {
            $stmt .= $selects . ", ";
        }
        $stm = rtrim($stmt, ', ');
        $stm .= ' FROM ' . $this->getTableName();
        $req = $this->getPdo();
        return $req->prepare($stm);
    }

    public function buildInsert() : \PDOStatement
    {
        $cols = array_flip($this->getCol());
        foreach ($cols as $key => $value) {
            $cols[$key] = null;
        }
        foreach ($this->getInsert() as $key => $value) {
            $cols[$value[0]] = $value[1];
        }
        $stmt = 'INSERT INTO ' . $this->getTableName() . ' ';
        $sqlKey = '(';
        $sqlValue = '(';
        foreach ($cols as $key => $value) {
            $sqlKey .= "`$key`, ";
            $sqlValue .= ":$key, ";
        }
        $sqlKey = rtrim($sqlKey, ', ');
        $sqlKey .= ') VALUES ';
        $sqlValue = rtrim($sqlValue, ', ');
        $sqlValue .= ')';
        $req = $this->getPdo();
        $a = $req->prepare($stmt . $sqlKey . $sqlValue);
        foreach ($cols as $key => &$value) {
            $a->bindParam(":$key", $value);
        }
        return $a;
    }

    public function buildUpdate() : \PDOStatement
    {

    }

    public function buildDelete() : \PDOStatement
    {

    }
    private $where = [];

    public function SELECT(...$selected): MBOBuilder
    {
        $actualSelect = $this->getSelect();
        foreach ($selected as $item) {
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

    public function INSERT(...$inserted): MBOBuilder
    {
        $actualInsert = $this->getSelect();
        foreach ($inserted as $item) {
            if (in_array($item[0], $this->getCol())) {
                $actualInsert[] = [$item[0], $item[1]];
            }
        }
        return $this->setInsert($actualInsert);

    }

    public function WHERE(...$conditions): MBOBuilder
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

    public function setSelect($select): MBOBuilder
    {
        $this->select = $select;
        return $this;
    }

    public function getDelete()
    {
        return $this->delete;
    }

    public function setDelete($delete): MBOBuilder
    {
        $this->delete = $delete;
        return $this;
    }

    public function getUpdate()
    {
        return $this->update;
    }

    public function setUpdate($update): MBOBuilder
    {
        $this->update = $update;
        return $this;
    }

    public function getInsert()
    {
        return $this->insert;
    }

    public function setInsert($insert): MBOBuilder
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
