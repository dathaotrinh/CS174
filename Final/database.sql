CREATE TABLE
    user (
        studentId CHAR(9) NOT NULL UNIQUE,
        email VARCHAR(128) NOT NULL UNIQUE,
        name VARCHAR(128) NOT NULL,
        password VARCHAR(255) NOT NULL,
        PRIMARY KEY (studentId)
    );

CREATE TABLE
    question (
        content TEXT NOT NULL,
        studentId CHAR(9) NOT NULL,
        FOREIGN KEY (studentId) REFERENCES user (studentId)
    );