<?php
/**
 * Created by PhpStorm.
 * User: abirillo
 * Date: 01.11.16
 * Time: 13:12
 */
namespace Engine\UserBundle\Entity;

use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_groups")
 */
class Group extends BaseGroup
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * One Group has Many Group.
	 * @ORM\OneToMany(targetEntity="Group", mappedBy="parent")
	 *
	 * @var  $children \Doctrine\Common\Collections\Collection
	 */
	private $children;

	/**
	 * Many Group have One Group.
	 * @ORM\ManyToOne(targetEntity="Group", inversedBy="children")
	 * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
	 */
	private $parent;

	/**
	 * @ORM\ManyToMany(targetEntity="Engine\UserBundle\Entity\Permission")
	 * @ORM\JoinTable(name="user_groups_to_user_permissions",
	 *      joinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="perm_id", referencedColumnName="id")}
	 * )
	 *
	 * @var  $permissions \Doctrine\Common\Collections\Collection
	 */
	protected $permissions;


	/**
	 * Add permission
	 *
	 * @param \Engine\UserBundle\Entity\Permission $permission
	 *
	 * @return Group
	 */
	public function addPermission(\Engine\UserBundle\Entity\Permission $permission)
	{
		$this->permissions->add($permission);

		return $this;
	}

	/**
	 * Remove permission
	 *
	 * @param \Engine\UserBundle\Entity\Permission $permission
	 */
	public function removePermission(\Engine\UserBundle\Entity\Permission $permission)
	{
		$this->permissions->removeElement($permission);
	}

	/**
	 * Get permissions
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getPermissions()
	{
		return $this->permissions;
	}

	/**
	 * Add child
	 *
	 * @param \Engine\UserBundle\Entity\Group $child
	 *
	 * @return Group
	 */
	public function addChild(\Engine\UserBundle\Entity\Group $child)
	{
		$this->children->add($child);

		return $this;
	}

	/**
	 * Remove child
	 *
	 * @param \Engine\UserBundle\Entity\Group $child
	 */
	public function removeChild(\Engine\UserBundle\Entity\Group $child)
	{
		$this->children->removeElement($child);
	}

	/**
	 * Get children
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getChildren()
	{
		return $this->children;
	}

	/**
	 * Set parent
	 *
	 * @param \Engine\UserBundle\Entity\Group $parent
	 *
	 * @return Group
	 */
	public function setParent(\Engine\UserBundle\Entity\Group $parent = null)
	{
		$this->parent = $parent;

		return $this;
	}

	/**
	 * Get parent
	 *
	 * @return \Engine\UserBundle\Entity\Group
	 */
	public function getParent()
	{
		return $this->parent;
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
		if(is_array($perm)&&!empty($perm)){
			foreach ($perm as $n=>$p){
				if($this->findPermission($p)){
					if($type===0) return true;
					unset($perm[$n]);
				}
			}
			if(empty($perm)){
				return true;
			}
			else{
				return false;
			}
		}
		elseif (is_string($perm)){
			if($this->findPermission($perm)){
				return true;
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
	}

	/**
	 * @param $name string
	 * @return boolean
	 */
	private function findPermission($name){
		foreach ($this->permissions->toArray() as $perm){
			/* @var $perm Permission */
			if ($perm->getName() == $name){
				return true;
			}
		}
		return false;
	}


}
