<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * polygon
 *
 * @ORM\Table(name="polygon")
 * @ORM\Entity
 * @ExclusionPolicy("all")
 */
class Polygon {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\SequenceGenerator(sequenceName="polygon_id_seq", allocationSize=1, initialValue=1)
     * @expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false, unique=true)
     * @expose
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="text", nullable=true)
     * @expose
     */
    private $path;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;

    /**
     * Constructor
     */
    public function __construct() {
        $this->createdAt = new \DateTime();
    }
    
    
    public function __toString() {
        return $this->name;
    }


    public function getPathShape() {
        $array = json_decode($this->path, true);
        $array_final = '[';
        foreach ($array as $value) {
            $array_final .= "\n" . '[' . $value['lat'] . ',' . $value['lng'] . ']' . ',';
        }
        $array_final = substr($array_final, 0, -1);
        $array_final.= "\n" .']';
        return $array_final;
    }

    public function getPathPoints() {
        $array = json_decode($this->path, true);
        $array_final = array();
        foreach ($array as $value) {
            $array_final[] =  $value['lat'] . ' ' . $value['lng'];
        }
        $array_final[] = $array[0]['lat'] . ' ' . $array[0]['lng'];
        return $array_final;
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
     * @return Polygon
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
     * Set path
     *
     * @param string $path
     *
     * @return Polygon
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Polygon
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
     * @return Polygon
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
}
