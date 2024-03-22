<?php

/*
    Check if two inputs are coprimes of each other
*/
function are_co_primes($num1, $num2)
{
    return greatest_common_divisor($num1, $num2) === 1 ? "true" : "false";
}

/*
    Check the greatest common divisor of two inputs
*/
function greatest_common_divisor($num1, $num2)
{
    // check if inputs are positive integers
    if (is_int($num1) && is_int($num2) && $num1 >= 0 && $num2 >= 0) {
        if ($num1 === 0 || $num2 === 0) {
            return 0;
        } else if ($num1 === $num2) {
            return $num1;
        } else if ($num1 > $num2) {
            return greatest_common_divisor($num1 - $num2, $num2);
        } else {
            return greatest_common_divisor($num1, $num2 - $num1);
        }
    } else { // if inputs are not positive integers, return -1
        return -1;
    }
}

/*
    Print result and show proof if two inputs are co primes of each other.
*/
function print_co_primes_result($num1, $num2)
{
    print_inputs($num1, $num2);
    $gcd = greatest_common_divisor($num1, $num2);
    are_co_primes($num1, $num2) === "true" ? print "<br>Yes, these inputs are co-primes." : print "<br>No, these inputs are not co-primes.";
    if ($gcd === -1) { // bad input case
        echo "<br>Proof: the concept of coprimes is only applied to positive integers.<br>";
    } else {
        echo "<br>Proof: greatest common divisor of $num1 and $num2 is $gcd<br>";
    }
}

/*
    Print inputs
*/
function print_inputs($num1, $num2)
{
    if (is_bool($num1)) {
        $num1 = convert_boolean_to_str($num1);
    }
    if (is_bool($num2)) {
        $num2 = convert_boolean_to_str($num2);
    }
    echo "<br> Inputs are: $num1 and $num2";

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
function print_test_result($input1, $input2, $expected_output, $actual_output)
{
    print_test_prompt($input1, $input2, $expected_output);
    if ($actual_output === $expected_output) {
        echo "-- Yes. test passed<br>";
    } else {
        echo "-- No. test failed<br>";
    }
}

/*

*/
function print_test_prompt($num1, $num2, $expected_output) {
    if (is_bool($num1)) {
        $num1 = convert_boolean_to_str($num1);
    }
    if (is_bool($num2)) {
        $num2 = convert_boolean_to_str($num2);
    }
    echo "<br>is output from are_co_primes($num1, $num2) equal to $expected_output?";
}

/*
    Convert boolean to string
*/
function convert_boolean_to_str($value)
{
    return $value ? "true" : "false";
}

/*
    Test multiple scenarios of are_co_primes
*/
function tester_function()
{
    # Test 1: good inputs - positive numbers
    $num_1a = 2;
    $num_1b = 7;

    # Print inputs
    print_inputs($num_1a, $num_1b);

    #Actual output
    $actual_output_1 = are_co_primes($num_1a, $num_1b);
    print_actual_output($actual_output_1);

    #Expected output
    $expected_output_1 = "true";
    print_expected_output($expected_output_1);

    # Print test 1 result
    print_test_result($num_1a, $num_1b, $expected_output_1, $actual_output_1);


    # Test 2: bad inputs - negative number or float number
    $num_2a = -2;
    $num_2b = 7.6;

    # Print inputs
    print_inputs($num_2a, $num_2b);

    #Actual output
    $actual_output_2 = are_co_primes($num_2a, $num_2b);
    print_actual_output($actual_output_2);

    #Expected output
    $expected_output_2 = "false";
    print_expected_output($expected_output_2);

    # Print test 2 result
    print_test_result($num_2a, $num_2b, $expected_output_2, $actual_output_2);

    # Test 3: ugly inputs - wrong type
    $num_3a = true;
    $num_3b = 7;

    # Print inputs
    print_inputs($num_3a, $num_3b);

    #Actual output
    $actual_output_3 = are_co_primes($num_3a, $num_3b);
    print_actual_output($actual_output_3);

    #Expected output
    $expected_output_3 = "false";
    print_expected_output($expected_output_3);

    # Print test 3 result
    print_test_result($num_3a, $num_3b, $expected_output_3, $actual_output_3);
}

/*
    main fuction for this program
*/
function main()
{
    // Check and print result if two inputs are co-primes
    print_co_primes_result(2, 3);
    print_co_primes_result(2, 4);
    print_co_primes_result(-8, 7);
    print_co_primes_result(8, 7.6);
    print_co_primes_result(3, "abc");
    print_co_primes_result(false, 7);

    // Call tester function to test the are_co_primes() function
    tester_function();
}

main();
?>