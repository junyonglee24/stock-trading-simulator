
use tradingdg13;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL
);

CREATE TABLE userportfolio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    asset_id  VARCHAR(50) NOT NULL,
    quantity VARCHAR(50) NOT NULL,
    price VARCHAR(50) NOT NULL
);

CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    identifier VARCHAR(50) NOT NULL,
    buy_sell VARCHAR(50) NOT NULL,
    trade_type VARCHAR(50) NOT NULL,
    stockPrice VARCHAR(50) NOT NULL,
    quantity INT(11) NOT NULL,
    totalprice VARCHAR(50) NOT NULL,
    createdAt DATETIME
);

CREATE TABLE virtualwallet (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    balance VARCHAR(50) NOT NULL 
);
