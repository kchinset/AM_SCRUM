DROP DATABASE IF EXISTS ACMEMed_SCRUM;
CREATE DATABASE ACMEMed_SCRUM;
USE ACMEMed_SCRUM;

DROP TABLE IF EXISTS patients;
DROP TABLE IF EXISTS medication;
DROP TABLE IF EXISTS pulmozyme;
DROP TABLE IF EXISTS enzymes;
DROP TABLE IF EXISTS vists;
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

CREATE TABLE medication
(patient_id INT NOT NULL,
vest VARCHAR(5) NOT NULL,
acapella VARCHAR(5) NOT NULL,
inhaled_tobi VARCHAR(5) NOT NULL,
inhaled_colistin VARCHAR(5) NOT NULL,
hypertonic_saline VARCHAR(10) NOT NULL,
azithromycin VARCHAR(5) NOT NULL,
clarithromycin VARCHAR(5) NOT NULL,
inhaled_gentamicin VARCHAR(5) NOT NULL,
FOREIGN KEY (patient_id) REFERENCES patients (patient_id))
ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE pulmozyme
(patient_id INT NOT NULL,
pulm_yn VARCHAR(5) NOT NULL,
pulm_quantity VARCHAR(10),
pulm_date DATE,
FOREIGN KEY (patient_id) REFERENCES patients (patient_id))
ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE enzymes
(patient_id INT NOT NULL,
enz_yn VARCHAR(5) NOT NULL,
enz_typedose VARCHAR(20),
FOREIGN KEY (patient_id) REFERENCES patients (patient_id))
ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE visits
(visit_date DATE NOT NULL,
patient_id INT NOT NULL,
doctor_name VARCHAR(100),
CONSTRAINT visit_pk PRIMARY KEY (visit_date,patient_id),
FOREIGN KEY (patient_id) REFERENCES patients (patient_id))
ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE fev1_results
(visit_date DATE NOT NULL,
patient_id INT NOT NULL,
fev1_value INT NOT NULL,
CONSTRAINT fev1_pk PRIMARY KEY (visit_date,patient_id,fev1_value),
FOREIGN KEY (visit_date,patient_id) REFERENCES visits (visit_date,patient_id))
ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE USER 'kermit'@'localhost' IDENTIFIED BY 'sesame';

USE ACMEMed_SCRUM;
GRANT SELECT, INSERT, UPDATE
ON patients
TO 'kermit'@'localhost';
GRANT SELECT, INSERT, UPDATE
ON medication
TO 'kermit'@'localhost';
GRANT SELECT, INSERT, UPDATE
ON pulmozyme
TO 'kermit'@'localhost';
GRANT SELECT, INSERT, UPDATE
ON enzymes
TO 'kermit'@'localhost';
GRANT SELECT, INSERT, UPDATE
ON visits
TO 'kermit'@'localhost';
GRANT SELECT, INSERT, UPDATE
ON fev1_results
TO 'kermit'@'localhost';
FLUSH PRIVILEGES;

INSERT INTO patients VALUES (1,"Jenny","Smith","F","1993-01-13",NULL,"N",NULL);
INSERT INTO medication VALUES (1,"Y","N","Y","N","Y 7%","N","Y","N");
INSERT INTO pulmozyme VALUES (1,"Y","3 mo.","2018-02-12");
INSERT INTO enzymes VALUES (1,"Y","Creon 2400");
INSERT INTO visits VALUES ("2018-04-02",1,NULL);
INSERT INTO fev1_results VALUES ("2018-04-02",1,90);
INSERT INTO fev1_results VALUES ("2018-04-02",1,85);
INSERT INTO fev1_results VALUES ("2018-04-02",1,95);

INSERT INTO patients VALUES (2,"Bill","Nye","M","2010-04-02",NULL,"N",NULL);
INSERT INTO medication VALUES (2,"N","Y","Y","N","Y 3%","Y","N","N");
INSERT INTO pulmozyme VALUES (2,"Y","1 mo.","2018-03-23");
INSERT INTO enzymes VALUES (2,"Y","Creon 1000");
INSERT INTO visits VALUES ("2018-03-01",2,NULL);
INSERT INTO fev1_results VALUES ("2018-03-01",2,77);
INSERT INTO fev1_results VALUES ("2018-03-01",2,80);
INSERT INTO fev1_results VALUES ("2018-03-01",2,79);

INSERT INTO patients VALUES (3,"Edith","James","F","2006-01-12",NULL,"N",NULL);
INSERT INTO medication VALUES (3,"Y","N","Y","N","N","Y","N","Y");
INSERT INTO pulmozyme VALUES (3,"N",NULL,NULL);
INSERT INTO enzymes VALUES (3,"N",NULL);
INSERT INTO visits VALUES ("2018-02-25",3,NULL);
INSERT INTO fev1_results VALUES ("2018-02-25",3,65);
INSERT INTO fev1_results VALUES ("2018-02-25",3,70);
INSERT INTO fev1_results VALUES ("2018-02-25",3,68);

INSERT INTO patients VALUES (4,"Casey","Jones","F","1990-12-29",NULL,"N",NULL);
INSERT INTO medication VALUES (4,"Y","N","Y","N","N","N","N","N");
INSERT INTO pulmozyme VALUES (4,"Y","3 mo.","2018-01-01");
INSERT INTO enzymes VALUES (4,"Y","Creon 2400");
INSERT INTO visits VALUES ("2018-03-18",4,NULL);
INSERT INTO fev1_results VALUES ("2018-03-18",4,77);
INSERT INTO fev1_results VALUES ("2018-03-18",4,79);
INSERT INTO fev1_results VALUES ("2018-03-18",4,85);

INSERT INTO patients VALUES (5,"Austin","Phillips","Not Specified","2014-08-11",NULL,"N",NULL);
INSERT INTO medication VALUES (5,"Y","N","Y","N","N","N","N","N");
INSERT INTO pulmozyme VALUES (5,"Y","1 mo.","2018-02-12");
INSERT INTO enzymes VALUES (5,"N",NULL);
INSERT INTO visits VALUES ("2018-03-30",5,NULL);
INSERT INTO fev1_results VALUES ("2018-03-30",5,99);
INSERT INTO fev1_results VALUES ("2018-03-30",5,95);
INSERT INTO fev1_results VALUES ("2018-03-30",5,97);