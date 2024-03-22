<?php
class Matrix
{
    private const MATRIX_SIZE = 20; // we only accept 20x20 matrix
    private const ADJACENT_NUMBER_SIZE = 5; // we only want 5 adjacent numbers for largest product
    private $matrix;
    private $largest_product;
    private $sum_of_factorial;
    private $largest_product_elements;
    private $largest_product_arrs;

    // Matrix constructor
    function __construct($file_content)
    {
        $this->matrix = array(array());
        $this->build_matrix($file_content);
        $this->largest_product = 0;
        $this->sum_of_factorial = 0;
        $this->largest_product_elements = array();
        $this->largest_product_arrs = array();
    }

    /*
    Get largest product of 5 adjacent numbers in a matrix
    */
    public function get_largest_product()
    {
        return $this->largest_product;
    }

    /*
    Get sum of factorial of the largest product in a matrix
    */
    public function get_sum_of_factorial()
    {
        return $this->sum_of_factorial;
    }

    /*
    Set matrix value at a specific row and column
    */
    private function setValue($row, $column, $value)
    {
        $this->matrix[$row][$column] = $value;
    }

    /*
    Get matrix value at a specific row and column
    */
    private function getValue($row, $column)
    {
        return $this->matrix[$row][$column];
    }

    /*
    Build matrix from file content
    */
    private function build_matrix($file_content)
    {
        // Split file content into tokens
        $tokens = str_split($file_content);
        $token_index = 0;
        // Iterate through each row and col
        // and assign value to that position
        for ($row = 0; $row < self::MATRIX_SIZE; $row++) {
            for ($col = 0; $col < self::MATRIX_SIZE; $col++) {
                $current_token = $tokens[$token_index];
                $token_index++;
                // check if the current token is a number
                if (is_numeric($current_token)) {
                    $this->setValue($row, $col, (int) $current_token);
                } else {
                    $this->setValue($row, $col, 0); // if not number, replace it with 0
                }

            }
        }
    }

    /*
    Calculate largest product in the matrix horizontally
    */
    private function calculate_largest_product_horizontally()
    {
        for ($row = 0; $row < self::MATRIX_SIZE; $row++) {
            for ($col = 0; $col <= self::MATRIX_SIZE - self::ADJACENT_NUMBER_SIZE; $col++) {
                $product_temp = 1;
                $product_element_temp = array();
                $product_element_arr = array();
                for ($index = 0; $index < 5; $index++) {
                    $matrix_value = $this->getValue($row, $col + $index);
                    $product_temp *= $matrix_value;
                    array_push($product_element_temp, $matrix_value);
                    $r = $row;
                    $c = $col + $index;
                    $location = "$r" . " $c";
                    array_push($product_element_arr, $location); 
                    
                }
                if ($product_temp > $this->largest_product) {
                    $this->largest_product = $product_temp;
                    $this->largest_product_elements = $product_element_temp;
                    $this->largest_product_arrs = $product_element_arr;
                }
            }
        }
    }

    /*
    Calculate largest product in the matrix vertically
    */
    private function calculate_largest_product_vertically()
    {
        for ($row = 0; $row <= self::MATRIX_SIZE - self::ADJACENT_NUMBER_SIZE; $row++) {
            for ($col = 0; $col < self::MATRIX_SIZE; $col++) {
                $product_temp = 1;
                $product_element_temp = array();
                $product_element_arr = array();
                for ($index = 0; $index < 5; $index++) {
                    $matrix_value = $this->getValue($row + $index, $col);
                    $product_temp *= $matrix_value;
                    array_push($product_element_temp, $matrix_value);
                    $r = $row + $index;
                    $c = $col;
                    $location = "$r" . " $c";
                    array_push($product_element_arr, $location); 
                }
                if ($product_temp > $this->largest_product) {
                    $this->largest_product = $product_temp;
                    $this->largest_product_elements = $product_element_temp;
                    $this->largest_product_arrs = $product_element_arr;
                }
            }
        }
    }

    /*
    Calculate largest product in the matrix diagonally (left -> right)
    */
    private function calculate_largest_product_left_diagonally()
    {
        for ($row = 0; $row <= self::MATRIX_SIZE - self::ADJACENT_NUMBER_SIZE; $row++) {
            for ($col = 0; $col <= self::MATRIX_SIZE - self::ADJACENT_NUMBER_SIZE; $col++) {
                $product_temp = 1;
                $product_element_temp = array();
                $product_element_arr = array();
                for ($index = 0; $index < 5; $index++) {
                    $matrix_value = $this->getValue($row + $index, $col + $index);
                    $product_temp *= $matrix_value;
                    array_push($product_element_temp, $matrix_value);
                    $r = $row + $index;
                    $c = $col + $index;
                    $location = "$r" . " $c";
                    array_push($product_element_arr, $location);    
                }
                if ($product_temp > $this->largest_product) {
                    $this->largest_product = $product_temp;
                    $this->largest_product_elements = $product_element_temp;
                    $this->largest_product_arrs = $product_element_arr;
                }
            }
        }
    }

