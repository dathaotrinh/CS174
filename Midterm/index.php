<?php

require_once "lru.php";
require_once "tester.php";
echo <<<_END
<html>

<head>
    <title>PHP Form Upload</title>
</head>

<body>
    <form method='post' action='index.php' enctype='multipart/form-data'>
        Select File: <input type='file' name='filename' size='10'>
        <input type='submit' value='Upload'>
    </form>
</body>

</html>
_END;

if ($_FILES) {
    // check and file
    $file_content = trim(read_file());
    print_file_content($file_content);
    // process file content
    process_file_content($file_content);
}

// call tester function
LRU_Tester::tester_function();

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
function process_file_content($file_content)
{
    echo "<br><b>Result</b><br>";
    $lru_cache = null;
    $lines = explode("\n", $file_content);
    // process first line to construct a LRU Cache
    // the value in the first line indicates the size of LRU Cache
    $lru_size = 2; // predefine the cache size to be 2
    // cache size has to be positive and there should be some content in the txt file
    if (count($lines) > 0 && $lru_size > 0) {
        $lru_cache = new LRUCache($lru_size);
        print_construct_output($lru_size, $lru_cache);
    } else {
        die("Cannot create a LRU Cache from this file. Goodbye!");
    }
    // process next lines
    for ($index = 0; $index < count($lines); $index++) {
        // get next line
        $line = trim($lines[$index]);
        $values = explode(" ", $line);
        // call get
        if (count($values) === 1) {
            // $get_value = (int) $values[0];
            $get_value = $values[0];
            print_get_output($get_value, $lru_cache->get($get_value), $lru_cache);
        }
        // call put
        else if (count($values) === 3) {
            $put_key = $values[0];
            $put_value = $values[1];
            $put_reset = (strtolower($values[2]) === "true") ? true : false;
            $put_result = $lru_cache->put($put_key, $put_value, $put_reset) . " " . $lru_cache->print_current_cache();
            print_put_output($put_key, $put_value, $put_reset, $put_result);
        }
        // unrecognized input
        else {
            die("Cannot create a LRU Cache from this file. Goodbye!");
        }
    }
}

/*
Read uploaded file
*/
function read_file()
{
    $file_name = htmlentities($_FILES['filename']['name']);
    $file_error = htmlentities($_FILES['filename']['error']);
    $file_tmp_name = htmlentities($_FILES['filename']['tmp_name']);
    $file_type = htmlentities($_FILES['filename']['type']);
    if ($file_error !== strval(UPLOAD_ERR_OK)) {
        die('Fail to upload file.');
    } else if (!is_txt_file($file_name, $file_type)) {
        die("Cannot read file other than .txt format.");
    } else {
        return htmlentities(file_get_contents($file_tmp_name));
    }
}


/*
Check file extension
*/
function is_txt_file($file_name, $file_type)
{
    return $file_type === 'text/plain' && pathinfo($file_name, PATHINFO_EXTENSION) === 'txt';
}


function print_construct_output($lru_size, $lru_cache)
{
    $current_cache_str = $lru_cache->print_current_cache();
    echo "constructor({$lru_size}) —> {$current_cache_str} <br>";
}

/*
    Print put output
*/
function print_put_output($key, $value, $is_reset, $result)
{
    $is_reset = $is_reset === true ? "True" : "False";
    echo "put({$key}, {$value}, {$is_reset}) —> {$result} <br>";
}

/*
    Print get output
*/
function print_get_output($value, $result, $lru_cache)
{
    if ($result === -1) {
        echo "get({$value}) —> return {$result} <br>";
    } else {
        $current_cache_str = $lru_cache->print_current_cache();
        echo "get({$value}) —> return {$result}, {$current_cache_str} <br>";
    }
}

?>