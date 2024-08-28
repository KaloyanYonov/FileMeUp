CREATE DATABASE FileMeUp_db;

use FileMeUp_db;

CREATE TABLE IF NOT EXISTS Users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP DEFAULT 0
);

CREATE TABLE IF NOT EXISTS Files (
    file_id INT PRIMARY KEY AUTO_INCREMENT,
    uploader_id INT,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    file_size INT NOT NULL,
    checksum CHAR(40) NOT NULL,
    thumbnail VARCHAR(255) DEFAULT NULL,
    upload_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_public BOOLEAN DEFAULT FALSE,
    CONSTRAINT fk_uploader FOREIGN KEY (uploader_id) REFERENCES Users(user_id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS File_Shares (
    file_share_id INT PRIMARY KEY AUTO_INCREMENT,
    file_id INT NOT NULL,
    shared_with INT NOT NULL,
    permissions ENUM('read', 'write') DEFAULT 'read',
    shared_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_file FOREIGN KEY (file_id) REFERENCES Files(file_id) ON DELETE CASCADE,
    CONSTRAINT fk_shared_with FOREIGN KEY (shared_with) REFERENCES Users(user_id) ON DELETE CASCADE,
    UNIQUE (file_id, shared_with)
);

INSERT INTO Users (username, email, password_hash, created_at)
VALUES ('root', 'root@example.com', SHA1('azis'), NOW());

INSERT INTO Files (file_name, file_path, mime_type, file_size, checksum, thumbnail, uploader_id)
VALUES ('bacinka.txt', '/root/example.txt', 'text/plain', 1024, '9a0364b9e99bb480dd25e1f0284c8555', '/thumbnails/txt.png', 1);
