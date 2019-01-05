<?php

namespace MBO;

abstract class MBOEntity extends MBOBuilder implements EntityInterface
{
    /**
     * MBOEntity constructor.
     * @param null $id
     */
    public function __construct($id = null)
    {
        parent::__construct();
        if ($id !== null) {
            $this->getById($id, false);
            $this->clear();
        }
    }

    /**
     * get data from self entity
     * @return array
     */
    public function getData()
    {
        $data = [];
        foreach ($this->getCol() as $col) {
            $func = 'get' . ucfirst($col);
            $pos = strpos($func, '_');
            while (!($pos === false)) {
                $func[$pos + 1] = ucfirst($func[$pos + 1]);
                $func = substr($func, 0, $pos) . substr($func, $pos + 1);
                $pos = strpos($func, '_');
            }
            $data[$col] = $this->$func();
        }
        return $data;
    }

    /**
     * create or complete entity with $data
     * @param array $data
     * @param bool $newEntity
     * @return MBOEntity
     */
    public function buildEntity(array $data, $newEntity = true): self
    {
        $entity = $newEntity ? new $this : $this;
        foreach ($entity->getCol() as $col) {
            $func = 'set' . ucfirst($col);
            $pos = strpos($func, '_');
            while (!($pos === false)) {
                $func[$pos + 1] = ucfirst($func[$pos + 1]);
                $func = substr($func, 0, $pos) . substr($func, $pos + 1);
                $pos = strpos($func, '_');
            }
            $entity->$func($data[$col]);
        }
        return $entity;
    }

    /**
     * Get entity by id
     * @param $id
     * @param bool $newEntity
     * @return MBOEntity
     * @throws \Exception
     */
    public function getById($id, $newEntity = true): self
    {
        $this
            ->clear()
            ->SELECT('*')
            ->WHERE(['id', '=', $id])
            ->buildQuery();
        return $this->buildEntity($this->execute(2, false)[0], $newEntity);
    }

    /**
     * get all entity
     * @return array
     * @throws \Exception
     */
    public function getAll(): array
    {
        $result = $this->clear()->SELECT('*')->buildQuery()->execute();
        $allEntities = [];
        foreach ($result as $data) {
            $allEntities[] = $this->buildEntity($data);
        }
        return $allEntities;
    }

    /**
     * get entity with critera (SELECT param)
     * @param mixed ...$criteria
     * @return array
     * @throws \Exception
     */
    public function getByCriteria(...$criteria)
    {
        $this->clear();
        $allEntities = [];
        $this->SELECT('*');
        foreach ($criteria as $where) {
            $this->WHERE($where);
        }
        $result = $this->buildQuery()->execute();
        foreach ($result as $data) {
            $allEntities[] = $this->buildEntity($data);
        }
        return $allEntities;
    }

    /**
     * create or update entity if id found in DB
     * @return MBOEntity
     * @throws \Exception
     */
    public function save(): self
    {
        $this->clear();
        if (!$this->exist(['id', '=', $this->getId()])) {
            $data = $this->getData();
            foreach ($data as $key => $value) {
                $this->INSERT([$key, $value]);
            }
            $this->buildQuery()
                ->getQuery()
                ->execute();
        } else {
            $data = $this->getData();
            foreach ($data as $key => $value) {
                $this->UPDATE([$key, $value]);
            }
            $this->WHERE(['id', '=', $data['id']])
                ->buildQuery()
                ->execute();
        }
        return $this;
    }

    /**
     * Delete entity in DB with $this->getId()
     * @return bool
     * @throws \Exception
     */
    public function deleteEntity()
    {
        $this->clear();
        if (!empty($this->getId())) {
            $this->DELETE(true)
                ->WHERE(['id', '=', $this->getId()])
                ->buildQuery()
                ->execute();
        } else {
            return false;
        }
    }

    /**
     * check if actual entity exist in db or can take $where param to check this param
     * @param mixed ...$where
     * @return bool
     * @throws \Exception
     */
    public function exist(...$where)
    {
        if (empty($where)) {
            $where[] = ['id', '=', $this->getId()];
        }
        $this->clear()->SELECT('*');
        foreach ($where as $condition) {
            $this->WHERE($condition);
        }
        $result = $this->buildQuery()->execute();
        return !empty($result);
    }

    /**
     * get the number of entity in db
     * @return mixed
     * @throws \Exception
     */
    public function countEntity()
    {
        $result = $this->clear()->COUNT('*')->buildQuery()->execute();
        return $result[0]['COUNT(*)'];
    }
}