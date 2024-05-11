CREATE TABLE
    advisor (
        id CHAR(9) UNIQUE,
        name VARCHAR(128) NOT NULL,
        phone CHAR(10),
        email VARCHAR(128) NOT NULL UNIQUE,
        stuIdLowerBound INT(2) NOT NULL,
        stuIdUpperBound INT(2) NOT NULL,
        PRIMARY KEY (id)
    );
    
CREATE TABLE
    user (
        email VARCHAR(128) NOT NULL UNIQUE,
        studentId CHAR(9) UNIQUE,
        password VARCHAR(255) NOT NULL,
        name VARCHAR(128) NOT NULL,
        advisorId CHAR(9) NOT NULL,
        PRIMARY KEY (studentId),
        FOREIGN KEY (advisorId) REFERENCES advisor (id)
    );

INSERT INTO advisor (id, name, phone, email, stuIdLowerBound, stuIdUpperBound) VALUES ("000000001", "Carter Barnes", "5101232123", "carter@sjsu.edu", 00, 19);
INSERT INTO advisor (id, name, phone, email, stuIdLowerBound, stuIdUpperBound) VALUES ("000000002", "Charley Blackburn", '5109823343', "charley@sjsu.edu", 20, 39);
INSERT INTO advisor (id, name, phone, email, stuIdLowerBound, stuIdUpperBound) VALUES ("000000003", "Marvin Jacobson", '5100495838', "marvin@sjsu.edu", 40, 59);
INSERT INTO advisor (id, name, phone, email, stuIdLowerBound, stuIdUpperBound) VALUES ("000000004", "Emmy Ponce", '5100291748', "emmy@sjsu.edu", 60, 79);
INSERT INTO advisor (id, name, phone, email, stuIdLowerBound, stuIdUpperBound) VALUES ("000000005", "Connor Reed", '5100193858', "connor@sjsu.edu", 80, 99);

