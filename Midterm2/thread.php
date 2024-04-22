<?php

session_start();

require_once "login.php";
require_once "sql.php";
require_once "common.php";

const DESTROY_COOOKIE_CONSTANT = 2592000;
$mysql = new SQL($hn, $un, $pw, $db); // start mysql connection

if (isset($_SESSION['name']) && isset($_SESSION['id'])) {
    // sanitize cookies
    $session_id = Common::sanitize_input($_SESSION['id']);
    $session_name = Common::sanitize_input($_SESSION['name']);
    echo "<h2>Hello " . $session_name . "!</h2>";
    echo <<<_END
        <html>

        <head>
            <title>Research</title>
        </head>

        <body>
            <form method='post' action='thread.php' enctype='multipart/form-data'>
                <input type='submit' value='Log Out' name="logout">
                <br><br>
                <label for="thread_name">Thread name: </label>
                <input type="text" id="thread_name" name="thread_name">
                <br><br>
                Select File: <input type='file' name='filename' size='10'>
                <br><br>
                <input type='submit' value='Upload' name="upload">
            </form>
        </body>

        </html>
    _END;

    // if users upload their research paper
    if (isset($_POST["upload"])) {
        if (
            $_FILES && read_file()
            && isset($_POST["thread_name"]) && !empty($_POST["thread_name"])
        ) {
            // check file
            $file_content = read_file();
            $thread_name = Common::sanitize_input($_POST["thread_name"]);
            // save the paper in db
            $insert_result = $mysql->insert_thread($thread_name, $file_content, $session_id);
            // if no server issue
            if ($insert_result !== -1) {
                // print threads
                $mysql->print_threads($session_id, 0);
            } else {
                // print generic message
                Common::print_server_error();
            }
        } else { // if there are any input fields missing
            echo ("Please input the thread name and upload a correct file.");
            $mysql->print_threads($session_id, 0);
        }
    } else if (isset($_POST["expand"])) {
        $mysql->print_threads($session_id, 1);
    } else if (isset($_POST["collapse"])) {
        $mysql->print_threads($session_id, 0);
    } else if (isset($_POST['logout'])) {
        // destroy session
        destroy_session_and_data();
        // force refresh the page
        header("Location: index.php");
    } else {
        $mysql->print_threads($session_id, 0);
    }
} else {
    header('HTTP/1.0 401 Unauthorized');
}


/*
Read uploaded file
*/
function read_file()
{
    $file_name = Common::sanitize_input($_FILES['filename']['name']);
    $file_error = Common::sanitize_input($_FILES['filename']['error']);
    $file_tmp_name = Common::sanitize_input($_FILES['filename']['tmp_name']);
    $file_type = Common::sanitize_input($_FILES['filename']['type']);

    if ($file_error !== strval(UPLOAD_ERR_OK)) {
        return false;
    } else if (!is_txt_file($file_name, $file_type)) {
        return false;
    } else {
        // replace \n with /n because stripslashes built-in function 
        // will remove backlashes
        return htmlentities(str_replace("\n", "/n", file_get_contents($file_tmp_name)));
    }
}


/*
Check file extension
*/
function is_txt_file($file_name, $file_type)
{
    return $file_type === 'text/plain' && pathinfo($file_name, PATHINFO_EXTENSION) === 'txt';
}


function destroy_session_and_data()
{
    $_SESSION = array();	// Delete all the information in the array
    setcookie(session_name(), '', time() - DESTROY_COOOKIE_CONSTANT, '/');
    session_destroy();
}
?>