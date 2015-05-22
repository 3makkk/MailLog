<?php

namespace MailLogTest\Log\Service;


use MailLogTest\Bootstrap;
use Zend\Log\Writer\Mail;
use Zend\ServiceManager\ServiceManager;

/**
 * Class MailLogLoggerFactoryTest
 * @package MailLogTest\Log\Service
 */
class MailLogLoggerFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test if Factory uses correct alias
     */
    public function testFactoryUsesAliasToLogger()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setInvokableClass('MailLog\Logger', 'Zend\Log\Logger');
        $this->assertInstanceOf('Zend\Log\Logger', $serviceManager->get('MailLog\Logger'));
    }

    /**
     * Test if Factory create Logger
     */
    public function testFactoryCreatesLogger()
    {
        $service = $this->getLoggerMock(Bootstrap::getServiceManager());
        $this->assertInstanceOf('Zend\Log\Logger', $service);
    }

    public function testFactorySetsToFromConfig()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $config = $serviceManager->get('config');
        $config['mail_log']['to'] = array('test@test.test');
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('config', $config);
        $serviceManager->setAllowOverride(false);

        $logger = $this->getLoggerMock($serviceManager);

        foreach($logger->getWriters() as $writer) {
            if($writer instanceof Mail) {
                $reflection = new \ReflectionObject($writer);
                $messageReflection = $reflection->getProperty('mail');
                $messageReflection->setAccessible(true);

                $message = $messageReflection->getValue($writer);
                $this->assertTrue($message->getTo()->has('test@test.test'));
            }
        }
    }

    public function testFactorySetsSubjectFromConfig()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $config = $serviceManager->get('config');
        $config['mail_log']['subject'] = 'Foo';
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('config', $config);
        $serviceManager->setAllowOverride(false);

        $logger = $this->getLoggerMock($serviceManager);

        foreach($logger->getWriters() as $writer) {
            if($writer instanceof Mail) {
                $reflection = new \ReflectionObject($writer);
                $messageReflection = $reflection->getProperty('mail');
                $messageReflection->setAccessible(true);

                $message = $messageReflection->getValue($writer);
                $this->assertEquals($message->getSubject(), 'Foo');
            }
        }
    }

    protected function getLoggerMock(ServiceManager $serviceManager)
    {
        $service = $serviceManager->get('MailLog\Logger');

        return $service;
    }

}
