<?php

class SimpleUser extends Eloquent {

    protected $table = 'users_simple';
    public $timestamps = false;
    protected $softDelete = false;

}
