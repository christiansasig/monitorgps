<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * alert
 *
 * @ORM\Table(name="alert", indexes={@ORM\Index(name="device_id_idx", columns={"device_id"})})
 * @ORM\Entity
 */
class Alert {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\SequenceGenerator(sequenceName="alert_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="tag", type="string", length=256, nullable=false)
     */
    private $tag;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="name_polygon", type="string", length=255, nullable=true)
     */
    private $namePolygon;

    /**
     * @var string
     *
     * @ORM\Column(name="path_polygon", type="text", nullable=true)
     */
    private $pathPolygon;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=512, nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \Device
     *
     * @ORM\ManyToOne(targetEntity="Device")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="device_id", referencedColumnName="id")
     * })
     */
    private $device;

    /**
     * Constructor
     */
    public function __construct() {
        $this->createdAt = new \DateTime();
    }

    public function getPathShape() {
        $array = json_decode($this->pathPolygon, true);
        $array_final = '[';
        foreach ($array as $value) {
            $array_final .= "\n" . '[' . $value['lat'] . ',' . $value['lng'] . ']' . ',';
        }
        $array_final = substr($array_final, 0, -1);
        $array_final.= "\n" . ']';
        return $array_final;
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
     * Set tag
     *
     * @param string $tag
     *
     * @return Alert
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
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Alert
     */
    public function setLatitude($latitude) {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude() {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Alert
     */
    public function setLongitude($longitude) {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude() {
        return $this->longitude;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Alert
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Alert
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Set device
     *
     * @param \AppBundle\Entity\Device $device
     *
     * @return Alert
     */
    public function setDevice(\AppBundle\Entity\Device $device = null) {
        $this->device = $device;

        return $this;
    }

    /**
     * Get device
     *
     * @return \AppBundle\Entity\Device
     */
    public function getDevice() {
        return $this->device;
    }

    /**
     * Set namePolygon
     *
     * @param string $namePolygon
     *
     * @return Polygon
     */
    public function setNamePolygon($namePolygon) {
        $this->namePolygon = $namePolygon;

        return $this;
    }

    /**
     * Get namePolygon
     *
     * @return string
     */
    public function getNamePolygon() {
        return $this->namePolygon;
    }

    /**
     * Set pathPolygon
     *
     * @param string $pathPolygon
     *
     * @return Polygon
     */
    public function setPathPolygon($pathPolygon) {
        $this->pathPolygon = $pathPolygon;

        return $this;
    }

    /**
     * Get pathPolygon
     *
     * @return string
     */
    public function getPathPolygon() {
        return $this->pathPolygon;
    }

}
