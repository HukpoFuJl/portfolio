<?php
/**
 * Created by PhpStorm.
 * User: abirillo
 * Date: 05.09.16
 * Time: 19:01
 */

namespace Engine\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToMany(targetEntity="Engine\UserBundle\Entity\Group")
	 * @ORM\JoinTable(name="users_to_user_groups",
	 *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
	 * )
	 * @var  $groups \Doctrine\Common\Collections\Collection
	 */
	protected $groups;


	public function __construct()
	{
		parent::__construct();
		// your own logic
	}


    /**
     * Get permissions
     *
     * @return array
     */
    public function getPermissions()
    {
	    $permissions = [];
	    foreach ($this->getGroups()->toArray() as $group){
		    /* @var $group Group */
		    foreach ($group->getPermissions()->toArray() as $perm){
			    /* @var $perm Permission */
			    $permissions[$perm->getName()] = $perm;
		    }
	    }
	    return $permissions;
    }


	/**
	 * Check permissions
	 *
	 * @param $perm string|array
	 * @param $type int If $perm is array it specify the search method.
	 *                  0 - "At least one".
	 *                  1 - "All of permissions".
	 *
	 * @return boolean
	 */
    public function hasPermission( $perm, $type=1 ) {

	    foreach ($this->getGroups()->toArray() as $group){
		    /* @var $group Group */
		    if($group->hasPermission($perm, $type)){
			    return true;
		    }
	    }
	    return false;
    }
}
