<?php

return [
    'allowed_extensions' => [
        'mp3',
        'wav',
        'ogg',
    ],

    // minute(s)
    'expire_time' => \Carbon\Carbon::MINUTES_PER_HOUR * \Carbon\Carbon::HOURS_PER_DAY,

    'per_page' => 10,
];
