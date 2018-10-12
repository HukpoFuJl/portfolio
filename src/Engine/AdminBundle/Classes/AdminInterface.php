<?php
/**
 * Created by PhpStorm.
 * User: abirillo
 * Date: 12.09.16
 * Time: 16:16
 */
namespace Engine\AdminBundle\Classes;

interface AdminInterface{

	/**
	 * @return MenuItem
	 */
	public function getMenu();

	/**
	 * @return []MenuItem
	 */
	public function getMenus();

}