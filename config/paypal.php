<?php

return [
    'client_id' => env('AUD5EUaZdfhFxTftE7maWnumaSb-cvFBlGhx6pxBGjxCDzIl0b9VBOKHtHUQJGDMSyBkxK4DNvg8IMzz'),
    'secret' => env('EFLZDbODXEXpt2YZMg4k3ZMDLy3fi8PD3Gnqdh36jIBDE3_3qfIi6TRcsbw9CIWM3MPVb9s92Z5thrfO'),
    'settings' => [
        'mode' => env('PAYPAL_MODE', 'live'), // live or sandbox
        'http.ConnectionTimeOut' => 30,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'FINE', // Available: FINE, INFO, WARN, ERROR
    ],
];