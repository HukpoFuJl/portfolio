<?php
/**
 * Created by PhpStorm.
 * User: abirillo
 * Date: 12.09.16
 * Time: 16:15
 */

namespace Engine\UserBundle\Admin;


use Engine\AdminBundle\Classes\AdminInterface;
use Engine\AdminBundle\Classes\MenuItem;

class Admin implements AdminInterface{

	public function getMenu() {
		return new MenuItem([
			'name'=> 'Users',
			'icon'=>'fa fa-users',
			'route'=>'user_admin_list'
		]);
	}

	public function getMenus() {
		return [
			new MenuItem([
				'name'=> 'Users',
				'icon'=>'fa fa-list',
				'route'=>'user_admin_list'
			]),
			new MenuItem([
				'name'=> 'Groups',
				'icon'=>'fa fa-list',
				'route'=>'user_admin_group_list'
			]),
			new MenuItem([
				'name'=> 'Permissions',
				'icon'=>'fa fa-list',
				'route'=>'user_admin_perms'
			]),
			new MenuItem([
				'name'=> 'Perm Test Page',
				'icon'=>'fa fa-check',
				'route'=>'user_admin_perms_chk'
			])
		];
	}
}