<?php
/**
 * Created by PhpStorm.
 * User: abirillo
 * Date: 12.09.16
 * Time: 16:15
 */

namespace Engine\BlogBundle\Admin;


use Engine\AdminBundle\Classes\AdminInterface;
use Engine\AdminBundle\Classes\MenuItem;

class Admin implements AdminInterface{

	public function getMenu() {
		return new MenuItem([
			'name'=> 'Blog',
			'icon'=>'fa fa-newspaper-o',
			'route'=>'blog_admin_index',
            'permission'=>'admin_blog'
		]);
	}

	public function getMenus() {
        return [
            new MenuItem([
                'name'=> 'Posts',
                'icon'=>'fa fa-newspaper-o',
                'route'=>'blog_admin_list'
            ]),
            new MenuItem([
                'name'=> 'Categories',
                'icon'=>'fa fa-list',
                'route'=>'blog_category_admin_list'
            ])
        ];
	}
}