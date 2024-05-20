<?php
session_start();
session_regenerate_id();

require_once "login.php";
require_once "sql.php";
require_once "common.php";

const COOKIE_PATH = "/";
const DESTROY_COOOKIE_CONSTANT = 2592000;

if (
    $_SESSION['check'] != hash('ripemd128', $_SERVER['REMOTE_ADDR'] .
        $_SERVER['HTTP_USER_AGENT'])
) {
    force_logout();
} else {
    if (isset($_SESSION['studentId'])) {
        // sanitize cookies
        $studentId = Common::sanitize_input($_SESSION['studentId']);
        $mysql = new SQL($hn, $un, $pw, $db, $studentId); // start mysql connection
        echo "<h2>Hello " . $studentId . "!</h2>";
        echo <<<_END
            <html>
    
            <head>
                <title>Question</title>
            </head>
    
            <body>
                <form method='post' action='question.php' enctype='multipart/form-data'>
                    <input type='submit' value='Log Out' name="logout">
                    <br><br>
                    Select File: <input type='file' name='filename' size='10'>
                    <br><br>
                    <input type='submit' value='Upload' name="upload">
                    <br><br>
                    <input type='submit' value='Suggest a question' name="suggest">
                </form>
            </body>
    
            </html>
        _END;

        // if users click get a question
        if (isset($_POST["suggest"])) {
            $result = $mysql->suggest_question();
            if ($result) {
                echo ($result . "<br><br>");
            }
        }
        // if users click on upload
        if (isset($_POST["upload"])) {
            if (
                $_FILES && read_file()
            ) {
                // check file
                $file_content = read_file();
                // split file content into multiple questions
                $questions = preg_split("/\r\n|\n|\r/", $file_content);
                // for each question
                // insert into the db
                foreach ($questions as $question) {
                    $insert_result = $mysql->insert_question($studentId, $question);
                    if (!$insert_result) {
                        echo "Could not add '$question' into database <br>";
                    } else {
                        echo "Added '$question' into database <br>";
                    }
                }
            } else { // if there are any input fields missing
                echo ("Please upload a txt file. <br>");
            }
        }
        // print list of questions belong to an user
        $print_questions_result = $mysql->print_questions();
        if (!$print_questions_result) {
            echo ("No question associated with this student.");
        }
        // if users choose logout
        if (isset($_POST['logout'])) {
            force_logout();
        }
    } else {
        header("Location: index.php");
    }
}
function destroy_session_and_data()
{
    $_SESSION = array();	// Delete all the information in the array
    setcookie(session_name(), '', time() - DESTROY_COOOKIE_CONSTANT, COOKIE_PATH);
    session_destroy();
}

function force_logout()
{
    // destroy session
    destroy_session_and_data();
    // force refresh the page
    header("Location: index.php");
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

    if ($file_error !== strval(UPLOAD_ERR_OK) || !is_txt_file($file_name, $file_type)) {
        return false;
    }
    return htmlentities(file_get_contents($file_tmp_name));
}


/*
Check file extension
*/
function is_txt_file($file_name, $file_type)
{
    return $file_type === 'text/plain' && pathinfo($file_name, PATHINFO_EXTENSION) === 'txt';
}
?>