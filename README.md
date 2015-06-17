[![Build Status](https://travis-ci.org/3makkk/MailLog.svg)](https://travis-ci.org/3makkk/MailLog)

Mail Log
========

Created by Sven Friedemann

Introduction
------------

Module for Zend Framework 2 to log errors and exceptions.

Installation
------------

Add "3makkk\mail-log" to your composer.json file and update your dependencies.
1. Enable "MailLog" in your ```application.config.php```.
2. Copy the `maillog.local.php.dist` (you can find this file in the
    `config` folder of 3makkk\MailLog) into your config/autoload folder and apply any
    setting you want.

Requirements
------------

Zend Framework 2

Configuration
-------------
See ```[config/maillog.local.php.dist](config/maillog.local.php.dist).

Usage
-----

MailLog logs every error and sends them to your configured email address.

