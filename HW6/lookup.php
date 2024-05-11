<?php
session_start();
session_regenerate_id();

require_once "login.php";
require_once "sql.php";
require_once "common.php";

const COOKIE_PATH = "/";
const DESTROY_COOOKIE_CONSTANT = 2592000;
$mysql = new SQL($hn, $un, $pw, $db); // start mysql connection

if (
    $_SESSION['check'] != hash('ripemd128', $_SERVER['REMOTE_ADDR'] .
        $_SERVER['HTTP_USER_AGENT'])
) {
    force_logout();
} else {
    if (isset($_SESSION['studentId'])) {
        // sanitize cookies
        $session_id = Common::sanitize_input($_SESSION['studentId']);
        echo "<h2>Hello " . $session_id . "!</h2>";
        echo <<<_END
            <html>
    
            <head>
                <title>Lookup</title>
            </head>
    
            <body>
                <form method='post' action='lookup.php' enctype='multipart/form-data'>
                    <input type='submit' value='Log Out' name="logout">
                </form>
                <form method='post' action='lookup.php' enctype='multipart/form-data' onSubmit="return validateLookupInputs(this)">
                    <label for="studentName">Student Name: </label>
                    <input type="text" id="studentName" name="studentName">
                    <br><br>
                    <label for="studentId">Student Id: </label>
                    <input type="number" id="studentId" name="studentId">
                    <br><br>
                    <input type='submit' value='Lookup' name="lookup">
                </form>
                <script type="text/javascript" src="validate.js">

                </script>
                <noscript>
                    Your browser doesn't support or has disabled JavaScript
                </noscript>
            </body>
    
            </html>
        _END;

        // if users click look up
        if (isset($_POST["lookup"])) {
            $studentName = $studentId = "";
            // sanitize lookup inputs
            if (isset($_POST["studentName"])) {
                $studentName = Common::sanitize_input($_POST['studentName']);
            }
            if (isset($_POST["studentId"])) {
                $studentId = Common::sanitize_input($_POST["studentId"]);
            }
            // validate look up inputs
            $lookup_error = Common::validateLookupInputs($studentId, $studentName);
            if ($lookup_error !== "") { // there is error
                Common::print_lookup_error($lookup_error);
            } else {
                // look up advisor
                $result = $mysql->lookup_advisor($studentId, $studentName);
                if (!$result) {
                    echo "No student found with that name and id.";
                }
            }
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
?>