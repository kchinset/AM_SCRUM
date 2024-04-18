USE phpcrud;
-- create a new table
CREATE TABLE customers (
   custId INT AUTO_INCREMENT NOT NULL,
   custName VARCHAR(100) NOT NULL,
   custEmail VARCHAR(150) UNIQUE NOT NULL,
   custPassword VARCHAR(100),
   PRIMARY KEY (custId)
)ENGINE=INNODB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
INSERT INTO customers (custName, custEmail, custPassword)
VALUES
('Sam Henry', 'sam@henry.com', NULL),
('Wilma Flinstone', 'wilma@bedrock.com', NULL),
('William Gates', 'bill@gates.com', NULL);


CREATE TABLE orders (
   orderID INT AUTO_INCREMENT NOT NULL,
   orderDescr VARCHAR(250) NOT NULL,
   custID INT,
   PRIMARY KEY (orderID),
   FOREIGN KEY(custID) REFERENCES customers(custID)
)ENGINE=INNODB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

INSERT INTO ORDERS (orderID, orderDescr, custID) VALUES 
(100, 'Awesome Order', 1);