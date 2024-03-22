<?php
require_once "lru.php";
class LRU_Tester
{
    /*
        Print input
    */
    private static function print_input($key, $value, $reset)
    {
        $reset = LRU_Tester::convert_boolean_to_str($reset);
        echo "<br>Input: {$key} {$value} {$reset}";
    }

    /*
    Print actual output for size
    */
    private static function print_actual_output_size($size)
    {
        echo "<br>Actual output for size: {$size}";
    }

    /*
    Print actual output for cache list
    */
    private static function print_actual_output_cache_list($cache_list)
    {
        echo "<br>Actual output for cache list: {$cache_list}";
    }

    /*
Print expected output for cache list
*/
    private static function print_expected_output_cache_list($cache_list)
    {
        echo "<br>Expected output for cache list: {$cache_list}";
    }


    /*
        Print actual output
    */
    private static function print_actual_output($actual_output)
    {
        echo "<br>Actual output: {$actual_output}";
    }

    /*
    Print expected output for size
    */
    private static function print_expected_output_size($size)
    {
        echo "<br>Expected output for size: {$size}";
    }

    /*
        Print expected output
    */
    private static function print_expected_output($expected_output)
    {
        echo "<br>Expected output: {$expected_output}";
    }

    /*
        Print test result for put
    */
    private static function print_test_result($input_key, $input_value, $input_reset, $expected_output, $actual_output)
    {
        $input_reset = LRU_Tester::convert_boolean_to_str($input_reset);
        echo "<br>is cache after calling put({$input_key}, {$input_value}, {$input_reset}) equal to `{$expected_output}`?";
        if ($actual_output === $expected_output) {
            echo "<br>--> Yes. test passed<br>";
        } else {
            echo "<br>--> No. test failed<br>";
        }
    }

    /*
        Print test result for cache size
    */
    private static function print_test_result_for_size($input_key, $input_value, $input_reset, $expected_output, $actual_output)
    {
        $input_reset = LRU_Tester::convert_boolean_to_str($input_reset);
        echo "<br>is cache size after calling put({$input_key}, {$input_value}, {$input_reset}) equal to {$expected_output}?";
        if ($actual_output === $expected_output) {
            echo "<br>--> Yes. test passed";
        } else {
            echo "<br>--> No. test failed";
        }
    }

    /*
        Print test result for cache list
    */
    private static function print_test_result_for_cache_list($input_key, $input_value, $input_reset, $expected_output, $actual_output)
    {
        $input_reset = LRU_Tester::convert_boolean_to_str($input_reset);
        echo "<br>is cache list after calling put({$input_key}, {$input_value}, {$input_reset}) equal to {$expected_output}?";
        if ($actual_output === $expected_output) {
            echo "<br>--> Yes. test passed";
        } else {
            echo "<br>--> No. test failed";
        }
    }

    /*
        Convert boolean to string
    */
    private static function convert_boolean_to_str($value)
    {
        return $value ? "true" : "false";
    }

