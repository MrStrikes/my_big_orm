<?php

namespace MBO;

abstract class MBOEntity extends MBOBuilder implements EntityInterface
{
    public function __construct()
    {
        parent::__construct();
    }

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

    public function buildEntity(array $data): self
    {
        $entity = new $this;
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

    public function getById($id, $fetchStyle = 2): self
    {
        $this
            ->clear()
            ->SELECT('*')
            ->WHERE(['id', '=', $id])
            ->buildQuery();
        return $this->buildEntity($this->execute($fetchStyle)[0]);
    }

    public function getAll(): array
    {
        $result = $this->clear()->SELECT('*')->buildQuery()->execute();
        $allEntities = [];
        foreach ($result as $data) {
            $allEntities[] = $this->buildEntity($data);
        }
        return $allEntities;
    }

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

    public function save(): self
    {
        $this->clear();
        if (empty($this->getId())) {
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
}