<?php

namespace MailLog\Log\Service;

use Zend\Log\Logger;
use Zend\Log\Writer\Mail;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class MailLogLoggerFactory
 * @package MailLog\Log\Service
 */
class MailLogLoggerFactory implements FactoryInterface {


    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $configArray = $serviceLocator->get('Config');
        $mailConfig = $configArray['mail_log'];

        $transport = $serviceLocator->get('Soflomo\Mail\Transport');
        $message   = $serviceLocator->get('Soflomo\Mail\Message');

        if(!empty($mailConfig['to'])) {
            $message->setTo($mailConfig['to']);
        }

        if(!empty($mailConfig['subject'])) {
            $message->setSubject($mailConfig['subject']);
        }

        $writer = new Mail($message, $transport);

        $logger = new Logger();
        $logger->addWriter($writer);

        return $logger;
    }
}