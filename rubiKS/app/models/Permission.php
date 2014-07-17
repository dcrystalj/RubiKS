<?php

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
	public $timestamps = false;
	protected $softDelete = false;
	protected $fillable = array('name', 'display_name');
}