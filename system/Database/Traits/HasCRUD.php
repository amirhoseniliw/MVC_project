<?php

namespace System\Database\Traits;

use System\Database\DBConnection\DBConnection;

trait HasCRUD{

        
    protected function deleteMethod($id = null){

        $object = $this;
        $this->resetQuery();
        if($id){
            $object = $this->findMethod($id);
            $this->resetQuery();
        }
        $object->setSql("DELETE FROM ". $object->getTablename());
        $object->setWhere("AND", $this->getAttributeName($this->primaryKey)." = ? ");
        $object->addValue($object->primaryKey, $object->{$object->primaryKey});
        return $object->executeQuery();
    }
    protected function allMethod(){

        $this->setSql("SELECT * FROM ".$this->getTableName());
        $statement = $this->executeQuery();
        $data = $statement->fetchAll();
        if ($data){

           $this->arrayToObjects($data);
           return $this->collection;
        }
        return [];
    }
    protected function findMethod($id){

        $this->setSql("SELECT * FROM ".$this->getTableName());
        $this->setWhere("AND", $this->getAttributeName($this->primaryKey)." = ? ");
        $this->addValue($this->primaryKey, $id);
        $statement = $this->executeQuery();
        $data = $statement->fetch();
        $this->setAllowedMethods(['update', 'delete', 'save']);
        if ($data)
        return $this->arrayToAttributes($data);
        return null;
    }
    protected function saveMethod(){

        $fillString = $this->fill();

        if(!isset($this->{$this->primaryKey})){
            $this->setSql("INSERT INTO ".$this->getTableName()." SET $fillString, ".$this->getAttributeName($this->createdAt)."=Now()");
        }
        else{
            $this->setSql("UPDATE ".$this->getTableName()." SET $fillString, ".$this->getAttributeName($this->updatedAt)."=Now()");
            $this->setWhere("AND", $this->getAttributeName($this->primaryKey)." = ?");
            $this->addValue($this->primaryKey, $this->{$this->primaryKey});
        }
        $this->executeQuery();
        $this->resetQuery();

        if(!isset($this->{$this->primaryKey})){

            $object = $this->findMethod(DBConnection::newInsertId());
            $defaultVars = get_class_vars(get_called_class());
            $allVars = get_object_vars($object);
            $differentVars = array_diff(array_keys($allVars), array_keys($defaultVars));
            foreach($differentVars as $attribute){
                $this->inCastsAttributes($attribute) == true ? $this->registerAttribute($this, $attribute, $this->castEncodeValue($attribute, $object->$attribute)) : $this->registerAttribute($this, $attribute, $object->$attribute);

            }

        }
        $this->resetQuery();
        $this->setAllowedMethods(['update', 'delete', 'find']);
        return $this;
    }


    protected function fill(){

        $fillArray = array();
        foreach ($this->fillable as $attribute){
            if(isset($this->$attribute)){
                array_push($fillArray, $this->getAttributeName($attribute) . " = ?");
                $this->inCastsAttributes($attribute) == true ? $this->addValue($attribute, $this->castEncodeValue($attribute, $this->$attribute)) : $this->addValue($attribute, $this->$attribute);
            }
        }
        $fillString = implode(', ', $fillArray);
        return $fillString;
    }


    

}