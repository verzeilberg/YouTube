<?php

namespace YouTube\Service;

interface youTubeServiceInterface {

    /**
     *
     * Create new YouTube object
     *
     * @return      object
     *
     */
    public function createYouTube();

    /**
     *
     * Get YouTube video object based on id
     *
     * @param       id  $id The id to fetch the youtube video from the database
     * @return      object
     *
     */
    public function getYouTubeVideoById($id);

    /**
     *
     * Get YouTube id from url
     *
     * @param       sUrl  $sUrl the urlto get the id from
     * @return      string
     *
     */
    public function youtubeID($sUrl);

    /**
     *
     * Remove you tube video from database
     *
     * @param       YouTubeVideo $YouTubeVideo object
     * @return      boolean
     *
     */
    public function removeYouTubeVideo($YouTubeVideo = NULL);

    /**
     *
     * Get you tube data from video
     *
     * @param       sUrl  $sUrl the urlto get the id from
     * @return      object
     *
     */
    public function getYouTubeDataFromVideo($sUrl);

    /**
     *
     * Save youtube video to database
     *
     * @param       youTubeVideo object
     * @return      void
     *
     */
    public function storeYouTube($youTubeVideo);

    /**
     *
     * Save youtube image to database
     *
     * @param       youTubeVideoImage object
     * @return      void
     *
     */
    public function storeYouTubeImage($youTubeVideoImage);
}
