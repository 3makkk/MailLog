<?php
/**
 *
 * @author   Sven Friedemann <sven@ertellbar.de>
 * @licence  MIT
 */

namespace MailLogTest;

use MailLog\Module;
use Zend\Log\Logger;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;

class ModuleTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Module
     */
    protected $module;

    /**
     * @var MvcEvent
     */
    protected $event;

    public function setUp()
    {
        $this->module = new Module();
        $this->event = $this->getMock(MvcEvent::class);

        $applicationMock = $this->getMockBuilder(Application::class)
            ->disableOriginalConstructor()
            ->getMock();

        $applicationMock->expects($this->once())
            ->method('getServiceManager')
            ->willReturn(Bootstrap::getServiceManager());

        $applicationMock->expects($this->once())
            ->method('getEventManager')
            ->willReturn($this->getMock('Zend\EventManager\EventManagerInterface'));

        $this->event->expects($this->any())
            ->method('getApplication')
            ->willReturn($applicationMock);
    }

    public function testOnBootstrapFatalErrorLogOption()
    {
        $sm = Bootstrap::getServiceManager();
        $sm->setAllowOverride(true);
        $config = $sm->get('config');
        $config['log']['log_fatal_errors'] = true;
        $sm->setService('config', $config);

        $this->module->onBootstrap($this->event);

        $logger = new Logger();
        $this->assertFalse($logger::registerFatalErrorShutdownFunction($logger));
    }

    public function testOnBootstrapErrorLogOption()
    {
        $sm = Bootstrap::getServiceManager();
        $sm->setAllowOverride(true);
        $config = $sm->get('config');
        $config['log']['log_errors'] = true;
        $sm->setService('config', $config);

        $this->module->onBootstrap($this->event);

        $logger = new Logger();
        $this->assertFalse($logger::registerErrorHandler($logger));
    }
}
