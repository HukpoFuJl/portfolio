<?php
/**
 * Created by PhpStorm.
 * User: abirillo
 * Date: 01.12.16
 * Time: 15:43
 */


namespace Engine\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_permissions")
 */
class Permission
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(name="name", type="string", length=100)
	 */
	protected $name;

	/**
	 * @ORM\Column(name="desc", type="string", length=255)
	 */
	protected $description;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Permission
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Permission
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


}
