<?php

namespace Gouh\BlogApi\App\DAO;

abstract class AbstractDAO implements InterfaceDAO
{
    /**
     * @param $filter
     * @return string
     */
    protected function getWhereSql($filter): string
    {
        $whereSql = [];
        foreach ($filter as $key => $value){
            $whereSql[] = "$key = :$key";
        }
        return implode(" AND ", $whereSql);
    }
}
