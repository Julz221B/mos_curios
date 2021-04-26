DROP TABLE IF EXISTS users;

CREATE TABLE users (
    user_id uuid UNIQUE PRIMARY KEY NOT NULL,
    user_name char(255) NOT NULL,
    user_dob date NOT NULL,
    user_date_created date NOT NULL DEFAULT(NOW()),
    user_email char(255) NOT NULL,
    user_password char(255) NOT NULL
);