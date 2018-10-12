<?php
/**
 * Created by PhpStorm.
 * User: abirillo
 * Date: 12.09.16
 * Time: 16:29
 */

namespace Engine\AdminBundle\Classes;

class MenuItem{
	public $name;
	public $icon;
	public $route;
	public $routeParameters;

	public function __construct($params) {
		if(isset($params['name'])){
			$this->name = $params['name'];
		}
		if(isset($params['icon'])){
			$this->icon = $params['icon'];
		}
		if(isset($params['route'])){
			$this->route = $params['route'];
		}
		if(isset($params['routeParameters'])){
			$this->routeParameters = $params['routeParameters'];
		}
	}
}