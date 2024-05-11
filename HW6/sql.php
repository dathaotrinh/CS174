<?php

class SQL
{
    private const ERROR_MESSAGE = "An error has occured."; // Generic error message
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

    // Insert new user into the database
    public function insert_user($name, $studentId, $email, $password)
    {
        return $this->execute_insert_user($name, $studentId, $email, $password);
    }

    // Insert new account into the database
    private function insert_user_query($name, $studentId, $email, $password, $advisorId)
    {
        return "INSERT INTO user VALUES ('$email', '$studentId', '$password', '$name', '$advisorId')";
    }

    private function execute_insert_user($name, $studentId, $email, $password)
    {
        // sanitize input
        $email = $this->sanitize_mysql($email);
        $password = $this->sanitize_mysql($password);
        $name = $this->sanitize_mysql($name);
        $studentId = $this->sanitize_mysql($studentId);

        // hash the password
        $password = $this->hash_password($password);
        // assign an advisor to the student
        $advisorId = $this->assign_advisor($studentId);
        if ($advisorId) {
            // store in db
            $query = $this->insert_user_query($name, $studentId, $email, $password, $advisorId);
            try {
                // perform query on the db
                $result = $this->get_conn()->query($query);
                return $result;
            } catch (Exception) {
                return false; // server issue
            }
        }
        return false;
    }

    // Fetch all advisors query
    private function get_advisors_query()
    {
        return "SELECT * FROM advisor";
    }

    private function execute_get_advisors()
    {
        $query = $this->get_advisors_query();
        try {
            // perform query on the db
            $result = $this->get_conn()->query($query);
            return $result;
        } catch (Exception) {
            return false; // server issue
        }
    }

    private function assign_advisor($studentId)
    {
        $studentId = $this->sanitize_mysql($studentId);
        // get the last 2 digits of student id
        $lastTwoDigits = intval(substr($studentId, -2));
        // fetch all advisors information
        $result = $this->execute_get_advisors();
        $advisorId = "";
        if ($result) {
            $rows = $result->num_rows;
            for ($cursor = 0; $cursor < $rows; $cursor++) {
                $result->data_seek($cursor);
                $row = $result->fetch_array(MYSQLI_ASSOC);
                // check if the last 2 digits of the student id
                // belongs to an advisor
                if (
                    $lastTwoDigits <= intval($row['stuIdUpperBound'])
                    && $lastTwoDigits >= intval($row['stuIdLowerBound'])
                ) {
                    // get advisor id
                    $advisorId = $row['id'];
                    break;
                }
            }
            // deallocate the result
            $result->close();
            return $advisorId;
        }

        return false;
    }
    // Fetch one user in the database
    public function get_user($studentId, $password)
    {
        return $this->execute_get_user($studentId, $password);
    }

    // Fetch one user query
    private function get_user_query($studentId)
    {
        return "SELECT * FROM user WHERE studentId='$studentId'";
    }

    private function execute_get_user($studentId, $password)
    {
        $query = $this->get_user_query($studentId);
        // perform query on the db
        try {
            $result = $this->get_conn()->query($query);
            $doesUserExist = false;
            if ($result->num_rows > 0) {
                $result->data_seek(0);
                $row = $result->fetch_array(MYSQLI_ASSOC);
                // make sure the passwords matched
                if (password_verify($password, $row['password'])) {
                    $doesUserExist = true;
                }
            }
            // deallocate result
            $result->close();
            return $doesUserExist;
        } catch (Exception) {
            return false; // server issue
        }
    }

    // Function to hash password
    private function hash_password($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
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

    // join query to lookup advisor
    private function lookup_advisor_query($studentId, $studentName)
    {
        return "SELECT a.id, a.name, a.phone, a.stuIdLowerBound, a.stuIdUpperBound from
                user AS u
                INNER JOIN
                advisor AS a
                ON u.advisorId = a.id
                WHERE u.studentId = '$studentId' AND u.name = '$studentName'";
    }

    private function execute_lookup_advisor($studentId, $studentName)
    {
        $query = $this->lookup_advisor_query($studentId, $studentName);
        try {
            $result = $this->get_conn()->query($query);
            return $result;
        } catch (Exception) {
            return false; // server issue
        }
    }
    // Print all info of an advisor if found
    public function lookup_advisor($studentId, $studentName)
    {
        $studentName = trim($studentName);
        $result = $this->execute_lookup_advisor($studentId, $studentName);
        if ($result) {
            $rows = $result->num_rows;

            // only show advisor if there is any
            if ($rows > 0) {
                echo "
                <table>
                <tr>
                    <th>Advisor Id</th>
                    <th>Advisor Name</th>
                    <th>Advisor Phone</th>
                    <th>Lower Bound</th>
                    <th>Upper Bound</th>
                </tr>";

                // Iterate through each row, column in the db
                // and print the value
                for ($cursor = 0; $cursor < $rows; $cursor++) {
                    $result->data_seek($cursor);
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    echo "<tr>";
                    echo "<td>{$row['id']}</td><td>{$row['name']}</td><td>{$row['phone']}</td><td>{$row['stuIdLowerBound']}</td><td>{$row['stuIdUpperBound']}</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }

            $result->close(); // deallocate the result
            return $rows === 0 ? false : true;
        }
        return false;
    }
}

?>