USE ACMEMed_SCRUM;

INSERT INTO patients VALUES (1,"Jenny","Smith","F","1993-01-13",NULL,"N",NULL);
INSERT INTO patients VALUES (2,"Bill","Nye","M","2010-04-02",NULL,"N",NULL);
INSERT INTO patients VALUES (3,"Edith","James","F","2006-01-12",NULL,"N",NULL);
INSERT INTO patients VALUES (4,"Casey","Jones","F","1990-12-29",NULL,"N",NULL);
INSERT INTO patients VALUES (5,"Austin","Phillips","Not Specified","2014-08-11",NULL,"N",NULL);

INSERT INTO doctors VALUES (1, "Akhilesh Bajaj");

INSERT INTO medications VALUES (1, "Vest");
INSERT INTO medications VALUES (2, "Acapella");
INSERT INTO medications VALUES (3, "Plumozyme");
INSERT INTO medications VALUES (4, "Inhaled Tobi");
INSERT INTO medications VALUES (5, "Inhaled Colistin");
INSERT INTO medications VALUES (6, "Hypertonic Saline");
INSERT INTO medications VALUES (7, "Azithromycin");
INSERT INTO medications VALUES (8, "Clarithromycin");
INSERT INTO medications VALUES (9, "Inhaled Gentamicin");
INSERT INTO medications VALUES (10, "Enzymes");

INSERT INTO visits VALUES (1,"2023-05-15",1,1);
INSERT INTO visits VALUES (2,"2023-06-19",2,1);
INSERT INTO visits VALUES (3,"2024-01-05",3,1);
INSERT INTO visits VALUES (4,"2024-02-24",4,1);
INSERT INTO visits VALUES (5,"2024-03-15",5,1);

INSERT INTO prescriptions VALUES (1,1,1,NULL,NULL);
INSERT INTO prescriptions VALUES (2,3,1,"3mo.","2018-02-12");

INSERT INTO fev1_results VALUES (1,1,90);
INSERT INTO fev1_results VALUES (2,1,85);
INSERT INTO fev1_results VALUES (3,1,95);