<?php

namespace MailLog\Log\Service;

use AcMailer\Service\MailService;
use Zend\Log\Logger;
use Zend\Log\Writer\Mail as MailLogWriter;
use Zend\Log\Writer\Stream;
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

        /** @var $mailService MailService */
        $mailService = $serviceLocator->get('AcMailer\Service\MailService');

        $message = $mailService->getMessage();
        $transport = $mailService->getTransport();

        if(!empty($mailConfig['to'])) {
            $message->setTo($mailConfig['to']);
        }

        $writer = new MailLogWriter($message, $transport);

        if(!empty($mailConfig['subject'])) {
            $writer->setSubjectPrependText($mailConfig['subject']);
        }

        $logger = new Logger();
        $logger->addWriter($writer);

        return $logger;
    }
}