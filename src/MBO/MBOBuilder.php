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

    /**
     * MBOBuilder constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Reset builder param
     * @return MBOBuilder
     */
    public function clear(): self {
        $this
            ->setSelect([])
            ->setDelete(false)
            ->setUpdate([])
            ->setInsert([])
            ->setWhere([])
            ->setCount([]);
        return $this;
    }

    /**
     * Call the good builder function
     * @return MBOBuilder
     * @throws \Exception
     */
    public function buildQuery(): self
    {
        if (!empty($this->getSelect()) || !empty($this->getCount())) {
            $query = $this->buildSelect();
        } else if (!empty($this->getInsert())) {
            $query = $this->buildInsert();
        } else if (!empty($this->getUpdate())) {
            $query = $this->buildUpdate();
        } else if (!empty($this->getDelete())) {
            $query = $this->buildDelete();
        } else {
            $date = new \DateTime();
            $log['date'] = $date->format('Y-m-d H:i:s');
            $log["requestType"] = 'No request type found';
            error_log(json_encode($log) . "\n", 3, $GLOBALS['MBO']['log']['error.log']);
            return $this;
        }
        $this->setQuery($query);
        return $this;
    }

    /**
     * Build select PDO, with where, count and order by
     * @return \PDOStatement
     */
    public function buildSelect(): \PDOStatement
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

    /**
     * Build insert PDO
     * @return \PDOStatement
     */
    public function buildInsert(): \PDOStatement
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

    /**
     * Build update PDO with where
     * @return \PDOStatement
     */
    public function buildUpdate(): \PDOStatement
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

    /**
     * Build Delete PDO with where
     * @return \PDOStatement
     */
    public function buildDelete(): \PDOStatement
    {
        $stmt = 'DELETE FROM `' . $this->getTableName() . '`';
        $where = $this->buildWhere();
        $req = $this->getPdo();
        $request = $req->prepare("$stmt $where[0]");
        return $this->bindWhere($request, $where);
    }

    /**
     * Build Where
     * @return array[query, value to bind]
     */
    private function buildWhere(): array
    {
        $stmt = 'WHERE ';
        $value = [];
        $query = '';
        $opeList = [];
        foreach ($this->getWhere() as $index => $condition) {
            if (!in_array($condition[3], $opeList)) {
                $opeList[] = $condition[3];
            }
            $query .= " $condition[3] " . $condition[0] . ' ' . $condition[1] . ' :wh' . $index;
            $value[$index] = $condition[2];
        }
        foreach ($opeList as $ope) {
            $query = trim($query, " $ope ");
        }
        $stmt .= $query;
        return [$stmt, $value];
    }

    /**
     * build order by
     * @return string
     */
    private function buildOrderBy(): string
    {
        $stmt = 'ORDER BY ';
        foreach ($this->getOrderBy() as $orderBy) {
            $stmt.= $orderBy[0] . ' ' . $orderBy[1] . ', ';
        }
        $stmt = rtrim($stmt, ', ');
        return $stmt;
    }

    /**
     * Bind param for where
     * @param $pdo
     * @param $where (from buildWhere())
     * @return \PDOStatement
     */
    private function bindWhere($pdo, $where): \PDOStatement
    {
        foreach ($where[1] as $key => &$value) {
            $pdo->bindParam(":wh$key", $value);
        }
        return $pdo;
    }

    /**
     * exucute query in $this->getQuery()
     * @param int $fetchStyle
     * @param bool $clear
     * @return array|bool
     * @throws \Exception
     */
    public function execute($fetchStyle = 2, $clear = true)
    {
        $query = $this->getQuery();
        $start_time = microtime(true);
        $bool = $query->execute();
        $end_time = microtime(true);

        $date = new \DateTime();
        $log['date'] = $date->format('Y-m-d H:i:s');
        $log['query'] = $this->getQuery()->queryString;
        $log['param']['where'] = $this->getWhere();
        if (!empty($this->getSelect()) || !empty($this->getCount())) {
            $log['requestType'] = 'select';
            $log['param']['select'] = $this->getSelect();
            $log['param']['count'] = $this->getCount();
        } else if (!empty($this->getInsert())) {
            $log['requestType'] = 'insert';
            $log['param']['insert'] = $this->getInsert();
        } else if (!empty($this->getUpdate())) {
            $log['requestType'] = 'update';
            $log['param']['update'] = $this->getUpdate();
        } else if (!empty($this->getDelete())) {
            $log['requestType'] = 'delete';
            $log['param']['delete'] = $this->getDelete();
        } else {
            $log['requestType'] = "No request type found";
        }
        if (!$bool) {
            $log['error'] = $query->errorInfo();
            $log['error'][2] = utf8_encode($log['error'][2]);
            error_log(json_encode($log) . "\n", 3, $GLOBALS['MBO']['log']['error.log']);
            return false;
        } else {
            $log['executionTime'] = $end_time - $start_time;
            error_log(json_encode($log) . "\n", 3, $GLOBALS['MBO']['log']['request.log']);
            $result = $query->fetchAll($fetchStyle);

            if ($clear) {
                $this->clear();
            }
            return $result;
        }

    }

    /**
     * Add param select to $this->getSelect()
     * @param mixed ...$selected
     * @return MBOBuilder
     */
    public function SELECT(...$selected): self
    {
        $actualSelect = $this->getSelect();
        foreach ($selected as $item) {
            if ($this->isCol($item) || $item === '*') {
                $actualSelect[] = $item;
            }
        }
        return $this->setSelect($actualSelect);
    }

    /**
     * @param bool $bool
     * @return MBOBuilder
     */
    public function DELETE(bool $bool): self
    {
        return $this->setDelete($bool);
    }

    /**
     * Add param update to $this->getUpdate()
     * @param mixed ...$updated
     * @return MBOBuilder
     */
    public function UPDATE(...$updated): self
    {
        $actualUpdate = $this->getUpdate();
        foreach ($updated as $item) {
            if ($this->isCol($item[0]) || $item === '*') {
                $actualUpdate[] = $item;
            }
        }
        return $this->setUpdate($actualUpdate);
    }

    /**
     * add param insert to $this->getInsert()
     * @param mixed ...$inserted
     * @return MBOBuilder
     */
    public function INSERT(...$inserted): self
    {
        $actualInsert = $this->getInsert();
        foreach ($inserted as $item) {
            if ($this->isCol($item[0])) {
                $actualInsert[] = [$item[0], $item[1]];
            }
        }
        return $this->setInsert($actualInsert);

    }

    /**
     * add param where to $this->getWhere()
     * @param mixed ...$conditions
     * @return MBOBuilder
     */
    public function WHERE(...$conditions): self
    {
        $actualWhere = $this->getWhere();
        foreach ($conditions as $condition) {
            if ($this->isCol($condition[0])) {
                $ope = isset($condition[3]) ? $condition[3] : 'AND';
                $actualWhere[] = [$condition[0], $condition[1], $condition[2], $ope];
            }
        }
        return $this->setWhere($actualWhere);
    }

    /**
     * add param order by to $this->getOrderBy()
     * @param mixed ...$orderBy
     * @return MBOBuilder
     */
    public function ORDERBY(...$orderBy): self
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

    /**
     * add param count to $this->getCount()
     * @param string $colName
     * @param bool $distinct
     * @return MBOBuilder
     */
    public function COUNT($colName = "*", $distinct = false): self
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

    /**
     * Check if $item is correct column with the array define by user in $this->getCol()
     * @param $item
     * @return bool
     */
    private function isCol($item): bool
    {
        return in_array($item, $this->getCol());
    }

    /**
     * @return array
     */
    public function getSelect(): array
    {
        return $this->select;
    }

    /**
     * @param $select
     * @return MBOBuilder
     */
    public function setSelect($select): self
    {
        $this->select = $select;
        return $this;
    }

    /**
     * @return bool
     */
    public function getDelete(): bool
    {
        return $this->delete;
    }

    /**
     * @param bool $delete
     * @return MBOBuilder
     */
    public function setDelete(bool $delete): self
    {
        $this->delete = $delete;
        return $this;
    }

    /**
     * @return array
     */
    public function getUpdate(): array
    {
        return $this->update;
    }

    /**
     * @param $update
     * @return MBOBuilder
     */
    public function setUpdate($update): self
    {
        $this->update = $update;
        return $this;
    }

    /**
     * @return array
     */
    public function getInsert(): array
    {
        return $this->insert;
    }

    /**
     * @param $insert
     * @return MBOBuilder
     */
    public function setInsert($insert): self
    {
        $this->insert = $insert;
        return $this;
    }

    /**
     * @return array
     */
    public function getWhere(): array
    {
        return $this->where;
    }

    /**
     * @param array $where
     * @return MBOBuilder
     */
    public function setWhere(array $where): self
    {
        $this->where = $where;
        return $this;
    }

    /**
     * @return \PDOStatement
     */
    public function getQuery(): \PDOStatement
    {
        return $this->query;
    }

    /**
     * @param \PDOStatement $query
     * @return MBOBuilder
     */
    public function setQuery(\PDOStatement $query): self
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @return array
     */
    public function getOrderBy(): array
    {
        return $this->orderBy;
    }

    /**
     * @param array $orderBy
     * @return MBOBuilder
     */
    public function setOrderBy(array $orderBy): self
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * @return array
     */
    public function getCount(): array
    {
        return $this->count;
    }


    /**
     * @param array $count
     * @return MBOBuilder
     */
    public function setCount(array $count): self
    {
        $this->count = $count;
        return $this;
    }
}
