<?php

/*
 * This file is part of Laravel Hashids.
 *
 * (c) Vincent Klaiber <hello@vinkla.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the connections below you wish to use as
    | your default connection for all work. Of course, you may use many
    | connections at once using the manager class.
    |
    */

    'default' => 'main',

    /*
    |--------------------------------------------------------------------------
    | Hashids Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the connections setup for your application. Example
    | configuration has been included, but you may add as many connections as
    | you would like.
    |
    */

    'connections' => [

        'users' => [
            'salt' => 'J9C3P3Q0ltDx7qWQqKfB81SnSgPyAJP94SZI2xgWHER4Xg',
            'length' => 6,
            'alphabet' => '0123456789abcdefzi',
        ],

        'conversations' => [
            'salt' => 'KsES5mTZnyHyfWiiLokCpjtCQPupbG3TMmYEQ1F7ZvLpaq',
            'length' => 6,
            'alphabet' => '0123456789abcdefzi',
        ],

        'messages' => [
            'salt' => 'KsES5mT1sfgqqZnyHyfWifiFDkCpjtCQDD3quoTeMmYEQ1F7ZvLpaq',
            'length' => 10,
            'alphabet' => '0123456789abcdefzi',
        ]

    ],

];
