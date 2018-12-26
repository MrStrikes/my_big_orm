<?php

namespace MBO;

class MBOBuilder extends DBManager
{
    private $select = [];

    private $delete = [];

    private $update = [];

    private $insert = [];

    private $where = [];

    private $query;

    public function __construct()
    {
        parent::__construct();
    }

    public function buildQuery() : MBOBuilder
    {
        if (!empty($this->getSelect())) {
            $query = $this->buildSelect();
        } else if (!empty($this->getInsert())) {
            $query = $this->buildInsert();
        } else if (!empty($this->getUpdate())) {
            $query = $this->buildUpdate();
        } else if (!empty($this->getDelete())) {
            $query = $this->buildDelete();
        } else {
            return $this;
            //TODO LOG
        }
        $this->setQuery($query);
        return $this;
    }

    public function buildSelect() : \PDOStatement
    {
        $stmt = "SELECT ";
        foreach ($this->getSelect() as $selects) {
            $stmt .= $selects . ", ";
        }
        $stm = rtrim($stmt, ', ');
        $stm .= ' FROM ' . $this->getTableName();
        $where = $this->buildWhere();
        $stm = $stm . ' ' . $where[0];
        $req = $this->getPdo();
        $request = $req->prepare($stm);
        $request = $this->bindWhere($request, $where);
        return $request;
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
        $stmt = 'INSERT INTO `' . $this->getTableName() . '` ';
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
        $request = $req->prepare($stmt . $sqlKey . $sqlValue);
        foreach ($cols as $key => &$value) {
            $request->bindParam(":$key", $value);
        }
        return $request;
    }

    public function buildUpdate() : \PDOStatement
    {
        $stmt = 'UPDATE `' . $this->getTableName() . '` SET ';

    }

    public function buildDelete() : \PDOStatement
    {

    }

    private function buildWhere() : array
    {
        $stmt = 'WHERE ';
        $value = [];
        foreach ($this->getWhere() as $index => $condition) {
            $stmt .= $condition[0] . ' ' . $condition[1] . ' :' . $index . ' AND ';
            $value[$index] = $condition[2];
        }
        $stmt = rtrim($stmt, 'AND ');
        return [$stmt, $value];
    }

    private function bindWhere($pdo, $where): \PDOStatement
    {
        foreach ($where[1] as $key => $value) {
            $pdo->bindParam(":$key", $value);
        }
        return $pdo;
    }

    public function execute($fetchStyle = 2)
    {
        $query = $this->getQuery();
        $bool = $query->execute();
        if (!$bool) {
            /////TODO log
        } else {
            return $query->fetchAll($fetchStyle);
        }

    }

    public function SELECT(...$selected): MBOBuilder
    {
        $actualSelect = $this->getSelect();
        foreach ($selected as $item) {
            if ($this->isCol($item) || $item === '*') {
                $actualSelect[] = $item;
            }
        }
        return $this->setSelect($actualSelect);
    }

    public function DELETE(...$deleted): MBOBuilder
    {
        $actualDelete = $this->getDelete();
        foreach ($deleted as $item) {
            if ($this->isCol($item)) {
                $actualDelete[] = $item;
            }
        }
        return $this->setDelete($actualDelete);
    }

    public function UPDATE(...$updated): MBOBuilder
    {
        $actualUpdate = $this->getUpdate();
        foreach ($updated as $item) {
            if ($this->isCol($item) || $item === '*') {
                $actualUpdate[] = $item;
            }
        }
        return $this->setUpdate($actualUpdate);
    }

    public function INSERT(...$inserted): MBOBuilder
    {
        $actualInsert = $this->getSelect();
        foreach ($inserted as $item) {
            if ($this->isCol($item[0])) {
                $actualInsert[] = [$item[0], $item[1]];
            }
        }
        return $this->setInsert($actualInsert);

    }

    public function WHERE(...$conditions): MBOBuilder
    {
        $actualWhere = $this->getWhere();
        foreach ($conditions as $condition) {
            if ($this->isCol($condition[0])) {
                $actualWhere[] = [$condition[0], $condition[1], $condition[2]];
            }
        }
        return $this->setWhere($actualWhere);
    }

    private function isCol($item): bool
    {
        return in_array($item, $this->getCol());
    }

    public function getSelect(): array
    {
        return $this->select;
    }

    public function setSelect($select): MBOBuilder
    {
        $this->select = $select;
        return $this;
    }

    public function getDelete(): array
    {
        return $this->delete;
    }

    public function setDelete($delete): MBOBuilder
    {
        $this->delete = $delete;
        return $this;
    }

    public function getUpdate(): array
    {
        return $this->update;
    }

    public function setUpdate($update): MBOBuilder
    {
        $this->update = $update;
        return $this;
    }

    public function getInsert(): array
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

    public function getQuery(): \PDOStatement
    {
        return $this->query;
    }

    public function setQuery(\PDOStatement $query): MBOBuilder
    {
        $this->query = $query;
        return $this;
    }
}
