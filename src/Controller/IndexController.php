<?php

namespace YouTube\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Result;
use Zend\Uri\Uri;

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
    
}