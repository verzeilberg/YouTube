<?php

namespace YouTube\Service;

use Zend\ServiceManager\ServiceLocatorInterface;

/*
 * Entities
 */
use YouTube\Entity\YouTube;
use YouTube\Entity\YouTubeImages;

class youTubeService implements youTubeServiceInterface {

    /**
     * @var \Blog\Service\PostServiceInterface
     */
    protected $em;

    public function __construct($em) {
        $this->em = $em;
    }

    /**
     *
     * Create new YouTube object
     *
     * @return      object
     *
     */
    public function createYouTube() {
        return new YouTube();
    }

    /**
     *
     * Create new YouTubeImage object
     *
     * @return      object
     *
     */
    public function createYouTubeImage() {
        return new YouTubeImages();
    }

    /**
     *
     * Get YouTube video object based on id
     *
     * @param       id  $id The id to fetch the youtube video from the database
     * @return      object
     *
     */
    public function getYouTubeVideoById($id) {
        $youTubeVideo = $this->em->getRepository(YouTube::class)
                ->findOneBy(['id' => $id], []);

        return $youTubeVideo;
    }

    /**
     * return youtube ID
     * @return string
     */
    public function youtubeID($sUrl) {
        $res = explode("v=", $sUrl);
        if (isset($res[1])) {
            $res1 = explode('&', $res[1]);
            if (isset($res1[1])) {
                $res[1] = $res1[0];
            }
            $res1 = explode('#', $res[1]);
            if (isset($res1[1])) {
                $res[1] = $res1[0];
            }
        }
        return substr($res[1], 0, 12);
        return false;
    }

    public function removeYouTubeVideo($YouTubeVideo = NULL) {
        if ($YouTubeVideo !== NULL) {
            $this->em->remove($YouTubeVideo);
            $this->em->flush();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns all data for a you tube video
     * @param string $sUrl
     */
    public function getYouTubeDataFromVideo($sUrl) {
        $succes = true;
        //First get you tube video ID an put in a variable
        $sYouTubeId = $this->youtubeID($sUrl);
        //Get you tube data from uploaded you tube link, snippet, contentDetails, 
        $dataContentDetails = json_decode(@file_get_contents('https://www.googleapis.com/youtube/v3/videos?part=id%2C+contentDetails&id=' . $sYouTubeId . '&key=AIzaSyCpkho1lm-jC0YFZNu3G-Af2UH_GYWE5j4'));
        $dataSnippet = json_decode(@file_get_contents('https://www.googleapis.com/youtube/v3/videos?part=id%2C+snippet&id=' . $sYouTubeId . '&key=AIzaSyCpkho1lm-jC0YFZNu3G-Af2UH_GYWE5j4'));

        //Video details
        $title = $dataSnippet->items[0]->snippet->title;
        $description = $dataSnippet->items[0]->snippet->description;
        $duration = $dataContentDetails->items[0]->contentDetails->duration;
        //Create YouTube object
        $youTubeVideo = $this->createYouTube();
        $youTubeVideo->setYouTubeId($sYouTubeId);
        $youTubeVideo->setDuration($duration);
        $youTubeVideo->setTitle($title);
        $youTubeVideo->setDescription($description);


        $this->storeYouTube($youTubeVideo);
        if (count($dataSnippet->items[0]->snippet->thumbnails) > 0) {
            foreach ($dataSnippet->items[0]->snippet->thumbnails AS $index => $thumbnail) {
                $url = $thumbnail->url;
                $width = $thumbnail->width;
                $height = $thumbnail->height;

                $youTubeVideoImages = $this->createYouTubeImage();
                $youTubeVideoImages->setType($index);
                $youTubeVideoImages->setUrl($url);
                $youTubeVideoImages->setWidth($width);
                $youTubeVideoImages->setHeight($height);
                $youTubeVideoImages->setYouTube($youTubeVideo);
                $this->storeYouTubeImage($youTubeVideoImages);
                $youTubeVideo->addYouTubeImages($youTubeVideoImages);
                
            }

           $this->storeYouTube($youTubeVideo); 

            return $youTubeVideo;
        } else {
            return $succes;
        }
    }

    /**
     *
     * Save youtube video to database
     *
     * @param       youTubeVideo object
     * @return      void
     *
     */
    public function storeYouTube($youTubeVideo) {
        $this->em->persist($youTubeVideo);
        $this->em->flush();
    }
    
        /**
     *
     * Save youtube image to database
     *
     * @param       youTubeVideoImage object
     * @return      void
     *
     */
    public function storeYouTubeImage($youTubeVideoImage) {
        $this->em->persist($youTubeVideoImage);
    }

}
