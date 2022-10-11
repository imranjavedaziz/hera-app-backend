<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use App\Models\User;
use Hash;
use JWTAuth;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthHelper
{
    public static function createToken($email)
    {
        return base64_encode($email);
    }  

    public static function dateConvert($date, $format)
    {
        return date_format(date_create($date), $format);
    }
    
    public static function authenticatedUser()
    { 
        $returnNull = null;
        try {
            if (!JWTAuth::parseToken()->authenticate()) {
                $returnNull;
            } else {
                return JWTAuth::parseToken()->authenticate();
            }
        } catch (TokenExpiredException $e) {
            $returnNull;
        } catch (TokenInvalidException $e) {
            $returnNull;
        } catch (JWTException $e) {
            $returnNull;
        }
        return $returnNull;
    }
}
