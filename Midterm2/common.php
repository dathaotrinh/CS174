<?php
class Common
{
    /*
    Sanitize string
    */
    public static function sanitize_input($str)
    {
        return htmlentities($str);
    }

    /*
    Print server error
    */
    public static function print_server_error()
    {
        echo "An error has occurred.";
    }

    /*
    Print signup error
    */
    public static function print_signup_error()
    {
        echo "Failed to signup.";
    }

    /*
    Print login error
    */
    public static function print_login_error()
    {
        echo "Failed to login.";
    }

    /*
    Print required fields error
    */
    public static function print_required_fields_error()
    {
        echo "Please fill out all the required fields.";
    }
    
}
?>