<?php

namespace Engine\UserBundle;

use Engine\UserBundle\Admin\Admin;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class UserBundle extends Bundle
{
	public static function getAdmin(){
		return new Admin();
	}

	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
