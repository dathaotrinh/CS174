<?php

class SQL
{
    // Different options to call the db
    private const INSERT_ENUM = "INSERT";
    private const GET_ONE_USER_ENUM = "GET_ONE_USER";
    private const GET_ALL_THREADS_BY_ID_ENUM = "GET_ALL_THREADS_BY_ID";
    private const USER_TABLE_ENUM = "user";
    private const THREAD_TABLE_ENUM = "thread";
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
    public function insert_user($name, $username, $password)
    {
        return $this->execute(self::INSERT_ENUM, $name, $username, $password, "", "", "", self::USER_TABLE_ENUM);
    }

    // Insert new account into the database
    private function insert_user_query($name, $username, $password)
    {
        // $temp_id is a placeholder for userid
        // database has userid set to be auto incremented
        // so the database will override this value
        $temp_id = 0;
        return "INSERT INTO user VALUES ($temp_id, '$username', '$password', '$name')";
    }

    // Fetch user in the database
    public function get_user($username, $password)
    {
        return $this->execute(self::GET_ONE_USER_ENUM, "", $username, $password, "", "", "", self::USER_TABLE_ENUM);
    }

    // Fetch one user query
    private function get_user_query($username)
    {
        return "SELECT * FROM user WHERE username='$username'";
    }

    // Query to fetch all threads associated with an user
    private function get_all_threads_by_id_query($userid)
    {
        // sort the threads in newest to oldest order.
        return "SELECT * FROM thread WHERE user_id='$userid' ORDER BY id DESC";
    }

    // Fetch all research papers associated with an user
    private function get_all_threads_by_id($userid)
    {
        return $this->execute(self::GET_ALL_THREADS_BY_ID_ENUM, "", "", "", "", "", $userid, self::THREAD_TABLE_ENUM);
    }

    // Insert new thread into the database
    public function insert_thread($thread_name, $file_content, $userid)
    {
        return $this->execute(self::INSERT_ENUM, "", "", "", $thread_name, $file_content, $userid, self::THREAD_TABLE_ENUM);
    }

    // Query to insert new thread into the database
    private function insert_thread_query($thread_name, $file_content, $userid)
    {
        // $temp_id is a placeholder for thread id
        // database has thread id set to be auto incremented
        // so the database will override this value
        $temp_id = 0;
        return "INSERT INTO thread VALUES ('$thread_name', '$file_content', $temp_id, '$userid')";
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

    /*
    This function is used to choose the correct query and execute the query
    */
    private function execute(
        $option,
        $name = "",
        $username = "",
        $password = "",
        $thread_name = "",
        $file_content = "",
        $userid = "",
        $table = ""
    ) {
        $query = "";

        // if working on thread table
        if ($table === self::THREAD_TABLE_ENUM) {
            // sanitize input
            $file_content = $this->sanitize_mysql($file_content);
            $thread_name = $this->sanitize_mysql($thread_name);
            $userid = $this->sanitize_mysql($userid);

            // insert new thread
            if ($option === self::INSERT_ENUM) {
                $query = $this->insert_thread_query($thread_name, $file_content, $userid);
            } else if ($option === self::GET_ALL_THREADS_BY_ID_ENUM) { // fetch all threads associted with an user
                $query = $this->get_all_threads_by_id_query($userid);
            }

        } else { // if working on user table
            // sanitize input
            $username = $this->sanitize_mysql($username);
            $password = $this->sanitize_mysql($password);
            $name = $this->sanitize_mysql($name);

            // insert new user
            if ($option === self::INSERT_ENUM) {
                // hash the password
                $password = $this->hash_password($password);
                // store in db
                $query = $this->insert_user_query($name, $username, $password);
            } else if ($option === self::GET_ONE_USER_ENUM) { // fetch an instance associated with a given username            
                $query = $this->get_user_query($username);
            }
        }

        try {
            // perform query on the db
            $result = $this->get_conn()->query($query);

            if (!$result) {
                return 0; // unsuccessful query, no result
            }

            return $result; // return result if success
        } catch (Exception) {
            return -1; // server issue
        }

    }

    // Print all threads in the database
    // expand option does not show by default
    public function print_threads($userid, $expand = 0)
    {
        // fetch all accounts
        $result = $this->get_all_threads_by_id($userid);

        if ($result === 0 || $result === -1) {
            return; // nothing shown if there is no thread or server has any issue
        }

        // get all threads associated with one user
        $rows = $result->num_rows;

        // only show threads if there is any
        if ($rows > 0) {
            echo "
            <table>
            <tr>
                <th>Thread Name</th>
                <th>File Content</th>
            </tr>";

            // Iterate through each row, column in the db
            // and print the value
            for ($cursor = 0; $cursor < $rows; $cursor++) {
                $result->data_seek($cursor);
                $row = $result->fetch_array(MYSQLI_ASSOC);
                echo "<tr>";
                echo "<td>{$row['thread_name']}</td>";

                // if need expand, perform word wrapping with 75 characters per line
                if ($expand === 1) {
                    echo "<td>" . wordwrap($this->replace_line_break_with_html_tag($row['file_content']), 75, "<br>", true) . "</td>";
                } else { // if not expand, only show the first 75 characters (include ...) of the file 
                    echo "<td>" . mb_strimwidth($this->replace_line_break_with_html_tag($row['file_content']), 0, 75, "...") . "</td>";
                }

                echo "</tr>";
            }
            echo "</table>";
            echo "
            <form method='post' action='thread.php' enctype='multipart/form-data'>
            <input type='submit' value='Expand All' name='expand'>
            </form>";
        }

        $result->close(); // deallocate the result
    }

    private function replace_line_break_with_html_tag($str)
    {
        return str_replace("/n", "<br>", $str);
    }
}

?>