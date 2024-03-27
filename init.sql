CREATE DATABASE IF NOT EXISTS task;
USE task;
CREATE TABLE IF NOT EXISTS users (
    id INT auto_increment,
    email VARCHAR(255),
	password VARCHAR(255),
    primary key (id)
);
