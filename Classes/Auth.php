<?php

namespace Classes;

class Auth
{

    public static function login($id)
    {
        $_SESSION['user-id'] = $id;
    }

    public static function getUserId()
    {
        return (isset($_SESSION['user-id']))?$_SESSION['user-id']:null;
    }

}