<?php

namespace YouTube\Service;

use Zend\ServiceManager\ServiceLocatorInterface;

/*
 * Entities
 */
use YouTube\Entity\YouTube;
use YouTube\Entity\YouTubeImages;

class youTubeService implements youTubeServiceInterface {

    protected $em;
    protected $config;

    public function __construct($em, $config) {
        $this->em = $em;
        $this->config = $config;
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

            $id = substr($res[1], 0, 12);
        } else {
            $id = array_pop(explode('/', $sUrl));
        }
        return $id;
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
        //First get you tube video ID an put in a variable
        $sYouTubeId = $this->youtubeID($sUrl);
        $key = $this->config['youtube_credentials']['consumer_key'];

        //Get you tube data from uploaded you tube link, snippet, contentDetails,
        $dataContentDetails = json_decode(@file_get_contents('https://www.googleapis.com/youtube/v3/videos?part=id%2C+contentDetails&id=' . $sYouTubeId . '&key=' . $key));
        $dataSnippet = json_decode(@file_get_contents('https://www.googleapis.com/youtube/v3/videos?part=snippet&id=' . $sYouTubeId . '&key=' . $key), true);

        //Video details
        $title = $dataSnippet["items"][0]["snippet"]["title"];
        $description = $dataSnippet["items"][0]["snippet"]["description"];
        $duration = $dataContentDetails->items[0]->contentDetails->duration;
        //Create YouTube object
        $youTubeVideo = $this->createYouTube();
        $youTubeVideo->setYouTubeId($sYouTubeId);
        $youTubeVideo->setDuration($duration);
        $youTubeVideo->setTitle($title);
        $youTubeVideo->setDescription($description);


        $thumbnails = $dataSnippet["items"][0]["snippet"]["thumbnails"];
        if (count($thumbnails) > 0) {
            foreach ($thumbnails AS $index => $thumbnail) {
                $url = $thumbnail['url'];
                $width = $thumbnail['width'];
                $height = $thumbnail['height'];

                $youTubeVideoImages = $this->createYouTubeImage();
                $youTubeVideoImages->setType($index);
                $youTubeVideoImages->setUrl($url);
                $youTubeVideoImages->setWidth($width);
                $youTubeVideoImages->setHeight($height);
                $youTubeVideoImages->setYouTube($youTubeVideo);
                $this->storeYouTubeImage($youTubeVideoImages);
                $youTubeVideo->addYouTubeImages($youTubeVideoImages);
                
            }
        }

        $this->storeYouTube($youTubeVideo);

        return $youTubeVideo;

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
