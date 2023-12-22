<?php

namespace Mugiwaras\Framework\Core;

class WhereClause{
    public readonly string $column;
    public readonly string $operator;
    public readonly string $value;
    public readonly string $type;

    public function __construct($column, $operator, $value, $type){
        $this->column = addSlashes($column);
        $this->operator = addSlashes($operator);
        $this->value = addSlashes($value);
        $this->type = $type;
    }

    public function __toString(){
        return $this->column . " " . $this->operator . ' "' . $this->value . '" ';
    }

    public function getType(){
        return $this->type;
    }
}