    /*
    Calculate largest product in the matrix diagonally (right -> left)
    */
    private function calculate_largest_product_right_diagonally()
    {
        for ($row = self::ADJACENT_NUMBER_SIZE - 1; $row < self::MATRIX_SIZE; $row++) {
            for ($col = 0; $col <= self::MATRIX_SIZE - self::ADJACENT_NUMBER_SIZE; $col++) {
                $product_temp = 1;
                $product_element_temp = array();
                $product_element_arr = array();
                for ($index = 0; $index < 5; $index++) {
                    $matrix_value = $this->getValue($row - $index, $col + $index);
                    $product_temp *= $matrix_value;
                    array_push($product_element_temp, $matrix_value);
                    $r = $row - $index;
                    $c = $col + $index;
                    $location = "$r" . " $c";
                    array_push($product_element_arr, $location);                   
                }
                if ($product_temp > $this->largest_product) {
                    $this->largest_product = $product_temp;
                    $this->largest_product_elements = $product_element_temp;
                    $this->largest_product_arrs = $product_element_arr;
                }
            }
        }
    }

    /*
    Calculate largest product in the matrix
    */
    public function calculate_largest_product()
    {
        $this->calculate_largest_product_horizontally();
        $this->calculate_largest_product_vertically();
        $this->calculate_largest_product_left_diagonally();
        $this->calculate_largest_product_right_diagonally();
    }

    /*
    Calculate sum of factorial in the matrix
    */
    public function calculate_sum_of_factorial()
    {
        // Split the largest product into tokens
        $largest_product_tokens = str_split($this->largest_product);

        // Iterate through each token to calculate sum of factorials
        foreach ($largest_product_tokens as $element) {
            $this->sum_of_factorial += $this->factorial((int) $element);
        }
    }

    /*
    Calculate factorial of a number
    */
    private function factorial($number)
    {
        if ($number <= 1) {
            return 1;
        } else {
            return $this->factorial($number - 1) * $number;
        }
    }

    /*
    Print matrix
    */
    public function print_original_matrix()
    {
        echo "<br>Original Matrix<br>";
        // Iterate through each row and col
        // and assign value to that position
        for ($row = 0; $row < self::MATRIX_SIZE; $row++) {
            for ($col = 0; $col < self::MATRIX_SIZE; $col++) {
                echo "" . $this->getValue($row, $col) . " ";
            }
            echo "<br>";
        }
        echo "<br>";
    }

    /*
    Print matrix with 
    */
    public function print_matrix_with_color()
    {
        echo "<br>Colored Matrix<br>";
        // Iterate through each row and col
        // and assign value to that position
        for ($row = 0; $row < self::MATRIX_SIZE; $row++) {
            for ($col = 0; $col < self::MATRIX_SIZE; $col++) {
                $location = "$row" . " $col";
                $val = $this->getValue($row, $col);
                if (in_array($location, $this->largest_product_arrs)) {
                    echo "<span style='color:red;'>$val</span>" . " ";
                } else {
                    echo "" . $val . " ";
                }
            }
            echo "<br>";
        }
        echo "<br>";
    }

    /*
    Print largest product elements
    */
    public function print_largest_product_elements()
    {
        $temp = "Largest product: ";
        // Iterate through each element in the list
        for ($index = 0; $index < count($this->largest_product_elements); $index++) {
            $temp .= $this->largest_product_elements[$index];
            if ($index < self::ADJACENT_NUMBER_SIZE - 1) {
                $temp .= " * ";
            } else {
                $temp .= " = ";
            }
        }

        $temp .= "$this->largest_product";
        echo $temp;
        echo "<br>";
    }

    /*
    Print sum of factorial
    */
    public function print_sum_of_factorial()
    {
        $temp = "Sum of factorial: ";

        // Split the largest product into tokens
        $largest_product_tokens = str_split($this->largest_product);
        $tokens_len = count($largest_product_tokens);
        // Iterate through each element in the list
        for ($index = 0; $index < $tokens_len; $index++) {
            $temp .= $largest_product_tokens[$index];
            if ($index < $tokens_len - 1) {
                $temp .= "! + ";
            } else {
                $temp .= "! ";
            }
        }

        $temp .= "= $this->sum_of_factorial";
        echo $temp;
        echo "<br>";
    }
}
?>