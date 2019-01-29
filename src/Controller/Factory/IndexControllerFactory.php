<?php
namespace YouTube\Controller\Factory;

use Interop\Container\ContainerInterface;
use YouTube\Controller\IndexController;
use Zend\ServiceManager\Factory\FactoryInterface;
use YouTube\Service\youTubeService;
/**
 * This is the factory for AuthController. Its purpose is to instantiate the controller
 * and inject dependencies into its constructor.
 */
class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {   
        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $config = $container->get('config');
        $youTubeService = new youTubeService($entityManager, $config);
        return new IndexController($entityManager, $youTubeService);
    }
}