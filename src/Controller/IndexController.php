<?php

namespace YouTube\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Laminas\Authentication\Result;
use Laminas\Uri\Uri;

/**
 * This controller is responsible for letting the user to log in and log out.
 */
class IndexController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager 
     */
    private $entityManager;
    private $youTubeService;
    
    /**
     * Constructor.
     */
    public function __construct($entityManager, $youTubeService)
    {
        $this->entityManager = $entityManager;
        $this->youTubeService = $youTubeService;
    }
    
    /**
     * Authenticates user given email address and password credentials.     
     */
    public function indexAction()
    {
        
        return new ViewModel([
        ]);
    }

    public function addYouTubeVideoAction() {
        $errorMessage = '';
        $success = true;
        $YouTubeData = [];

        $youTubeLink = $this->params()->fromPost('youTubeLink');
        if (!empty($youTubeLink)) {
            $YouTube = $this->youTubeService->getYouTubeDataFromVideo($youTubeLink);
            if (is_object($YouTube)) {

                //Create data array to send back with json
                $YouTubeData['youTubeId'] = $YouTube->getId();
                $YouTubeData['title'] = $YouTube->getTitle();
                $YouTubeData['imageurl'] = (is_object($YouTube->getYouTubeImageByType('maxres')) ? $YouTube->getYouTubeImageByType('maxres')->getUrl() : '');
            } else {
                $errorMessage = 'No You Tube video found!';
                $success = false;
            }
        } else {
            $errorMessage = 'No url data!';
            $success = false;
        }

        return new JsonModel([
            'errorMessage' => $errorMessage,
            'success' => $success,
            'YouTubeData' => $YouTubeData
        ]);
    }

    public function getYouTubeVideoAction() {
        $errorMessage = '';
        $success = true;
        $youTubeData = [];

        $id = $this->params()->fromPost('youTubeVideoId');
        if (empty($id)) {
            $success = false;
            $errorMessage = 'Id is missing!';
        } else {
            $youTubeVideo = $this->youTubeService->getYouTubeVideoById($id);
            if (empty($youTubeVideo)) {
                $success = false;
                $errorMessage = 'No You Tube video found!';
            } else {
                $youTubeData['title'] = $youTubeVideo->getTitle();
                $youTubeData['description'] = nl2br($youTubeVideo->getDescription());
                $youTubeData['youTubeId'] = $youTubeVideo->getYouTubeId();
                $interval = new \DateInterval($youTubeVideo->getDuration());
                $youTubeData['duration'] = $interval->format('%I:%S');
            }
        }



        return new JsonModel([
            'errorMessage' => $errorMessage,
            'success' => $success,
            'youTubeData' => $youTubeData,
        ]);
    }

    public function removeYouTubeVideoAction() {
        $errorMessage = '';
        $success = true;
        $youTubeId = $this->params()->fromPost('youTubeId');

        if ($youTubeId === NULL) {
            $errorMessage = 'Id is missing!';
            $success = false;
        } else {
            $youTubeVideo = $this->youTubeService->getYouTubeVideoById($youTubeId);
            if (!empty($youTubeVideo)) {
                $status = $this->youTubeService->removeYouTubeVideo($youTubeVideo);
                if (!$status) {
                    $errorMessage = 'Something went wrong! Please try again';
                    $success = false;
                }
            } else {
                $errorMessage = 'No You Tube video found!';
                $success = false;
            }
        }

        return new JsonModel([
            'errorMessage' => $errorMessage,
            'success' => $success
        ]);
    }
    
}