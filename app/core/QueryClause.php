<?php

namespace Mugiwaras\Framework\Core;

class QueryClause{
    public readonly array|string $columns;
    public readonly string $operator;
    public readonly array|string $values;
    public readonly string $type;

    public function __construct($columns, $operator, $values = "", $type = ""){
        $this->columns = $this->addSlashesToArray($columns);
        $this->operator = addSlashes($operator);
        $this->values = $this->addSlashesToArray($values);
        $this->type = $type;
    }

    public function __toString(){
        if ($this->values == ""){
            return $this->arrayToString($this->columns) . " " . $this->operator;
        }
        return $this->arrayToString($this->columns) . " " . $this->operator . ' "' . $this->arrayToString($this->values) . '" ';
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

    private function arrayToString($array, $quote=false){
        if (!is_array($array)){
            return $array;
        }
        $string = "";
        foreach ($array as $item){
            if ($quote){
                $string .= '"' . $item . '", ';
                continue;
            }
            $string .= $item . ", ";
        }
        return substr($string, 0, -2);
    }

    public function getType(){
        return $this->type;
    }

    public function getColumns(){
        return $this->arrayToString($this->columns);
    }

    public function getOperator(){
        return $this->operator;
    }

    public function getValues(){
        return $this->arrayToString($this->values, true);
    }
}