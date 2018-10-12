<?php
/**
 * Created by PhpStorm.
 * User: abirillo
 * Date: 12.09.16
 * Time: 17:37
 */

namespace Engine\UserBundle\Admin\Controller;

use Doctrine\ORM\EntityManager;
use Engine\UserBundle\Entity\Group;
use Engine\UserBundle\Entity\Permission;
use Engine\UserBundle\Entity\User;
use Engine\UserBundle\Form\Type\Admin\GroupEditType;
use Engine\UserBundle\Form\Type\Admin\UserEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/admin/users")
 */
class UserController extends Controller{

	/**
	 * @Route("", name="user_admin_list")
	 */
	public function listAction(Request $request)
	{


		$userManager = $this->get('fos_user.user_manager');
		$users = $userManager->findUsers();

		return $this->render('@User/admin/userlist.html.twig', ['users' => $users]);
	}

	/**
	 * @Route("/edit/{id}", name="user_admin_edit")
	 */
	public function editAction(Request $request, $id){

		return $this->userEditForm($request, $id);
	}

	/**
	 * @Route("/create", name="user_admin_create")
	 */
	public function createAction(Request $request){

		return $this->userEditForm($request);
	}


	/**
	 * @Route("/group", name="user_admin_group_list")
	 */
	public function groupListAction(Request $request)
	{

		$groupManager = $this->get('fos_user.group_manager');
		$groups = $groupManager->findGroups();

		return $this->render('@User/admin/grouplist.html.twig', ['groups' => $groups]);
	}

	/**
	 * @Route("/group/edit/{id}", name="user_admin_group_edit")
	 */
	public function groupEditAction(Request $request, $id){

		return $this->groupEditForm($request, $id);
	}

	/**
	 * @Route("/group/create", name="user_admin_group_create")
	 */
	public function groupCreateAction(Request $request){

		return $this->groupEditForm($request);
	}


	/**
	 * @Route("/permissions", name="user_admin_perms")
	 */
	public function permListAction(Request $request)
	{


		$perms = $this->get('doctrine')->getRepository('UserBundle:Permission')->findAll();
		/* @var $perms Permission[] */

		return $this->render('@User/admin/permlist.html.twig', ['perms' => $perms]);
	}

	/**
	 * @Route("/perm-test", name="user_admin_perms_chk")
	 */
	public function permChkAction(Request $request)
	{


		$user = $this->getUser();
		/* @var $user User */
		$data = [
			'admin_dashboard' => $user->hasPermission('admin_dashboard'),
			'some_permission' => $user->hasPermission('some_permission'),
			'["admin_dashboard"]' => $user->hasPermission(['admin_dashboard']),
			'["admin_dashboard","some_permission"]' => $user->hasPermission(['admin_dashboard','some_permission']),
			'["some_permission","admin_dashboard"],0' => $user->hasPermission(['some_permission','admin_dashboard'],0),
			'YOUR PERMISSIONS' => $user->getPermissions()
		];

		return $this->render('@User/admin/permtest.html.twig', ['data' => $data]);
	}



	private function userEditForm(Request $request, $id = null) {

		$userManager = $this->get('fos_user.user_manager');
		$session = $request->getSession();
		$user = null;

		if ($id) {
			$user = $userManager->findUserBy(['id'=>$id]);

			if (!$user) {
				/* @var $session Session */
				$session->getFlashBag()->add('error', 'User with id ' . $id . ' not found!');
				return $this->redirectToRoute("user_admin_list");
			}
			$originalPassword = $user->getPassword();    # encoded password
		} else {
			$user = new User();
		}

		$form = $this->createForm(UserEditType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {


			try {
				$message = $message = "User \"" . $user->getUsername() . "\" was successfully ";
				$message .= $id?"updated!":"created!";
				/* @var $session Session */
				$session->getFlashBag()->add("success", $message);
				$userManager->updateUser($user);

				return $this->redirectToRoute("user_admin_list");
			}
			catch(Exception $exception){
				$session = $request->getSession();
				/* @var $session Session */
				$session->getFlashBag()->add('error', 'Something went wrong: '.$exception->getMessage());
			}
		}

		return $this->render('@User/admin/useredit.html.twig', ['form' => $form->createView()]);
	}


	private function groupEditForm(Request $request, $id = null) {

		$groupManager = $this->get('fos_user.group_manager');
		$session = $request->getSession();
		$group = null;

		if ($id) {
			$group = $groupManager->findGroupBy(['id'=>$id]);

			if (!$group) {
				/* @var $session Session */
				$session->getFlashBag()->add('error', 'Group with id ' . $id . ' not found!');
				return $this->redirectToRoute("user_admin_group_list");
			}
		} else {
			$group = new Group("New Group");
		}

		$form = $this->createForm(GroupEditType::class, $group);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {


			try {
				$message = $message = "Group \"" . $group->getName() . "\" was successfully ";
				$message .= $id?"updated!":"created!";
				/* @var $session Session */
				$session->getFlashBag()->add("success", $message);
				$groupManager->updateGroup($group);

				return $this->redirectToRoute("user_admin_group_list");
			}
			catch(Exception $exception){
				$session = $request->getSession();
				/* @var $session Session */
				$session->getFlashBag()->add('error', 'Something went wrong: '.$exception->getMessage());
			}
		}

		return $this->render('@User/admin/groupedit.html.twig', ['form' => $form->createView()]);
	}
}