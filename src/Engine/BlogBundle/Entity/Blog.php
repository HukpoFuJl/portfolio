<?php

namespace Engine\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="blog")
 */
class Blog
{    
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(name="title", type="string", length=255)
	 */
	protected $title;

	/**
	 * @ORM\Column(name="content", type="text")
	 */
	protected $content;

	/**
     * @ORM\Column(name="author_id", type="integer")
     */
	protected $authorId;

	/**
	 * @ORM\Column(name="image", type="string", nullable=true)
	 */
	protected $image;
	
	/**
     * @Assert\File()
	 */
	protected $image_input;

	/**
	 * @ORM\Column(name="date", type="datetime")
	 */
	protected $date;

    /**
     * @ORM\Column(name="updateDate", type="datetime")
     */
    protected $updateDate;

    /**
     * @ORM\ManyToMany(targetEntity="Engine\BlogBundle\Entity\BlogCategories")
     * @ORM\JoinTable(name="blog_to_categories",
     *      joinColumns={@ORM\JoinColumn(name="blog_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     * )
     * @var  $categories \Doctrine\Common\Collections\Collection
     */
    protected $categories;

    /**
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    protected $active;


    public function __construct()
    {
        $this->date = new \DateTime();
        $this->categories = new ArrayCollection();
        $this->active = true;
    }

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
     * @return mixed
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * @param mixed $authorId
     */
    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Blog
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Blog
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Blog
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Blog
     */
    public function setImageInput($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImageInput()
    {
        return $this->image;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Blog
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return \DateTime
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * @param \DateTime $updateDate
     * @return Blog
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;
        return $this;
    }

    /**
     * Get categories
     *
     * @return array
     */
    public function getCategories()
    {
        $categories = [];
        foreach ($this->categories->toArray() as $key => $category) {
            /* @var $category BlogCategories */
            $categories[$key]["id"] = $category->getId();
            $categories[$key]["name"] = $category->getCategoryName();
        }
        return $categories;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     * @return Blog
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }


}
