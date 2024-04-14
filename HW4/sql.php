<?php

class SQL
{
    // Different options to call the db
    private const INSERT_ENUM = "INSERT";
    private const UPDATE_ENUM = "UPDATE";
    private const GET_ONE_ENUM = "GET_ONE";
    private const GET_ALL_ENUM = "GET_ALL";
    private const ERROR_MESSAGE = "An error has occured."; // Generic error message
    private const NUMBER_OF_COLS = 2; // Number of columns in this db
    private $conn;

    /*
    Construct new database
    */
    public function __construct($hn, $un, $pw, $db)
    {
        try {
            $this->conn = new mysqli($hn, $un, $pw, $db);
            if ($this->conn->connect_error) {
                die(self::ERROR_MESSAGE);
            }
        } catch (Exception $e) {
            die(self::ERROR_MESSAGE);
        }
    }

    // Close connection once done
    public function __destruct()
    {
        $this->close_conn();
    }


    private function get_conn()
    {
        return $this->conn;
    }

    /*
    Calculate new balance for an account given the account's email
    */
    public function get_balance($email, $balance)
    {
        // get the instance associated with the given email
        $result = $this->query(self::GET_ONE_ENUM, $email, $balance);
        // if there is at least an instance
        if ($result->num_rows > 0) {
            // update its balance
            $this->update($email, $balance, $result);
        } else {
            // insert new email and balance if no instance found
            $this->insert($email, $balance);
        }
        // deallocate the result
        $result->close();
    }

    // Insert new account into the database
    private function insert($email, $balance)
    {
        return $this->query(self::INSERT_ENUM, $email, $balance);
    }

    // Update the current balance of an account
    private function update($email, $balance, $instance)
    {
        return $this->query(self::UPDATE_ENUM, $email, $balance, $instance);
    }

    // Close database connection
    private function close_conn()
    {
        $this->get_conn()->close();
    }

    // Sanitize string
    private function sanitize_string($string)
    {
        $string = stripslashes($string);
        $string = strip_tags($string);
        $string = htmlentities($string);
        return $string;
    }

    // Sanitize mysql
    private function sanitize_mysql($string)
    {
        $string = $this->get_conn()->real_escape_string($string);
        $string = $this->sanitize_string($string);
        return $string;
    }

    // Fetch one account
    private function get_one_query($email)
    {
        return "SELECT * FROM Bank WHERE email='$email'";
    }

    // Insert new account into the database
    private function insert_query($email, $balance)
    {
        return "INSERT INTO Bank VALUES ('$email', '$balance')";
    }

    // Update the balance
    private function update_query($email, $balance)
    {
        return "UPDATE Bank SET balance='$balance' WHERE email='$email'";
    }

    // Fetch all accounts in the database
    private function get_all_query()
    {
        return "SELECT * FROM Bank";
    }

    /*
    This function is used to choose the correct query to run
    email and balance have a default value of an empty string
    instance is set to null by default
    */
    private function query($option, $email = "", $balance = "", $instance = null)
    {
        // sanitize email and balance
        $email = $this->sanitize_mysql($email);
        $balance = $this->sanitize_mysql($balance);
        $query = "";

        // update the balance
        if ($option === self::UPDATE_ENUM) {
            // point the cursor to the first row
            $instance->data_seek(0);
            // fetch array in associative array format
            $row = $instance->fetch_array(MYSQLI_ASSOC);
            // calculate new balance
            $balance = floatVal($row['balance']) + floatVal($balance);
            // sanitize the balance again (just to be sure)
            $balance = $this->sanitize_mysql($balance);
            // generate update query
            $query = $this->update_query($email, $balance);
        } else if ($option === self::INSERT_ENUM) { // insert if the email is not in the db
            $query = $this->insert_query($email, $balance);
        } else if ($option === self::GET_ONE_ENUM) { // fetch an instance associated with a given email
            $query = $this->get_one_query($email);
        } else {
            $query = $this->get_all_query(); // fetch all instances in the db
        }

        // perform query on the db
        $result = $this->get_conn()->query($query);

        // if issue occurred
        // send a generic error message
        if (!$result) {
            echo (self::ERROR_MESSAGE);
        }
        return $result;
    }

    // Print all accounts in the database
    public function print_data()
    {
        // fetch all accounts
        $result = $this->query(self::GET_ALL_ENUM);
        $rows = $result->num_rows;
        echo "<table><tr> <th>Email</th><th>Balance</th></tr>";
        // Iterate through each row, column in the db
        // and print the value
        for ($cursor = 0; $cursor < $rows; ++$cursor) {
            $result->data_seek($cursor);
            $row = $result->fetch_array(MYSQLI_NUM);
            echo "<tr>";
            for ($col = 0; $col < self::NUMBER_OF_COLS; ++$col)
                if ($col === 1) { // balance column
                    if ($row[$col] < 0) {
                        echo ("<td>" . "-$" . -$row[$col] . "</td>");
                    } else {
                        echo ("<td>" . "$" . $row[$col] . "</td>");
                    }
                } else { // email column
                    echo "<td>$row[$col]</td>";
                }
            echo "</tr>";
        }
        echo "</table>";
        $result->close();
    }
}

?>