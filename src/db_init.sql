CREATE TABLE IF NOT EXISTS User (
                      id INT NOT NULL AUTO_INCREMENT,
                      email VARCHAR(100) NOT NULL,
                      displayname VARCHAR(100) NOT NULL,
                      password VARCHAR(100) NOT NULL,
                      date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                      CONSTRAINT PK_User PRIMARY KEY (id),
                      CONSTRAINT UQ_User_Email UNIQUE (email),
                      CONSTRAINT UQ_User_Displayname UNIQUE (displayname)
);

CREATE TABLE IF NOT EXISTS Comment (
                         id INT NOT NULL AUTO_INCREMENT,
                         user_id INT NOT NULL,
                         parent_id INT,
                         content VARCHAR(255),
                         date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                         CONSTRAINT PK_Comment PRIMARY KEY (id),
                         CONSTRAINT FK_Comment_User FOREIGN KEY (user_id) REFERENCES User (id),
                         CONSTRAINT FK_Comment_Parent FOREIGN KEY (parent_id) REFERENCES Comment (id)
);

CREATE TABLE IF NOT EXISTS Vote (
                      id INT NOT NULL AUTO_INCREMENT,
                      user_id INT NOT NULL,
                      comment_id INT NOT NULL,
                      CONSTRAINT PK_Like PRIMARY KEY (id),
                      CONSTRAINT FK_Like_User FOREIGN KEY (user_id) REFERENCES User (id),
                      CONSTRAINT FK_Like_Comment FOREIGN KEY (comment_id) REFERENCES Comment (id)
);
