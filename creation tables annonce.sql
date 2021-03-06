/* SQLEditor (MySQL (2))*/

CREATE TABLE CHAMP
(
id INT AUTO_INCREMENT UNIQUE,
nom VARCHAR(100) CHARACTER SET utf8,
label VARCHAR(40) CHARACTER SET utf8,
type INT,
PRIMARY KEY (id)
) CHARACTER SET=utf8;

CREATE TABLE AUTEUR
(
id INT AUTO_INCREMENT UNIQUE,
email VARCHAR(255) CHARACTER SET utf8 NOT NULL,
password VARCHAR(255) CHARACTER SET utf8 NOT NULL,
pseudo VARCHAR(100) CHARACTER SET utf8,
PRIMARY KEY (id)
) CHARACTER SET=utf8;

CREATE TABLE MODELE
(
id INT AUTO_INCREMENT UNIQUE,
nom VARCHAR(100) CHARACTER SET utf8 NOT NULL UNIQUE,
PRIMARY KEY (id)
) CHARACTER SET=utf8;

CREATE TABLE CATEGORIE
(
id INT AUTO_INCREMENT UNIQUE,
nom VARCHAR(100) CHARACTER SET utf8 UNIQUE,
id_parent INT,
id_modele INT,
PRIMARY KEY (id)
) CHARACTER SET=utf8;

CREATE TABLE ANNONCE
(
id INT AUTO_INCREMENT UNIQUE,
nom VARCHAR(255) CHARACTER SET utf8,
description TEXT CHARACTER SET utf8,
date_crea DATE,
date_modif DATE,
id_categorie INT,
id_auteur INT,
PRIMARY KEY (id)
) CHARACTER SET=utf8;

CREATE TABLE ANNONCE_CHAMP
(
id INT AUTO_INCREMENT UNIQUE,
id_annonce INT,
id_champ INT,
valeur TEXT CHARACTER SET utf8,
PRIMARY KEY (id)
) CHARACTER SET=utf8;

CREATE TABLE MODELE_CHAMP
(
id_champ INT,
id_modele INT,
nombre INT,
PRIMARY KEY (id_champ,id_modele)
) CHARACTER SET=utf8;

ALTER TABLE CATEGORIE ADD FOREIGN KEY id_parent_idxfk (id_parent) REFERENCES CATEGORIE (id);

ALTER TABLE CATEGORIE ADD FOREIGN KEY id_modele_idxfk (id_modele) REFERENCES MODELE (id);

ALTER TABLE ANNONCE ADD FOREIGN KEY id_categorie_idxfk (id_categorie) REFERENCES CATEGORIE (id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE ANNONCE ADD FOREIGN KEY id_auteur_idxfk (id_auteur) REFERENCES AUTEUR (id);

ALTER TABLE ANNONCE_CHAMP ADD FOREIGN KEY id_annonce_idxfk (id_annonce) REFERENCES ANNONCE (id);

ALTER TABLE ANNONCE_CHAMP ADD FOREIGN KEY id_champ_idxfk (id_champ) REFERENCES CHAMP (id);

ALTER TABLE MODELE_CHAMP ADD FOREIGN KEY id_champ_idxfk_1 (id_champ) REFERENCES CHAMP (id);

ALTER TABLE MODELE_CHAMP ADD FOREIGN KEY id_modele_idxfk_1 (id_modele) REFERENCES MODELE (id);
