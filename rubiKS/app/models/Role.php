<?php

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
	public $timestamps = false;
	protected $softDelete = false;
	protected $fillable = array('name');

	/**
	 *
	 */
	public function addPermissions($permissions)
	{
		$permissionIds = array();
		foreach ($permissions as $permission) $permissionIds[] = $permission->id;
		$this->perms()->sync($permissionIds);
	}
}