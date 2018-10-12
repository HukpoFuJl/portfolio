<?php
/**
 * Created by PhpStorm.
 * User: abirillo
 * Date: 12.09.16
 * Time: 17:50
 */

namespace Engine\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/user")
 */
class DefaultController extends Controller{
	/**
	 * @Route("", name="user_home")
	 */
	public function indexAction( Request $request ) {
		return $this->render( 'UserBundle:default:test.html.twig' );
	}


	/**
	 * @Route("/login", name="user_login")
	 * @Route("/login", name="fos_user_security_login")
	 */
	public function loginAction( Request $request ) {
		return $this->forward('FOSUserBundle:Security:login',['request'=>$request]);
	}

	/**
	 * @Route("/login_check", name="user_check")
	 * @Route("/login_check", name="fos_user_security_check")
	 */
	public function loginCheckAction( Request $request ) {
		return $this->forward('FOSUserBundle:Security:check',['request'=>$request]);
	}
	/**
	 * @Route("/logout", name="user_logout")
	 * @Route("/logout", name="fos_user_security_logout")
	 */
	public function logoutAction( Request $request ) {
		return $this->forward('FOSUserBundle:Security:logout',['request'=>$request]);
	}

}