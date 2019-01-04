<?php

namespace MBO;

abstract class MBOEntity extends MBOBuilder implements EntityInterface
{
    public function __construct()
    {
        parent::__construct();
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

}