<?php

namespace MBO;

abstract class MBOBuilder extends DBManager
{
    private $select = [];

    private $delete = false;

    private $update = [];

    private $insert = [];

    private $where = [];

    private $orderBy = [];

    private $query;

    private $count = [];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return $this
     */
    public function clear() : MBOBuilder {
        $this
            ->setSelect([])
            ->setDelete(false)
            ->setUpdate([])
            ->setInsert([])
            ->setWhere([])
            ->setCount([]);
        return $this;
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
            $stmt .= "$selects, ";
        }
        if (!empty($this->getCount())) {
            foreach ($this->getCount() as $count) {
                $stmt .= "COUNT($count), ";
            }
        }
        $stm = rtrim($stmt, ', ');
        $stm .= ' FROM ' . $this->getTableName();
        if (!empty($this->getWhere())) {
            $where = $this->buildWhere();
            $stm = $stm . ' ' . $where[0];
        }
        if (!empty($this->getOrderBy())) {
            $orderBy = $this->buildOrderBy();
            $stm .= ' ' . $orderBy;
        }
        $req = $this->getPdo();
        $request = $req->prepare($stm);
        return !empty($this->getWhere()) ? $this->bindWhere($request, $where) : $request;
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
            $sqlValue .= ":in$key, ";
        }
        $sqlKey = rtrim($sqlKey, ', ');
        $sqlKey .= ') VALUES ';
        $sqlValue = rtrim($sqlValue, ', ');
        $sqlValue .= ')';
        $req = $this->getPdo();
        $request = $req->prepare($stmt . $sqlKey . $sqlValue);
        foreach ($cols as $key => &$value) {
            $request->bindParam(":in$key", $value);
        }
        return $request;
    }

    public function buildUpdate() : \PDOStatement
    {
        $stmt = 'UPDATE `' . $this->getTableName() . '` SET ';
        foreach ($this->getUpdate() as $index => $update) {
            $stmt .= $update[0] . ' = :up' . $index . ' , ';
        }
        $stmt = rtrim($stmt, ' , ');
        $where = $this->buildWhere();
        $req = $this->getPdo();
        $request = $req->prepare("$stmt $where[0]");
        foreach ($this->getUpdate() as $index => $update) {
            $request->bindParam(":up$index", $update[1]);
        }
        return $this->bindWhere($request, $where);
    }

    public function buildDelete() : \PDOStatement
    {
        $stmt = 'DELETE FROM `' . $this->getTableName() . '`';
        $where = $this->buildWhere();
        $req = $this->getPdo();
        $request = $req->prepare("$stmt $where[0]");
        return $this->bindWhere($request, $where);
    }

    private function buildWhere() : array
    {
        $stmt = 'WHERE ';
        $value = [];
        foreach ($this->getWhere() as $index => $condition) {
            $stmt .= $condition[0] . ' ' . $condition[1] . ' :wh' . $index . ' AND ';
            $value[$index] = $condition[2];
        }
        $stmt = rtrim($stmt, 'AND ');
        return [$stmt, $value];
    }

    private function buildOrderBy() : string
    {
        $stmt = 'ORDER BY ';
        foreach ($this->getOrderBy() as $orderBy) {
            $stmt.= $orderBy[0] . ' ' . $orderBy[1] . ', ';
        }
        $stmt = rtrim($stmt, ', ');
        return $stmt;
    }

    private function bindWhere($pdo, $where): \PDOStatement
    {
        foreach ($where[1] as $key => $value) {
            $pdo->bindParam(":wh$key", $value);
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
            $result = $query->fetchAll($fetchStyle);
            $this->clear();
            return $result;
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

    public function DELETE(bool $bool): MBOBuilder
    {
        return $this->setDelete($bool);
    }

    public function UPDATE(...$updated): MBOBuilder
    {
        $actualUpdate = $this->getUpdate();
        foreach ($updated as $item) {
            if ($this->isCol($item[0]) || $item === '*') {
                $actualUpdate[] = $item;
            }
        }
        return $this->setUpdate($actualUpdate);
    }

    public function INSERT(...$inserted): MBOBuilder
    {
        $actualInsert = $this->getInsert();
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

    public function ORDERBY(...$orderBy): MBOBuilder
    {
        $actualOrderBy = $this->getOrderBy();
        foreach ($orderBy as $order) {
            if ($this->isCol($order[0])) {
                if (isset($order[1])) {
                    $actualOrderBy[] = $order;
                } else {
                    $actualOrderBy[] = [$order[0], 'ASC'];
                }
            }
        }
        return $this->setOrderBy($actualOrderBy);
    }

    public function COUNT($colName = "*", $distinct = false): MBOBuilder
    {
        $actualCount = $this->getCount();
        if ($this->isCol($colName) ||$colName === '*') {
            if ($distinct) {
                $actualCount[] = "DISTINCT $colName";
            } else {
                $actualCount[] = $colName;
            }
        }
        return $this->setCount($actualCount);
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

    public function getDelete(): bool
    {
        return $this->delete;
    }

    public function setDelete(bool $delete): MBOBuilder
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

    public function getOrderBy(): array
    {
        return $this->orderBy;
    }

    public function setOrderBy(array $orderBy): MBOBuilder
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    public function getCount(): array
    {
        return $this->count;
    }

    public function setCount(array $count): MBOBuilder
    {
        $this->count = $count;
        return $this;
    }
}
