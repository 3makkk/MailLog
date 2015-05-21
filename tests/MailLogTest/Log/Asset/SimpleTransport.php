<?php

namespace MailLogTest\Log\Asset;

use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;

/**
 * Class SimpleTransport
 * @package MailLogTest\Log\Asset
 */
class SimpleTransport implements TransportInterface {

    protected $message;

    public function send(Message $message)
    {
        $this->message = $message;
    }

    public function getLastMessage()
    {
        return $this->message;
    }
}