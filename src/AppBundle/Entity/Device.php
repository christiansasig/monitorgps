<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Device
 *
 * @ORM\Table(name="device", indexes={@ORM\Index(name="polygon_id_idx", columns={"polygon_id"}), @ORM\Index(name="user_id_idx", columns={"user_id"})})
 * @ORM\Entity
 * @UniqueEntity(fields="ip", message="field.ip.not_blank")
 * @ExclusionPolicy("all")
 */
class Device
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\SequenceGenerator(sequenceName="device_id_seq", allocationSize=1, initialValue=1)
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
     * @ORM\Column(name="ip", type="string", length=64, nullable=false)
     * @expose
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="mac", type="string", length=64, nullable=true)
     * @expose
     */
    private $mac;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=512, nullable=true)
     * @expose
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;

    /**
     * @var \Polygon
     *
     * @ORM\ManyToOne(targetEntity="Polygon")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="polygon_id", referencedColumnName="id")
     * })
     * @expose
     */
    private $polygon;

    
    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="devices")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     * @expose
     */
    private $user;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->createdAt = new \DateTime();
    }

    public function __toString() {
        return $this->name;
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
     * Set name
     *
     * @param string $name
     *
     * @return Device
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
     * Set ip
     *
     * @param string $ip
     *
     * @return Device
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set mac
     *
     * @param string $mac
     *
     * @return Device
     */
    public function setMac($mac)
    {
        $this->mac = $mac;

        return $this;
    }

    /**
     * Get mac
     *
     * @return string
     */
    public function getMac()
    {
        return $this->mac;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Device
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

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Device
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Device
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set polygon
     *
     * @param \AppBundle\Entity\Polygon $polygon
     *
     * @return Device
     */
    public function setPolygon(\AppBundle\Entity\Polygon $polygon = null)
    {
        $this->polygon = $polygon;

        return $this;
    }

    /**
     * Get polygon
     *
     * @return \AppBundle\Entity\Polygon
     */
    public function getPolygon()
    {
        return $this->polygon;
    }
    
    /**
     * Set user
     *
     * @param \AppBundle\Entity\Users $user
     *
     * @return Audit
     */
    public function setUser(\AppBundle\Entity\Users $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AdminBundle\Entity\Users
     */
    public function getUser() {
        return $this->user;
    }
}
