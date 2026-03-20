<?php
if (!function_exists('RandomFloat')) {
    function RandomFloat($min, $max, $decimals=2)
    {
        $scale = pow(10, $decimals);
        return mt_rand($min * $scale, $max * $scale) / $scale;
    }
}