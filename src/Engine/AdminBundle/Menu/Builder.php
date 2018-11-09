<?php
/**
 * Created by PhpStorm.
 * User: abirillo
 * Date: 07.09.16
 * Time: 19:42
 */

namespace Engine\AdminBundle\Menu;

use Engine\AdminBundle\Classes\AdminInterface;
use Engine\AdminBundle\Classes\MenuItem;
use Engine\UserBundle\Entity\User;
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
	use ContainerAwareTrait;

	/**
	 * @param FactoryInterface $factory
	 * @param array            $options
	 *
	 * @return \Knp\Menu\ItemInterface
	 */
	public function sidebarMenu(FactoryInterface $factory, array $options)
	{

		$menu = $factory->createItem('root', ['childrenAttributes' => ['class' => 'sidebar-menu']]);

		$menu->addChild('Home', array('route' => 'admin_home'))
		     ->setAttribute('data-icon', 'fa fa-dashboard');

		$bundles = $this->container->getParameter('kernel.bundles');
		foreach ($bundles as $bundle){
			if(method_exists($bundle,"getAdmin")){
				$objBundle = new $bundle();
                /** @var $bundleAdmin AdminInterface */
				$bundleAdmin = $objBundle->getAdmin();

				$menuRootItem = $bundleAdmin->getMenu();
                /** @var User $user */
                $user = $this->container->get('security.token_storage')->getToken()->getUser();
                $allow = true;
				if(isset($menuRootItem->permission)){
                    $allow = $user->hasPermission($menuRootItem->permission);
                }
				if($allow){
                    $menu->addChild($menuRootItem->name,
                        ['route'=>$menuRootItem->route, 'routeParameters'=>$menuRootItem->routeParameters]
                    )
                        ->setAttribute('data-icon', $menuRootItem->icon)
                        ->setChildrenAttribute('class','treeview-menu')
                        ->setAttribute('data-expander', 1);

                    $menuItems = $bundleAdmin->getMenus();
                    foreach ( $menuItems as $menuItem ) {
                        /** @var $menuItem MenuItem */
                        $allow = true;
                        if(isset($menuItem->permission)){
                            $allow = $user->hasPermission($menuItem->permission);
                        }
                        if($allow){
                            $menu[$menuRootItem->name]->addChild($menuItem->name,
                                ['route'=>$menuItem->route, 'routeParameters'=>$menuItem->routeParameters]
                            )
                                ->setAttribute('data-icon', $menuItem->icon);
                        }
                    }
                }
			}
		}


		return $menu;
	}
}