<?php

namespace App\Helper;

class CustomHelper
{
    public static function nameRegex() {
        return 'regex:/^[a-zA-Z\s\. ]+$/';
    }

    public static function emailRegex() {
        return 'regex:/^([a-zA-Z0-9\+_\-]+)(\.[a-zA-Z0-9\+_\-]+)*@([a-zA-Z0-9\-]+\.)+[a-zA-Z]{2,6}$/';
    }

    public static function passwordRegex() {
        return 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$!%^*?&])[A-Za-z\d@#$!%^*?&]{8,}$/';
    }
}
