DROP DATABASE phpcrud;
CREATE DATABASE IF NOT EXISTS phpcrud;
USE phpcrud;

CREATE TABLE IF NOT EXISTS `contacts` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
  	`name` VARCHAR(255) NOT NULL,
  	`email` VARCHAR(255) NOT NULL,
  	`phone` VARCHAR(255) NOT NULL,
  	`title` VARCHAR(255) NOT NULL,
  	`created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=INNODB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

INSERT INTO `contacts` (`id`, `name`, `email`, `phone`, `title`, `created`) VALUES
(1, 'John Doe', 'johndoe@example.com', '2026550143', 'Lawyer', '2019-05-08 17:32:00'),
(2, 'David Deacon', 'daviddeacon@example.com', '2025550121', 'Employee', '2019-05-08 17:28:44'),
(3, 'Sam White', 'samwhite@example.com', '2004550121', 'Employee', '2019-05-08 17:29:27'),
(4, 'Colin Chaplin', 'colinchaplin@example.com', '2022550178', 'Supervisor', '2019-05-08 17:29:27'),
(5, 'Ricky Waltz', 'rickywaltz@example.com', '7862342390', '', '2019-05-09 19:16:00'),
(6, 'Arnold Hall', 'arnoldhall@example.com', '5089573579', 'Manager', '2019-05-09 19:17:00'),
(7, 'Toni Adams', 'alvah1981@example.com', '2603668738', '', '2019-05-09 19:19:00'),
(8, 'Donald Perry', 'donald1983@example.com', '7019007916', 'Employee', '2019-05-09 19:20:00'),
(9, 'Joe McKinney', 'nadia.doole0@example.com', '6153353674', 'Employee', '2019-05-09 19:20:00'),
(10, 'Angela Horst', 'angela1977@example.com', '3094234980', 'Assistant', '2019-05-09 19:21:00'),
(11, 'James Jameson', 'james1965@example.com', '4002349823', 'Assistant', '2019-05-09 19:32:00'),
(12, 'Daniel Deacon', 'danieldeacon@example.com', '5003423549', 'Manager', '2019-05-09 19:33:00');


-- now create a conversations table
CREATE TABLE IF NOT EXISTS `conversations` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
  	`conversationSummary` VARCHAR(1024) NOT NULL,
    `contact_id` INT(11),
  	`created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
    FOREIGN KEY(`contact_id`) REFERENCES `contacts`(`id`)
) ENGINE=INNODB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;  

INSERT INTO conversations(id,conversationSummary,contact_id) VALUES
(100, 'Good one',1);