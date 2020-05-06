<?php

namespace YouTube\Entity;

use Doctrine\ORM\Mapping as ORM;
use Laminas\Form\Annotation;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Club
 *
 * @ORM\Entity
 * @ORM\Table(name="youtubevideos")
 */
class YouTube {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11, name="id");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="you_tube_id", type="string", length=255, nullable=true)
     */
    protected $youTubeId;

    /**
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(name="duration", type="string", length=255, nullable=true)
     */
    protected $duration;

    /**
     * @ORM\Column(name="controls", type="integer", length=1, nullable=true, options={"default"=0})
     */
    protected $controls = 0;

    /**
     * @ORM\Column(name="auto_play", type="integer", length=1, nullable=true, options={"default"=0})
     */
    protected $autoPlay = 0;

    /**
     * @ORM\Column(name="related_videos", type="integer", length=1, nullable=true, options={"default"=0})
     */
    protected $relatedVideos = 0;

    /**
     * @ORM\Column(name="show_info", type="integer", length=1, nullable=true, options={"default"=0})
     */
    protected $showInfo = 0;

    /**
     * One YouTube video has Many YouTube Images.
     * @ORM\OneToMany(targetEntity="YouTubeImages", mappedBy="youTube", orphanRemoval=true)
     */
    private $youTubeImages;

    public function __construct() {
        $this->youTubeImages = new ArrayCollection();
    }

    function getId() {
        return $this->id;
    }

    function getYouTubeId() {
        return $this->youTubeId;
    }

    function getTitle() {
        return $this->title;
    }

    function getDuration() {
        return $this->duration;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setYouTubeId($youTubeId) {
        $this->youTubeId = $youTubeId;
    }

    function setTitle($title) {
        $this->title = $title;
    }

    function setDuration($duration) {
        $this->duration = $duration;
    }

    function getDescription() {
        return $this->description;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    public function addYouTubeImages($youTubeImages) {
        if (!$this->youTubeImages->contains($youTubeImages)) {
            $this->youTubeImages->add($youTubeImages);
        }
        return $this;
    }

    public function removeYouTubeImages($youTubeImages) {
        if ($this->youTubeImages->contains($youTubeImages)) {
            $this->youTubeImages->removeElement($youTubeImages);
        }
        return $this;
    }

    function getYouTubeImages() {
        return $this->youTubeImages;
    }

    function setYouTubeImages($youTubeImages) {
        $this->youTubeImages = $youTubeImages;
        return $this;
    }

    function getYouTubeImageByType($type = 'default') {
        foreach ($this->youTubeImages AS $youTubeImage) {
            if ($youTubeImage->getType() == $type) {
                return $youTubeImage;
                break;
            }
        }
    }

}
