<?php

return [
    'headers' => [
        'CF-Connecting-IP',
        'True-Client-IP',
        'X-Real-IP',
    ],
    'trust_x_forwarded_for' => true,
];
