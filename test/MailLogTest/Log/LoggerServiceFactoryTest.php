<?php
/**
 *
 * @author   Sven Friedemann <sven@ertellbar.de>
 * @licence  MIT
 */

namespace MailLogTest\Log;

use MailLogTest\Bootstrap;


class LoggerServiceFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testFactoryAlias()
    {
        $sm = Bootstrap::getServiceManager();
        $logger = $sm->get('Logger');

        $this->assertInstanceOf('Zend\Log\Logger', $logger);
    }
}
