<?php

namespace System\Database\Traits;

trait HasSoftDelete{


    protected function deleteMethod($id = null){
        $object = $this;
        if($id){
            $this->resetQuery();
            $object = $this->findMethod($id);
        }
        if ($object) {
            $object->resetQuery();
            $object->setSql("UPDATE ".$object->getTableName(). " SET ".$this->getAttributeName($this->deletedAt)." = NOW() ");
            $object->setWhere("AND", $this->getAttributeName($object->primaryKey)." = ?");
            $object->addValue($object->primaryKey, $object->{$object->primaryKey});
            return $object->executeQuery();
        }
    }

    protected function allMethod(){
        $this->setSql("SELECT ".$this->getTableName().".* FROM ".$this->getTableName());
        $this->setWhere("AND", $this->getAttributeName($this->deletedAt)." IS NULL ");
        $statement = $this->executeQuery();
        $data = $statement->fetchAll();
        if ($data){
           $this->arrayToObjects($data);
           return $this->collection;
        }
        return [];
    }

    protected function findMethod($id){
        $this->resetQuery();
        $this->setSql("SELECT ".$this->getTableName().".* FROM ".$this->getTableName());
        $this->setWhere("AND", $this->getAttributeName($this->primaryKey)." = ? ");
        $this->addValue($this->primaryKey, $id);
        $this->setWhere("AND", $this->getAttributeName($this->deletedAt)." IS NULL ");
        $statement = $this->executeQuery();
        $data = $statement->fetch();
        $this->setAllowedMethods(['update', 'delete', 'save']);
        if ($data)
            return $this->arrayToAttributes($data);
        return null;
    }





}