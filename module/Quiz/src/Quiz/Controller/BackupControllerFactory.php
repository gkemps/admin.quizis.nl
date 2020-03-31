<?php
namespace Quiz\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use pCloud;

class BackupControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        require_once("vendor/pcloud/pcloud-php-sdk/lib/pcloud/autoload.php");
        pCloud\Config::$credentialPath = "config/app.cred";

        return new BackupController(
            [
                "data/images" => 5670277258,
                //"data/audio" => 5670277878,
                //"data/mysql" => 5670278339
            ]
        );
    }
}