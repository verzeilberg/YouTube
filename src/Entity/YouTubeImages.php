<?php

namespace YouTube\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * Club
 *
 * @ORM\Entity
 * @ORM\Table(name="youtubeimages")
 */
class YouTubeImages {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11, name="id");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    protected $type;

    /**
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    protected $url;

    /**
     * @ORM\Column(name="width", type="integer", length=11, nullable=true)
     */
    protected $width;

    /**
     * @ORM\Column(name="height", type="integer", length=11, nullable=true)
     */
    protected $height;

    /**
     * Many YouTubeImages have One YouTube.
     * @ORM\ManyToOne(targetEntity="YouTube", inversedBy="youTubeImages")
     * @ORM\JoinColumn(name="youtube_id", referencedColumnName="id")
     */
    private $youTube;

    function getId() {
        return $this->id;
    }

    function getType() {
        return $this->type;
    }

    function getUrl() {
        return $this->url;
    }

    function getWidth() {
        return $this->width;
    }

    function getHeight() {
        return $this->height;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setType($type) {
        $this->type = $type;
    }

    function setUrl($url) {
        $this->url = $url;
    }

    function setWidth($width) {
        $this->width = $width;
    }

    function setHeight($height) {
        $this->height = $height;
    }

    function getYouTube() {
        return $this->youTube;
    }

    function setYouTube($youTube) {
        $this->youTube = $youTube;
    }

}
