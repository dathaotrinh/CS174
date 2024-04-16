<?php

require_once "login.php";
require_once "sql.php";

const COOKIE_EXPIRE_SECONDS = 60; // cookie expires after 1 minute
$mysql = new SQL($hn, $un, $pw, $db); // start mysql connection

// if cookies are set
// only show the research
if (isset($_COOKIE['name']) && isset($_COOKIE['id'])) {
    // sanitize cookies
    $cookie_id = sanitize_input($_COOKIE['id']);
    $cookie_name = sanitize_input($_COOKIE['name']);
    echo "<h2>Hello " . $cookie_name . "!</h2>";
    echo <<<_END
        <html>

        <head>
            <title>Research</title>
        </head>

        <body>
            <form method='post' action='index.php' enctype='multipart/form-data'>
                <label for="title">Title: </label>
                <input type="text" id="title" name="title">
                <br><br>
                <label for="content">Content: </label>
                <textarea id="content" name="content"></textarea>
                <br><br>
                <input type='submit' value='Upload' name="upload">
            </form>
        </body>

        </html>
    _END;

    // if users upload their research paper
    if (isset($_POST["upload"])) {
        if (
            isset($_POST["title"]) && !empty($_POST["title"])
            && isset($_POST["content"]) && !empty($_POST["content"])
        ) {
            // sanitize the paper
            $title = sanitize_input($_POST["title"]);
            $content = sanitize_input($_POST['content']);
            // save the paper in db
            $insert_result = $mysql->insert_research($title, $content, $cookie_id);
            // if no server issue
            if ($insert_result !== -1) {
                // print research
                $mysql->print_research($cookie_id);
            } else {
                // print generic message
                print_server_error();
            }
        } else { // if there are any input fields missing
            echo ("Please input the research title and the research content.");
            $mysql->print_research($cookie_id);
        }
    } else {
        $mysql->print_research($cookie_id);
    }
} else { // if cookie is not set, show the authentication
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
            $name = sanitize_input($_POST["name"]);
            $username = sanitize_input($_POST['usernameSignupBox']);
            $password = sanitize_input($_POST["passwordSignupBox"]);
            // store new user in db
            $result = $mysql->insert_user($name, $username, $password);
            // if there is any server issue (no table/duplicated user/connection issue)
            if ($result === -1) {
                print_server_error();
            } else if ($result === 0) { // query failed issue
                print_signup_error();
            } else { // success
                echo "Please proceed to login.";
            }
        } else {
            print_required_fields_error();
        }
    } else if (isset($_POST["login"])) { // if users want to login
        // make sure all required fields are filled out
        if (
            isset($_POST["usernameLoginBox"]) && !empty($_POST["usernameLoginBox"])
            && isset($_POST["passwordLoginBox"]) && !empty($_POST["passwordLoginBox"])
        ) {
            // sanitize
            $username = sanitize_input($_POST['usernameLoginBox']);
            $password = sanitize_input($_POST["passwordLoginBox"]);
            // fetch user with a specific username and password
            $result = $mysql->get_user($username, $password);
            // if server issue
            if ($result === -1) {
                print_server_error();
            } else if ($result === 0) { // query failed issue
                print_login_error();
            } else { // success
                // if a user with a given username and password exists
                if ($result->num_rows > 0) {
                    $result->data_seek(0);
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    // make sure the passwords matched
                    if (password_verify($password, $row['password'])) {
                        // set cookies
                        $cookie_expire_duration = time() + COOKIE_EXPIRE_SECONDS;
                        $cookie_path = "/";
                        setcookie('name', $row['name'], $cookie_expire_duration, $cookie_path);
                        setcookie('id', $row['id'], $cookie_expire_duration, $cookie_path);
                        // force refresh the page
                        header("Location: index.php");
                    } else {
                        print_login_error();
                    }
                } else {
                    print_login_error();
                }
                $result->close(); // deallocate the result
            }
        } else {
            print_required_fields_error();
        }
    }
}

/*
Sanitize string
*/
function sanitize_input($str)
{
    return htmlentities($str);
}

/*
Print server error
*/
function print_server_error()
{
    echo "An error has occurred.";
}

/*
Print signup error
*/
function print_signup_error()
{
    echo "Failed to signup.";
}

/*
Print login error
*/
function print_login_error()
{
    echo "Failed to login.";
}

/*
Print required fields error
*/
function print_required_fields_error()
{
    echo "Please fill out all the required fields.";
}

?>