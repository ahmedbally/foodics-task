<?php

return [
    'min_percentage' => (float) env('STOCK_MIN_PERCENTAGE', 50) / 100, // 50% as default
    'notification_email' => env('STOCK_NOTIFICATION_EMAIL')
];
