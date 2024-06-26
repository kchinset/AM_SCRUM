-- USER: kermit
-- PWD: sesame

DROP DATABASE IF EXISTS ACMEMed_SCRUM;
CREATE DATABASE ACMEMed_SCRUM;
USE ACMEMed_SCRUM;

DROP TABLE IF EXISTS patients;
DROP TABLE IF EXISTS medications;
DROP TABLE IF EXISTS prescriptions;
DROP TABLE IF EXISTS doctors;
DROP TABLE IF EXISTS visits;
DROP TABLE IF EXISTS fev1_results;

CREATE TABLE patients
(patient_id INT AUTO_INCREMENT NOT NULL,
first_name VARCHAR(50) NOT NULL,
last_name VARCHAR(50) NOT NULL,
gender VARCHAR(30) NOT NULL,
birthdate DATE NOT NULL,
genetics VARCHAR(1000),
diabetes VARCHAR(5) NOT NULL,
other_conditions VARCHAR(1000),
PRIMARY KEY (patient_id))
ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE doctors
(doctor_id INT AUTO_INCREMENT NOT NULL,
doctor_name VARCHAR(100) NOT NULL,
PRIMARY KEY (doctor_id))
ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE medications
(med_id INT AUTO_INCREMENT NOT NULL,
med_name VARCHAR(50) NOT NULL,
PRIMARY KEY (med_id))
ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE visits
(visit_id INT AUTO_INCREMENT NOT NULL,
visit_date DATE NOT NULL,
patient_id INT NOT NULL,
doctor_id INT NOT NULL,
PRIMARY KEY (visit_id),
FOREIGN KEY (patient_id) REFERENCES patients (patient_id) ON DELETE CASCADE,
FOREIGN KEY (doctor_id) REFERENCES doctors (doctor_id) ON DELETE CASCADE)
ENGINE=INNODB DEFAULT CHARSET=utf8;

DROP TABLE prescriptions;

CREATE TABLE prescriptions
(presc_id INT AUTO_INCREMENT NOT NULL,
med_id INT NOT NULL,
visit_id INT NOT NULL,
presc_dosage VARCHAR(50),
presc_quantity INT NOT NULL,
date_received DATE,
PRIMARY KEY (presc_id),
FOREIGN KEY (med_id) REFERENCES medications (med_id) ON DELETE CASCADE,
FOREIGN KEY (visit_id) REFERENCES visits (visit_id) ON DELETE CASCADE)
ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE fev1_results
(fev1_id INT AUTO_INCREMENT NOT NULL,
visit_id INT NOT NULL,
fev1_value INT NOT NULL,
PRIMARY KEY (fev1_id),
FOREIGN KEY (visit_id) REFERENCES visits (visit_id) ON DELETE CASCADE)
ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE USER 'kermit'@'localhost' IDENTIFIED BY 'sesame';

USE ACMEMed_SCRUM;
GRANT SELECT, INSERT, UPDATE
ON patients
TO 'kermit'@'localhost';
GRANT SELECT, INSERT, UPDATE
ON medications
TO 'kermit'@'localhost';
GRANT SELECT, INSERT, UPDATE
ON doctors
TO 'kermit'@'localhost';
GRANT SELECT, INSERT, UPDATE
ON prescriptions
TO 'kermit'@'localhost';
GRANT SELECT, INSERT, UPDATE
ON visits
TO 'kermit'@'localhost';
GRANT SELECT, INSERT, UPDATE
ON fev1_results
TO 'kermit'@'localhost';
FLUSH PRIVILEGES;