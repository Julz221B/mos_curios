DROP TABLE IF EXISTS users;

CREATE TABLE users (
    user_id uuid UNIQUE NOT NULL PRIMARY KEY,
    first_name char(128) NOT NULL,
    last_name char(128) NOT NULL,
    dob date NOT NULL,
    username char(64) NOT NULL,
    user_email char(64) NOT NULL,
    user_password text NOT NULL,
    reg_date date NOT NULL DEFAULT(NOW())
);