CREATE DATABASE IF NOT EXISTS camagru_db;
USE camagru_db;
CREATE USER IF NOT EXISTS 'fdiaz-root'@'%' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON *.* TO 'fdiaz-root'@'%';
FLUSH PRIVILEGES;

CREATE TABLE IF NOT EXISTS user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    confirmationToken VARCHAR(255),
    emailPreference BOOLEAN NOT NULL DEFAULT 1,
	emailConfirmed BOOLEAN NOT NULL DEFAULT 0,
    reset_token VARCHAR(64) DEFAULT NULL,
    reset_expires DATETIME DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS post (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userId INT,
    title VARCHAR(255) NOT NULL,
    date DATETIME NOT NULL,
    mediaUrl LONGBLOB,
    FOREIGN KEY (userId) REFERENCES user(id)
);

CREATE TABLE IF NOT EXISTS comment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    postId INT,
    userComment INT,
    content VARCHAR(255) NOT NULL,
    date DATETIME NOT NULL,
    FOREIGN KEY (postId) REFERENCES post(id),
    FOREIGN KEY (userComment) REFERENCES user(id)
);


CREATE TABLE IF NOT EXISTS likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    postId INT,
    userId INT,
    date DATETIME NOT NULL,
    FOREIGN KEY (postId) REFERENCES post(id),
    FOREIGN KEY (userId) REFERENCES user(id)
);