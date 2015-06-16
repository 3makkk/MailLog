<?php

namespace MailLog\Log;

/**
 *
 * @author   Sven Friedemann <sven@ertellbar.de>
 * @licence  MIT
 */
class Logger extends \Zend\Log\Logger {

    public static $errorPriorityMap = array(
        E_NOTICE => self::NOTICE,
        E_USER_NOTICE => self::NOTICE,
        E_WARNING => self::WARN,
        E_CORE_WARNING => self::WARN,
        E_USER_WARNING => self::WARN,
        E_ERROR => self::ERR,
        E_USER_ERROR => self::ERR,
        E_CORE_ERROR => self::ERR,
        E_RECOVERABLE_ERROR => self::ERR,
        E_PARSE => self::ERR,
        E_COMPILE_ERROR => self::ERR,
        E_COMPILE_WARNING => self::ERR,
        E_STRICT => self::DEBUG,
        E_DEPRECATED => self::DEBUG,
        E_USER_DEPRECATED => self::DEBUG
    );

    /**
     * Register a shutdown handler to log fatal errors
     *
     * @link http://www.php.net/manual/function.register-shutdown-function.php
     * @param  Logger $logger
     * @return bool
     */
    public static function registerFatalErrorShutdownFunction(\Zend\Log\Logger $logger)
    {
        // Only register once per instance
        if (static::$registeredFatalErrorShutdownFunction) {
            return false;
        }

        $errorPriorityMap = static::$errorPriorityMap;

        register_shutdown_function(function () use ($logger, $errorPriorityMap) {
            $error = error_get_last();
            if (null !== $error && array_key_exists($error['type'], self::$errorPriorityMap) && self::$errorPriorityMap[$error['type']] == self::ERR) {
                $logger->log($errorPriorityMap[$error['type']], $error['message'], array(
                    'file' => $error['file'],
                    'line' => $error['line']
                ));
            }

        });

        static::$registeredFatalErrorShutdownFunction = true;

        return true;
    }
}