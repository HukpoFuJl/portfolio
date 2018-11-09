<?php
/**
 * Created by PhpStorm.
 * User: abirillo
 * Date: 06.09.16
 * Time: 12:17
 */

namespace Engine\AdminBundle\Controller;

use Engine\UserBundle\Annotations\Permissions;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin")
 *
 * @Permissions("admin_dashboard")
 */
class DefaultController extends Controller
{
	/**
	 * @Route("", name="admin_home")
	 */
	public function indexAction(Request $request)
	{
		$haveAdminBundles = [];
		$bundles = $this->getParameter('kernel.bundles');
		foreach ($bundles as $bundle){
			if(method_exists($bundle,"getAdmin")){
				$haveAdminBundles[] = $bundle;
			}
		}


		return $this->render('AdminBundle:default:index.html.twig', [
			'bundles' => $haveAdminBundles,
			'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
		]);
	}


	/**
	 * @Route("/about", name="admin_about")
	 */
	public function aboutAction(Request $request)
	{

		return $this->render('AdminBundle:default:index.html.twig', [
			'bundles' => null,
			'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
		]);
	}

	/**
	 * @Route("/list", name="admin_custom_list")
	 */
	public function customListAction(){

		return $this->render('AdminBundle:default:index.html.twig', [
			'bundles' => null
		]);
	}
}
