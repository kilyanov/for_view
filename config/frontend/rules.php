<?php

return [
    '' => '/device',
    'login' => 'site/login',
    'logout' => 'site/logout',

    'contract/specification/index/<contractId:[\w\-]+>' => 'contract/specification/index',
    'contract/specification/create/<contractId:[\w\-]+>' => 'contract/specification/create',

    /**
     * System rules

    '<module:[\w\-]+>' => '<module>',
    '<module:[\w\-]+>/<controller:[\w\-]+>/<action:[\w\-]+>/<page:\d+>/<per-page:\d+>' => '<module>/<controller>/<action>',
    '<module:[\w\-]+>/<controller:[\w\-]+>/<action:[\w\-]+>/<id:[\w\-]+>' => '<module>/<controller>/<action>',
    '<module:[\w\-]+>/<controller:[\w\-]+>/<action:[\w\-]+>' => '<module>/<controller>/<action>',*/
];
