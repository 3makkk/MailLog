<?php
namespace MailLog;

use Zend\Log\Logger;
use Zend\Mvc\MvcEvent;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }

    public function onBootstrap(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $logger = $serviceManager->get('Logger');
        $config = $serviceManager->get('config');

        if (isset($config['log']['log_fatal_errors']) && $config['log']['log_fatal_errors'] === true) {
            Logger::registerFatalErrorShutdownFunction($logger);
        }
        if (isset($config['log']['log_errors']) && $config['log']['log_errors'] === true) {
            Logger::registerErrorHandler($logger);
        }

        if (isset($config['log']['log_exceptions']) && $config['log']['log_exceptions'] === true) {
            $eventManager = $e->getApplication()->getEventManager();
            $eventManager->attach('dispatch.error', [$this, 'logException']);
        }
    }

    /**
     * Log Exception on mvc error event
     * @param $event
     */
    public function logException($event)
    {
        $exception = $event->getResult()->exception;
        if ($exception) {
            $sm = $event->getApplication()->getServiceManager();
            $service = $sm->get('Logger');
            $service->err($exception);
        }
    }
}
