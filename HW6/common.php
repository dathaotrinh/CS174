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
    public static function print_signup_error($error_message)
    {
        echo ("Signup error " . $error_message);
    }

    /*
    Print login error
    */
    public static function print_login_error($error_message)
    {
        echo ("Login error " . $error_message);
    }

    /*
    Print lookup error
    */
    public static function print_lookup_error($error_message)
    {
        echo ("Lookup error " . $error_message);
    }

    // student id should be 9 digits
    public static function isValidStudentId($studentId)
    {
        return strlen(abs(intval($studentId))) === 9;
    }

    // email should not be empty
    // should not start with a special character
    // should have 1 @ and only 1 . after @
    // should end with .{2to4chactacters} such as .com, .edu...
    // should not end with .a, .b, .abcde
    public static function isValidEmail($email)
    {
        return strlen($email) > 0 && preg_match("/^[a-zA-Z0-9]+[\w\-\.]*@([\w-]+\.){1}[\w\-]{2,4}$/", $email);
    }

    // Name should not be empty
    public static function isValidName($name)
    {
        return strlen($name) > 0;
    }

    // Password must be more than 6 characters, include at least 1 Upper case and at least 1 Lower case
    public static function isValidPassword($password)
    {
        return strlen($password) > 6 && preg_match("/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?!.* ).{6,}$/", $password);
    }

    // validate singup inputs
    public static function validateSignupInputs($name, $studentId, $email, $password)
    {
        $errorMessage = "";
        if (!Common::isValidName($name)) {
            $errorMessage .= "Name should not be empty";
        } else if (!Common::isValidStudentId($studentId)) {
            $errorMessage .= "Student ID should be 9 digits and should not be negative";
        } else if (!Common::isValidEmail($email)) {
            $errorMessage .= "Email should not be empty and should be a proper email";
        } else if (!Common::isValidPassword($password)) {
            $errorMessage .= "Password must be more than 6 characters, include at least 1 Upper case and at least 1 Lower case";
        }
        if ($errorMessage !== "") {
            return $errorMessage;
        } else {
            return "";
        }
    }

    // login fields should not be empty
    public static function validateLoginInputs($studentId, $password)
    {
        if (strlen($studentId) === 0 || strlen($password) === 0) {
            return "Login fields should not be empty";
        }
        return "";
    }

    // Lookup fields should not be empty
    public static function validateLookupInputs($studentId, $studentName)
    {
        if (strlen($studentId) === 0 || strlen($studentName) === 0) {
            return "Lookup fields should not be empty";
        }
        return "";
    }
}
?>