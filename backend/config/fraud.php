<?php

return [
    'enabled' => true,

    'checks' => [
        'velocity_check' => [
            'enabled' => true,
            'max_transactions_per_10_min' => 5,
            'description' => 'Check for rapid transaction attempts',
        ],
        'amount_check' => [
            'enabled' => true,
            'threshold' => 1000000, // XAF - monto sospechoso
            'description' => 'Check for unusual transaction amounts',
        ],
        'location_check' => [
            'enabled' => true,
            'max_distance_km' => 1000, // cambio de ubicación en poco tiempo
            'description' => 'Check for impossible location changes',
        ],
        'blacklist_check' => [
            'enabled' => true,
            'description' => 'Check against fraud blacklist',
        ],
        'device_check' => [
            'enabled' => true,
            'description' => 'Check for new/unknown devices',
        ],
    ],

    'risk_scoring' => [
        'low' => [0, 25],
        'medium' => [26, 50],
        'high' => [51, 75],
        'critical' => [76, 100],
    ],

    'auto_block_threshold' => 80,
];
