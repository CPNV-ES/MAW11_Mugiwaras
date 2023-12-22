<?php

namespace Mugiwaras\Framework\Core;

class QueryClause{
    public readonly array|string $columns;
    public readonly string $operator;
    public readonly string $value;
    public readonly string $type;

    public function __construct($columns, $operator, $value = "", $type = ""){
        $this->columns = $this->addSlashesToArray($columns);
        $this->operator = addSlashes($operator);
        $this->value = addSlashes($value);
        $this->type = $type;
    }

    public function __toString(){
        if ($this->value == ""){
            return $this->arrayToString($this->columns) . " " . $this->operator;
        }
        return $this->arrayToString($this->columns) . " " . $this->operator . ' "' . $this->value . '" ';
    }

    private function addSlashesToArray($array){
        if (!is_array($array)){
            return addSlashes($array);
        }
        $newArray = [];
        foreach ($array as $item){
            array_push($newArray, addSlashes($item));
        }
        return $newArray;
    }

    private function arrayToString($array){
        if (!is_array($array)){
            return $array;
        }
        $string = "";
        foreach ($array as $item){
            $string .= $item . ", ";
        }
        return substr($string, 0, -2);
    }

    public function getType(){
        return $this->type;
    }
}