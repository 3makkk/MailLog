<?php
return [
    'service_manager' => [
        'factories' => [
            'Logger' => 'Zend\Log\LoggerServiceFactory'
        ]
    ],
    'log' => [
        'log_exceptions' => true,
        'log_errors' => true,
        'log_fatal_errors' => true,
        'writers' =>[
            [
                'name' => 'mail',
                'options' => [
                    'subject_prepend_text' => 'Error Log',
                    'mail' => [
                        'to' => 'log@log.de'
                    ]
                ]
            ]
        ]
    ]

];
