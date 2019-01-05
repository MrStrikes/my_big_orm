<?php

namespace MBO;

interface EntityInterface
{

    /**
     * return col witch are in DB
     * @return mixed
     */
    public function getCol();

    /**
     * return DB table name
     * @return mixed
     */
    public function getTableName();

    /**
     * return entity id
     * @return mixed
     */
    public function getId();

    /**
     * set entity id
     * @return mixed
     */
    public function setId();

}
