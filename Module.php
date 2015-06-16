<?php
namespace MailLog;


use MailLog\Log\Logger;
use Zend\Mvc\MvcEvent;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function onBootstrap(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $logger = $serviceManager->get('MailLog\Logger');

        Logger::registerFatalErrorShutdownFunction($logger);
        Logger::registerErrorHandler($logger);

        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach('dispatch.error', function($event) {
            $exception = $event->getResult()->exception;
            if ($exception) {
                $sm = $event->getApplication()->getServiceManager();
                $service = $sm->get('MailLog\Logger');
                $service->err($exception);
            }
        });
    }
}
