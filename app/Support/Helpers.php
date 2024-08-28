<?php

if (! function_exists('min_stock_percentage')) {
    // assume that we get the value from the config file
    // it may be other source like general settings
    function stock_min_percentage(): float
    {
        return config('ingredient_stocks.min_percentage');
    }
}

if (! function_exists('stock_notification_email')) {
    // assume that we get the value from the config file
    // it may be other source like general settings or specific users
    function stock_notification_email(): string
    {
        return config('ingredient_stocks.notification_email');
    }
}
