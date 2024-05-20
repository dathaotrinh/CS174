<?php

class SQL
{
    private const ERROR_MESSAGE = "An error has occured."; // Generic error message
    private $conn;
    private $questions; // list of questions belong to a user

    /*
    Construct new database
    */
    public function __construct($hn, $un, $pw, $db, $studentId = false)
    {
        try {
            $this->conn = new mysqli($hn, $un, $pw, $db);
            if ($this->conn->connect_error) {
                die(self::ERROR_MESSAGE);
            }
            if ($studentId) {
                $this->questions = [];

                // fetch all questions from a user
                $get_questions_result = $this->execute_get_questions_from_user($studentId);
                if (!$get_questions_result) {
                    return;
                }
                $rows = $get_questions_result->num_rows;
                if ($rows > 0) {
                    // Iterate through each row, column in the db
                    // and save it in questions
                    for ($cursor = 0; $cursor < $rows; ++$cursor) {
                        $get_questions_result->data_seek($cursor);
                        $row = $get_questions_result->fetch_array(MYSQLI_ASSOC);
                        // add all questions to questions array
                        array_push($this->questions, $row['content']);
                    }
                }

                $get_questions_result->close(); // deallocate the result
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


    // Close database connection
    private function close_conn()
    {
        $this->get_conn()->close();
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
    private function insert_user_query($name, $studentId, $email, $password)
    {
        return "INSERT INTO user (email, studentId, password, name) VALUES ('$email', '$studentId', '$password', '$name')";
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
        // store in db
        $query = $this->insert_user_query($name, $studentId, $email, $password);
        try {
            // perform query on the db
            $result = $this->get_conn()->query($query);
            return $result;
        } catch (Exception) {
            return false; // server issue
        }
    }

    // Insert new question into the database
    public function insert_question($studentId, $question)
    {
        return $this->execute_insert_question($studentId, $question);
    }

    // Insert new question query
    private function insert_question_query($studentId, $question)
    {
        return "INSERT INTO question (content, studentId) VALUES ('$question', '$studentId')";
    }

    private function execute_insert_question($studentId, $question)
    {
        // sanitize input
        $question = $this->sanitize_mysql($question);
        $studentId = $this->sanitize_mysql($studentId);

        // if this question was already uploaded by this user
        // or is an empty line
        if (in_array($question, $this->questions) || strlen($question) === 0) {
            return false;
        }
        // store in db
        $query = $this->insert_question_query($studentId, $question);
        try {
            // perform query on the db
            $result = $this->get_conn()->query($query);
            if ($result) {
                array_push($this->questions, $question);
            }
            return $result;
        } catch (Exception) {
            return false; // server issue
        }
    }

    // Fetch all questions from a user query
    private function get_questions_from_user_query($studentId)
    {
        return "SELECT * FROM question WHERE studentId = '$studentId'";
    }

    private function execute_get_questions_from_user($studentId)
    {
        $query = $this->get_questions_from_user_query($studentId);
        try {
            // perform query on the db
            $result = $this->get_conn()->query($query);
            return $result;
        } catch (Exception) {
            return false; // server issue
        }
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

    // suggest a question
    public function suggest_question()
    {
        if (count($this->questions) > 0) {
            // store all questions of this user to a temp array
            $temp_questions = $this->questions;
            // shuffle this temp array
            shuffle($temp_questions);
            // print the first question in the temp array
            return $temp_questions[0];
        }
        return false;
    }

    // Print all questions belongs to a user in the database
    public function print_questions()
    {
        if (count($this->questions) > 0) {
            echo ("<br><b>List of questions</b> <br>");

            foreach ($this->questions as $question) {
                echo ($question . "<br>");
            }
            return true;
        }
        return false;
    }

    // Function to hash password
    private function hash_password($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
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
}

?>