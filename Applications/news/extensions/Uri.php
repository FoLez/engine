<?php
return [
    '/news'   => [
        'app'   => 'news',
        'out'   => [ '#^news(&|$)#i', 'news' ],
        'in'    => [
            'regex' => [ "#^/news(/|$)#i" ],
            'matches'   => [
                'app'   =>  [ 'news' ],
                'controller'    => 'news',
                'action'        => 'list'
            ]
        ]
    ],
];