    /*
        Tester function
    */
    public static function tester_function()
    {
        echo "<br><b>Below is for testing</b><br>";
        // build new lru cache with maximum capacity of 2
        $cache_size = 2;
        $lru_cache = new LRUCache($cache_size);
        echo "construct a LRU Cache with a maximum capacity of {$cache_size}<br>";

        // Test case 1: good case - positive value
        $input_key_1 = 1;
        $input_value_1 = 1;
        $input_reset_1 = false;
        LRU_Tester::print_input($input_key_1, $input_value_1, $input_reset_1);


        // actual output 1
        $actual_output_1 = $lru_cache->put($input_key_1, $input_value_1, $input_reset_1);
        LRU_Tester::print_actual_output($actual_output_1);

        // expected output 1
        $expected_output_1 = "adding new key 1";
        LRU_Tester::print_expected_output($expected_output_1);

        // actual output 1 for size
        $actual_size_output_1 = $lru_cache->get_size();
        LRU_Tester::print_actual_output_size($actual_size_output_1);

        // expected output 1 for size
        $expected_size_output_1 = 1;
        LRU_Tester::print_expected_output_size($expected_size_output_1);

        // actual output 1 for cache list
        $actual_cache_list_output_1 = $lru_cache->print_current_cache();
        LRU_Tester::print_actual_output_cache_list($actual_cache_list_output_1);

        // expected output 1 for cache list
        $expected_cache_list_output_1 = "{1: 1}";
        LRU_Tester::print_expected_output_cache_list($expected_cache_list_output_1);

        LRU_Tester::print_test_result_for_cache_list($input_key_1, $input_value_1, $input_reset_1, $expected_cache_list_output_1, $actual_cache_list_output_1);
        LRU_Tester::print_test_result_for_size($input_key_1, $input_value_1, $input_reset_1, $expected_size_output_1, $actual_size_output_1);
        LRU_Tester::print_test_result($input_key_1, $input_value_1, $input_reset_1, $expected_output_1, $actual_output_1);

        // Test case 2: bad case - negative value
        $input_key_2 = 1;
        $input_value_2 = -1;
        $input_reset_2 = false;
        LRU_Tester::print_input($input_key_2, $input_value_2, $input_reset_2);

        // actual output 2
        $actual_output_2 = $lru_cache->put($input_key_2, $input_value_2, $input_reset_2);
        LRU_Tester::print_actual_output($actual_output_2);

        // expected output 2
        $expected_output_2 = "Not accept negative value";
        LRU_Tester::print_expected_output($expected_output_2);

        // actual output 2 for size
        $actual_size_output_2 = $lru_cache->get_size();
        LRU_Tester::print_actual_output_size($actual_size_output_2);

        // expected output 2 for size
        $expected_size_output_2 = 1;
        LRU_Tester::print_expected_output_size($expected_size_output_2);

        // actual output 2 for cache list
        $actual_cache_list_output_2 = $lru_cache->print_current_cache();
        LRU_Tester::print_actual_output_cache_list($actual_cache_list_output_2);

        // expected output 2 for cache list
        $expected_cache_list_output_2 = "{1: 1}";
        LRU_Tester::print_expected_output_cache_list($expected_cache_list_output_2);

        LRU_Tester::print_test_result_for_cache_list($input_key_2, $input_value_2, $input_reset_2, $expected_cache_list_output_2, $actual_cache_list_output_2);
        LRU_Tester::print_test_result_for_size($input_key_2, $input_value_2, $input_reset_2, $expected_size_output_2, $actual_size_output_2);
        LRU_Tester::print_test_result($input_key_2, $input_value_2, $input_reset_2, $expected_output_2, $actual_output_2);

        // Test case 3: ugly case - string value
        $input_key_3 = 1;
        $input_value_3 = "abc";
        $input_reset_3 = true;
        LRU_Tester::print_input($input_key_3, $input_value_3, $input_reset_3);

        // actual output 3
        $actual_output_3 = $lru_cache->put($input_key_3, $input_value_3, $input_reset_3);
        LRU_Tester::print_actual_output($actual_output_3);

        // expected output 3
        $expected_output_3 = "Not accept string value";
        LRU_Tester::print_expected_output($expected_output_3);

        // actual output 3 for size
        $actual_size_output_3 = $lru_cache->get_size();
        LRU_Tester::print_actual_output_size($actual_size_output_3);

        // expected output 3 for size
        $expected_size_output_3 = 1;
        LRU_Tester::print_expected_output_size($expected_size_output_3);

        // actual output 3 for cache list
        $actual_cache_list_output_3 = $lru_cache->print_current_cache();
        LRU_Tester::print_actual_output_cache_list($actual_cache_list_output_3);

        // expected output 3 for cache list
        $expected_cache_list_output_3 = "{1: 1}";
        LRU_Tester::print_expected_output_cache_list($expected_cache_list_output_3);

        LRU_Tester::print_test_result_for_cache_list($input_key_3, $input_value_3, $input_reset_3, $expected_cache_list_output_3, $actual_cache_list_output_3);
        LRU_Tester::print_test_result_for_size($input_key_3, $input_value_3, $input_reset_3, $expected_size_output_3, $actual_size_output_3);
        LRU_Tester::print_test_result($input_key_3, $input_value_3, $input_reset_3, $expected_output_3, $actual_output_3);

        // Test case 4: good case - negative key
        $input_key_4 = -1;
        $input_value_4 = 1;
        $input_reset_4 = false;
        LRU_Tester::print_input($input_key_4, $input_value_4, $input_reset_4);

        // actual output 4
        $actual_output_4 = $lru_cache->put($input_key_4, $input_value_4, $input_reset_4);
        LRU_Tester::print_actual_output($actual_output_4);

        // expected output 4
        $expected_output_4 = "adding new key -1";
        LRU_Tester::print_expected_output($expected_output_4);

        // actual output 4 for size
        $actual_size_output_4 = $lru_cache->get_size();
        LRU_Tester::print_actual_output_size($actual_size_output_4);

        // expected output 4 for size
        $expected_size_output_4 = 2;
        LRU_Tester::print_expected_output_size($expected_size_output_4);

        // actual output 4 for cache list
        $actual_cache_list_output_4 = $lru_cache->print_current_cache();
        LRU_Tester::print_actual_output_cache_list($actual_cache_list_output_4);

        // expected output 4 for cache list
        $expected_cache_list_output_4 = "{-1: 1, 1: 1}";
        LRU_Tester::print_expected_output_cache_list($expected_cache_list_output_4);

        LRU_Tester::print_test_result_for_cache_list($input_key_4, $input_value_4, $input_reset_4, $expected_cache_list_output_4, $actual_cache_list_output_4);
        LRU_Tester::print_test_result_for_size($input_key_4, $input_value_4, $input_reset_4, $expected_size_output_4, $actual_size_output_4);
        LRU_Tester::print_test_result($input_key_4, $input_value_4, $input_reset_4, $expected_output_4, $actual_output_4);

        // Test case 5: good case - float key, evict case
        $input_key_5 = 2.3;
        $input_value_5 = 1;
        $input_reset_5 = false;
        LRU_Tester::print_input($input_key_5, $input_value_5, $input_reset_5);

        // actual output 5
        $actual_output_5 = $lru_cache->put($input_key_5, $input_value_5, $input_reset_5);
        LRU_Tester::print_actual_output($actual_output_5);

        // expected output 5
        $expected_output_5 = "evict key 1";
        LRU_Tester::print_expected_output($expected_output_5);

        // actual output 5 for size
        $actual_size_output_5 = $lru_cache->get_size();
        LRU_Tester::print_actual_output_size($actual_size_output_5);

        // expected output 5 for size
        $expected_size_output_5 = 2;
        LRU_Tester::print_expected_output_size($expected_size_output_5);

        // actual output 5 for cache list
        $actual_cache_list_output_5 = $lru_cache->print_current_cache();
        LRU_Tester::print_actual_output_cache_list($actual_cache_list_output_5);

        // expected output 5 for cache list
        $expected_cache_list_output_5 = "{2.3: 1, -1: 1}";
        LRU_Tester::print_expected_output_cache_list($expected_cache_list_output_5);

        LRU_Tester::print_test_result_for_cache_list($input_key_5, $input_value_5, $input_reset_5, $expected_cache_list_output_5, $actual_cache_list_output_5);
        LRU_Tester::print_test_result_for_size($input_key_5, $input_value_5, $input_reset_5, $expected_size_output_5, $actual_size_output_5);
        LRU_Tester::print_test_result($input_key_5, $input_value_5, $input_reset_5, $expected_output_5, $actual_output_5);

        // Test case 6: good case - string key, evict case
        $input_key_6 = "abc";
        $input_value_6 = 1;
        $input_reset_6 = false;
        LRU_Tester::print_input($input_key_6, $input_value_6, $input_reset_6);

        // actual output 6
        $actual_output_6 = $lru_cache->put($input_key_6, $input_value_6, $input_reset_6);
        LRU_Tester::print_actual_output($actual_output_6);

        // expected output 6
        $expected_output_6 = "evict key -1";
        LRU_Tester::print_expected_output($expected_output_6);

        // actual output 6 for size
        $actual_size_output_6 = $lru_cache->get_size();
        LRU_Tester::print_actual_output_size($actual_size_output_6);

        // expected output 6 for size
        $expected_size_output_6 = 2;
        LRU_Tester::print_expected_output_size($expected_size_output_6);

        // actual output 6 for cache list
        $actual_cache_list_output_6 = $lru_cache->print_current_cache();
        LRU_Tester::print_actual_output_cache_list($actual_cache_list_output_6);

        // expected output 6 for cache list
        $expected_cache_list_output_6 = "{abc: 1, 2.3: 1}";
        LRU_Tester::print_expected_output_cache_list($expected_cache_list_output_6);

        LRU_Tester::print_test_result_for_cache_list($input_key_6, $input_value_6, $input_reset_6, $expected_cache_list_output_6, $actual_cache_list_output_6);
        LRU_Tester::print_test_result_for_size($input_key_6, $input_value_6, $input_reset_6, $expected_size_output_6, $actual_size_output_6);
        LRU_Tester::print_test_result($input_key_6, $input_value_6, $input_reset_6, $expected_output_6, $actual_output_6);

        // Test case 7: good case - string key, key already exists
        $input_key_7 = "abc";
        $input_value_7 = 2;
        $input_reset_7 = false;
        LRU_Tester::print_input($input_key_7, $input_value_7, $input_reset_7);

        // actual output 7
        $actual_output_7 = $lru_cache->put($input_key_7, $input_value_7, $input_reset_7);
        LRU_Tester::print_actual_output($actual_output_7);

        // expected output 7
        $expected_output_7 = "update key abc value";
        LRU_Tester::print_expected_output($expected_output_7);

        // actual output 7 for size
        $actual_size_output_7 = $lru_cache->get_size();
        LRU_Tester::print_actual_output_size($actual_size_output_7);

        // expected output 7 for size
        $expected_size_output_7 = 2;
        LRU_Tester::print_expected_output_size($expected_size_output_7);

        // actual output 7 for cache list
        $actual_cache_list_output_7 = $lru_cache->print_current_cache();
        LRU_Tester::print_actual_output_cache_list($actual_cache_list_output_7);

        // expected output 7 for cache list
        $expected_cache_list_output_7 = "{abc: 2, 2.3: 1}";
        LRU_Tester::print_expected_output_cache_list($expected_cache_list_output_7);

        LRU_Tester::print_test_result_for_cache_list($input_key_7, $input_value_7, $input_reset_7, $expected_cache_list_output_7, $actual_cache_list_output_7);
        LRU_Tester::print_test_result_for_size($input_key_7, $input_value_7, $input_reset_7, $expected_size_output_7, $actual_size_output_7);
        LRU_Tester::print_test_result($input_key_7, $input_value_7, $input_reset_7, $expected_output_7, $actual_output_7);


        // Test case 8: good case - reset cache
        $input_key_8 = "abc";
        $input_value_8 = 3;
        $input_reset_8 = true;
        LRU_Tester::print_input($input_key_8, $input_value_8, $input_reset_8);

        // actual output 8
        $actual_output_8 = $lru_cache->put($input_key_8, $input_value_8, $input_reset_8);
        LRU_Tester::print_actual_output($actual_output_8);

        // expected output 8
        $expected_output_8 = "reset cache";
        LRU_Tester::print_expected_output($expected_output_8);

        // actual output 8 for size
        $actual_size_output_8 = $lru_cache->get_size();
        LRU_Tester::print_actual_output_size($actual_size_output_8);

        // expected output 8 for size
        $expected_size_output_8 = 1;
        LRU_Tester::print_expected_output_size($expected_size_output_8);

        // actual output 8 for cache list
        $actual_cache_list_output_8 = $lru_cache->print_current_cache();
        LRU_Tester::print_actual_output_cache_list($actual_cache_list_output_8);

        // expected output 8 for cache list
        $expected_cache_list_output_8 = "{abc: 3}";
        LRU_Tester::print_expected_output_cache_list($expected_cache_list_output_8);

        LRU_Tester::print_test_result_for_cache_list($input_key_8, $input_value_8, $input_reset_8, $expected_cache_list_output_8, $actual_cache_list_output_8);
        LRU_Tester::print_test_result_for_size($input_key_8, $input_value_8, $input_reset_8, $expected_size_output_8, $actual_size_output_8);
        LRU_Tester::print_test_result($input_key_8, $input_value_8, $input_reset_8, $expected_output_8, $actual_output_8);
    }
}
?>