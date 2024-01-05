<?php

if (!function_exists('generageOrderCode')) {
    function generageOrderCode($userId)
    {
        return 1 . str_pad($userId, 5, '0', STR_PAD_LEFT) . time();
    }
}
