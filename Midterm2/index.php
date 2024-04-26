<?php
// ini_set('session.use_only_cookies', 1);
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
            <h1>Hello!</h1>
            <h2>Log In</h2>
            <form method='post' action='index.php' enctype='multipart/form-data'>
                <label for="usernameLoginBox">Username: </label>
                <input type="text" id="usernameLoginBox" name="usernameLoginBox">
                <br><br>
                <label for="passwordLoginBox">Password: </label>
                <input type="password" id="passwordLoginBox" name="passwordLoginBox">
                <br><br>
                <input type='submit' value='Log In' name="login">
            </form>
            <h2>Sign Up</h2>
            <form method='post' action='index.php' enctype='multipart/form-data'>
                <label for="name">Name: </label>
                <input type="text" id="name" name="name">
                <br><br>
                <label for="usernameSignupBox">Username: </label>
                <input type="text" id="usernameSignupBox" name="usernameSignupBox">
                <br><br>
                <label for="passwordSignupBox">Password: </label>
                <input type="password" id="passwordSignupBox" name="passwordSignupBox">
                <br><br>
                <input type='submit' value='Sign up' name="signup">
            </form>
        </body>
    </html>
    _END;

// if users want to sign up
if (isset($_POST["signup"])) {
    // make sure all required fields are filled out
    if (
        isset($_POST["name"]) && isset($_POST["usernameSignupBox"]) && isset($_POST["passwordSignupBox"])
        && !empty($_POST["name"]) && !empty($_POST["usernameSignupBox"]) && !empty($_POST["passwordSignupBox"])
    ) {
        // sanitize input
        $name = Common::sanitize_input($_POST["name"]);
        $username = Common::sanitize_input($_POST['usernameSignupBox']);
        $password = Common::sanitize_input($_POST["passwordSignupBox"]);
        // store new user in db
        $result = $mysql->insert_user($name, $username, $password);
        // if there is any server issue (no table/duplicated user/connection issue)
        if ($result === -1) {
            Common::print_server_error();
        } else if ($result === 0) { // query failed issue
            Common::print_signup_error();
        } else { // success
            echo "Please proceed to login.";
        }
    } else {
        Common::print_required_fields_error();
    }
} else if (isset($_POST["login"])) { // if users want to login
    // make sure all required fields are filled out
    if (
        isset($_POST["usernameLoginBox"]) && !empty($_POST["usernameLoginBox"])
        && isset($_POST["passwordLoginBox"]) && !empty($_POST["passwordLoginBox"])
    ) {
        // sanitize
        $username = Common::sanitize_input($_POST['usernameLoginBox']);
        $password = Common::sanitize_input($_POST["passwordLoginBox"]);
        // fetch user with a specific username and password
        $result = $mysql->get_user($username, $password);
        // if server issue
        if ($result === -1) {
            Common::print_server_error();
        } else if ($result === 0) { // query failed issue
            Common::print_login_error();
        } else { // success
            // if a user with a given username and password exists
            if ($result->num_rows > 0) {
                $result->data_seek(0);
                $row = $result->fetch_array(MYSQLI_ASSOC);
                // make sure the passwords matched
                if (password_verify($password, $row['password'])) {
                    // start session
                    // session_start();
                    $_SESSION['name'] = $row['name'];
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['check'] = hash('ripemd128', $_SERVER['REMOTE_ADDR'] .
                        $_SERVER['HTTP_USER_AGENT']);
                    // force refresh the page
                    header("Location: thread.php");
                } else {
                    Common::print_login_error();
                }
            } else {
                Common::print_login_error();
            }
            $result->close(); // deallocate the result
        }
    } else {
        Common::print_required_fields_error();
    }
}

?>