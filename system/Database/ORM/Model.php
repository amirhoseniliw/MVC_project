<?php
namespace System\Database\ORM;
use System\Database\Traits\HasAtrbutes ;
use System\Database\Traits\HasCRUD ;
use System\Database\Traits\HasMethodCaller ;
use System\Database\Traits\HasQueryBuilder ;
use System\Database\Traits\HasRelation ;

abstract class Model {
    use HasAtrbutes , HasCRUD , HasMethodCaller , HasQueryBuilder , HasRelation  ;
}
