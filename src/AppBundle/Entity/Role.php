<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Role
 *
 * @ORM\Table(name="role")
 * @ORM\Entity
 * @ExclusionPolicy("all")
 */
class Role {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\SequenceGenerator(sequenceName="role_id_seq", allocationSize=1, initialValue=1)
     * @expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=256, nullable=false)
     * @expose
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="tag", type="string", length=256, nullable=false)
     * @expose
     */
    private $tag;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=512, nullable=true)
     * @expose
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     * @expose
     */
    private $status;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Users", mappedBy="role")
     */
    private $user;
 
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return $this->name;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Role
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Set tag
     *
     * @param string $tag
     *
     * @return string
     */
    public function setTag($tag) {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string
     */
    public function getTag() {
        return $this->tag;
    }
    

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Role
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Role
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus() {
        return $this->status;
    }


    /**
     * Add user
     *
     * @param \AdminBundle\Entity\Users $user
     *
     * @return Role
     */
    public function addUser(\AppBundle\Entity\Users $user) {
        $this->user[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \AdminBundle\Entity\Users $user
     */
    public function removeUser(\AppBundle\Entity\Users $user) {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUser() {
        return $this->user;
    }
}
