<?php
return [
    '/'   => [
        'app'   => 'core',
        'out'   => [ '#^(&|$)#i', '/' ],
        'in'    => [
            'regex' => [ "#^(/|$)#i" ],
            'matches'   => [
                'app'   =>  [ 'core' ],
                'controller'    => 'main',
                'action'        => 'index'
            ]
        ]
    ],

    '/register'   => [
        'app'   => 'core',
        'out'   => [ '#^register(&|$)#i', 'register' ],
        'in'    => [
            'regex' => [ "#^/register(/|$)#i" ],
            'matches'   => [
                'app'   =>  [ 'core' ],
                'controller'    => 'main',
                'action'        => 'registerForm'
            ]
        ]
    ],
];