<?php
require_once "matrix.php";
require_once "ui.html";

echo <<<_END

_END;

if ($_FILES) {
    // read file
    $file_content = read_file();

    // check if the file content is valid
    $processed_content = is_valid_input($file_content);

    // terminate the program if file content does not have 400 characters
    if($processed_content === -1) {
        die("Cannot build a 20x20 matrix from this file.");
    }

    // build new matrix with the new content
    $matrix = new Matrix($processed_content);

    // calculate largest product
    $matrix->calculate_largest_product();

    // calculate sum of factorial
    $matrix->calculate_sum_of_factorial();

    // print result
    $matrix->print_original_matrix();
    $matrix->print_largest_product_elements();
    $matrix->print_sum_of_factorial();
    $matrix->print_matrix_with_color();
}

tester_function();

/*
Read uploaded file
*/
function is_valid_input($file_content) {
    // ignore line break, whitespaces in file content
    $processed_content = str_replace(array("\r", "\n", " "), "", $file_content);

    // terminate the program if file content does not have 400 characters
    if (strlen($processed_content) !== 400) {
        return -1;
    } else {
        return $processed_content;
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
    return $file_type === 'text/plain' && pathinfo($file_name, PATHINFO_EXTENSION) === 'txt' ;
}

/*
Print file content
*/
function print_file_content($file_content)
{
    echo "<pre> . $file_content . </pre>";
}

/*

*/
function tester_function() {
    // Test case 1: good case - all numbers
    $input_1 = "8809923957743005112887997139824758583652926834585683956489996629078641451056304879044807200738438982607394687835670478798431127143303158968528375199999117093261306141241441828947633019301140134083971244242893157081475865751053579520354473827177006502617262296041505957762446974000477004597278743940629549490216562866176392941664835746737517346677844719930271334965923204520820902751347154711167988084";
    print_input($input_1);

    // build new matrix with the new content
    $matrix_1 = new Matrix($input_1);

    // calculate largest product
    $matrix_1->calculate_largest_product();

    // actual output 1
    $actual_output_1 = $matrix_1->get_largest_product();
    print_actual_output($actual_output_1);

    // expected output 1
    $expected_output_1 = 59049;
    print_expected_output($expected_output_1);

    print_test_result($input_1, $expected_output_1, $actual_output_1);

    // Test case 2: ugly case - all letters
    $input_2 = "ojzvrveottezmhhvzzzipxkwjfvpqmwphxnuxwajapknmvxeiexqtcavpgvctcnlbqtphxuvxooijlvpvzvrkgciqmliefurtelwgjopetiofrvzygpmnjyuiovvcxnbtyawizirumrkyfwdwyyiigfrkwnvsvxaessdkdvxjnzlqnxyucccyjcjumoxakphwgnjytpjrggfznllcdsooqokzpygkdrkkmxeplarbwyiygqsbbfayiwmqsvmintrkfvmnfrursecrjzklzonbculsocjymupsaslvqfjejpwlrfvrnkxpbxgnjelmetnukbdrnmigeoikkmrhlakajsdycudtmbvnxaxnngsfqctalxmtnxyncwoldngotoovwqqintxglsffhyg";
    print_input($input_2);
    
    // build new matrix with the new content
    $matrix_2 = new Matrix($input_2);

    // calculate largest product
    $matrix_2->calculate_largest_product();

    // actual output 2
    $actual_output_2 = $matrix_2->get_largest_product();
    print_actual_output($actual_output_2);

    // expected output 2
    $expected_output_2 = 0;
    print_expected_output($expected_output_2);

    print_test_result($input_2, $expected_output_2, $actual_output_2);


    // Test case 3: ugly case - both numbers, letters, symbols
    $input_3 = "674:6jj3a463://927//a3a2:467421j54c9a54c:3/226a8a:1j5a21864j642::/:8j9a413723jc11942452j:51c77/23346c371679c92197j17a5314869c:2a89/ac9754598/3c238581ca821a2:69j7c739c9/9162124312a/49jj/861928j661a/48928a499:6/8a74ca69115/43j271:586976j929:56626cc37a1/j858969caj78::5jcc73:27782756:661/4/a941/9:/17785/82c41371j8:85:/jaa:556:831c4a9a8/cc9132/796814/c9647635cc112aj91a61:c179245711a32875:aa455:49//425:";
    print_input($input_3);
    
    // build new matrix with the new content
    $matrix_3 = new Matrix($input_3);

    // calculate largest product
    $matrix_3->calculate_largest_product();

    // actual output 3
    $actual_output_3 = $matrix_3->get_largest_product();
    print_actual_output($actual_output_3);

    // expected output 3
    $expected_output_3 = 41472;
    print_expected_output($expected_output_3);

    print_test_result($input_3, $expected_output_3, $actual_output_3);

    // Test case 4: bad case
    // since my calculate_largest_product() only accepts valid 20x20 matrix,
    // I add a new test case here to test bad case where the input whose length is not 400
    // this calls is_valid_input() to check
    $input_4 = "badcase";
    print_input($input_4);

    // actual output 4
    $actual_output_4 = is_valid_input($input_4);
    print_actual_output($actual_output_4);

    // expected output 4
    $expected_output_4 = -1;
    print_expected_output($expected_output_4);

    print_test_result($input_4, $expected_output_4, $actual_output_4);
}

/*
    Print input
*/
function print_input($input) {
    echo "<br> Input: $input";
}

/*
    Print actual output
*/
function print_actual_output($actual_output)
{
    echo "<br> Actual output: $actual_output";
}

/*
    Print expected output
*/
function print_expected_output($expected_output)
{
    echo "<br> Expected output: $expected_output";
}

/*
    Print test result
*/
function print_test_result($input, $expected_output, $actual_output)
{
    if ($actual_output === $expected_output) {
        echo " -- Yes. test passed<br>";
    } else {
        echo " -- No. test failed<br>";
    }
}
?>