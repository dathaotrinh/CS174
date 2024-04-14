<?php

require_once "login.php";
require_once "sql.php";

echo <<<_END
<html>

<head>
    <title>PHP Form Upload</title>
</head>

<body>
    <form method='post' action='index.php' enctype='multipart/form-data'>
        <label for="email">Email: </label>
        <input type="text" id="email" name="email">
        <br><br>
        Select File: <input type='file' name='filename' size='10'>
        <br><br>
        <input type='submit' value='Upload'>
    </form>
</body>

</html>
_END;

$mysql = new SQL($hn, $un, $pw, $db);

// check if email text box is not empty
// and file is input correct
if ($_FILES && isset($_POST["email"]) && !empty($_POST["email"])) {
    // check file
    $file_content = trim(read_file($mysql));
    // print content of the fild
    print_file_content($file_content);
    // process file content
    process_file_content($file_content, $mysql);
} else {
    // remainder to the users to input their email and upload transaction file
    echo("Please input your email and upload your transaction file.");
    // print mysql data
    $mysql->print_data();
}

/*
Print file content
*/
function print_file_content($file_content)
{
    echo "<b>File content</b>";
    echo "<pre>{$file_content}</pre>";
}


/*
Process file content
*/
function process_file_content($file_content, $mysql)
{
    echo "<br><b>Result</b><br>";
    $lines = explode("\n", $file_content);
    $balance = 0.0;

    // for new line in the file
    // get the balance - if it is a number
    // and calculate the balance of the transaction
    // ignore empty string, string, or any non-numeric values
    for ($index = 0; $index < count($lines); $index++) {
        // get next line
        $line = trim($lines[$index]);
        if (is_numeric($line)) {
            $balance += floatval($line);
            if ($balance < 0) {
                echo ($line . " -> total of the current transaction is -$" . -$balance . "<br>");
            } else {
                echo ($line . " -> total of the current transaction is $" . $balance . "<br>");                
            }
        } else {
            echo ($line . " -> invalid transaction" . "<br>");
        }
    }

    echo("<br>");

    // calculate the balance for a given account
    $mysql->get_balance($_POST["email"], $balance);

    // print database data
    $mysql->print_data();
}

/*
Read uploaded file
*/
function read_file($mysql)
{
    $file_name = santitize_file($_FILES['filename']['name']);
    $file_error = santitize_file($_FILES['filename']['error']);
    $file_tmp_name = santitize_file($_FILES['filename']['tmp_name']);
    $file_type = santitize_file($_FILES['filename']['type']);
    
    if ($file_error !== strval(UPLOAD_ERR_OK)) {
        echo('Fail to upload file.');
        $mysql->print_data();
        die (0); // The status 0 is used to terminate the program successfully
    } else if (!is_txt_file($file_name, $file_type)) {
        echo ("Cannot read file other than .txt format.");
        $mysql->print_data();
        die (0); // The status 0 is used to terminate the program successfully
    } else {
        return htmlentities(file_get_contents($file_tmp_name));
    }
}

/*
Sanitize file variable
*/
function santitize_file($file_var) 
{
    return htmlentities($file_var);
}

/*
Check file extension
*/
function is_txt_file($file_name, $file_type)
{
    return $file_type === 'text/plain' && pathinfo($file_name, PATHINFO_EXTENSION) === 'txt';
}

?>