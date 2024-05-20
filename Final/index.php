<?php
session_start();
session_regenerate_id();

require_once "login.php";
require_once "sql.php";
require_once "common.php";

$mysql = new SQL($hn, $un, $pw, $db); // start mysql connection

echo <<<_END
    <html>
        <head>
            <title>Authentication</title>
        </head>

        <body>
            <h2>Log In</h2>
            <form method='post' action='index.php' enctype='multipart/form-data' onSubmit="return validateLoginInputs(this)">
                <label for="studentIdLoginBox">Student ID: </label>
                <input type="number" id="studentIdLoginBox" name="studentIdLoginBox">
                <br><br>
                <label for="passwordLoginBox">Password: </label>
                <input type="password" id="passwordLoginBox" name="passwordLoginBox">
                <br><br>
                <input type='submit' value='Log In' name="login">
                <p id="loginError"></p>
            </form>
            <h2>Sign Up</h2>
            <form method='post' action='index.php' enctype='multipart/form-data' onSubmit="return validateSignupInputs(this)">
                <label for="name">Name: </label>
                <input type="text" id="name" name="name">
                <br><br>
                <label for="studentIdSignupBox">Student ID: </label>
                <input type="number" id="studentIdSignupBox" name="studentIdSignupBox">
                <br><br>
                <label for="emailSignupBox">Email: </label>
                <input type="email" id="emailSignupBox" name="emailSignupBox">
                <br><br>
                <label for="passwordSignupBox">Password: </label>
                <input type="password" id="passwordSignupBox" name="passwordSignupBox">
                <br><br>
                <input type='submit' value='Sign up' name="signup">
                <p id="signupError"></p>
            </form>
            <script type="text/javascript" src="validate.js">

            </script>
            <noscript>
                Your browser doesn't support or has disabled JavaScript
            </noscript>
        </body>
    </html>
    _END;

$name = $studentId = $email = $password = "";
// if users want to sign up
if (isset($_POST["signup"])) {
    // sanitize inputs
    if (isset($_POST["name"])) {
        $name = Common::sanitize_input($_POST["name"]);
    }
    if (isset($_POST["studentIdSignupBox"])) {
        $studentId = Common::sanitize_input($_POST['studentIdSignupBox']);
    }
    if (isset($_POST["emailSignupBox"])) {
        $email = Common::sanitize_input($_POST['emailSignupBox']);
    }
    if (isset($_POST["passwordSignupBox"])) {
        $password = Common::sanitize_input($_POST["passwordSignupBox"]);
    }
    // validate inputs
    $signup_error = Common::validateSignupInputs($name, $studentId, $email, $password);
    // there is error
    if ($signup_error !== "") {
        Common::print_signup_error($signup_error);
    } else { // if not
        // store new user in db
        $result = $mysql->insert_user($name, $studentId, $email, $password);
        // if there is any server issue
        if (!$result) {
            Common::print_server_error();
        } else { // success
            echo "Please proceed to login.";
        }
    }
}
// if users want to login
if (isset($_POST["login"])) {
    // sanitize inputs
    if (isset($_POST["studentIdLoginBox"])) {
        $studentId = Common::sanitize_input($_POST['studentIdLoginBox']);
    }
    if (isset($_POST["passwordLoginBox"])) {
        $password = Common::sanitize_input($_POST["passwordLoginBox"]);
    }
    // validate login inputs
    $login_error = Common::validateLoginInputs($studentId, $password);
    // there is error
    if ($login_error !== "") { 
        Common::print_login_error($login_error);
    } else {
        // get user in db
        $result = $mysql->get_user($studentId, $password);
        // if a user with a given studentid and password exists
        if ($result) {
            $_SESSION['studentId'] = $studentId;
            $_SESSION['check'] = hash('ripemd128', $_SERVER['REMOTE_ADDR'] .
                $_SERVER['HTTP_USER_AGENT']);
            // force refresh the page
            header("Location: question.php");
        } else {
            Common::print_login_error("The combination of StudentId and Password is not correct or an error has occurred.");
        }
    }
}
?>