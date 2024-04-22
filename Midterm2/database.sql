CREATE TABLE
    user (
        id INT AUTO_INCREMENT,
        username VARCHAR(128) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        name VARCHAR(128) NOT NULL,
        PRIMARY KEY (id)
    );

CREATE TABLE
    thread (
        thread_name TEXT NOT NULL,
        file_content TEXT NOT NULL,
        id INT AUTO_INCREMENT NOT NULL,
        user_id INT NOT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (user_id) REFERENCES user (id)
    );