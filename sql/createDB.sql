CREATE TABLE vlak(
    id INT NOT NULL AUTO_INCREMENT,
    cislo INT NOT NULL,
    typ CHAR(2),
    PRIMARY KEY(id)
);

CREATE TABLE spoje(
    id INT NOT NULL AUTO_INCREMENT,
    vlak_id INT NOT NULL, #cizi klic pro vlak
    prijezd_id INT NOT NULL, #cizi klic pro prijezdy
    provozni_den DATE NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE trate(
    id INT NOT NULL AUTO_INCREMENT,
    cislo INT NOT NULL,
    delka INT NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE stanice(
    id INT NOT NULL AUTO_INCREMENT,
    trat_id INT NOT NULL, #cizi klic pro trat
    jmeno VARCHAR(30) NOT NULL,
    vzdalenost INT NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE smer(
  id int NOT NULL AUTO_INCREMENT,
  smer CHAR(1) NOT NULL,
  PRIMARY KEY(id)
);

CREATE TABLE prijezdy(
    id INT NOT NULL AUTO_INCREMENT,
    stanice_id INT NOT NULL,
    prijezd TIME,
    odjezd TIME,
    smer_id INT NOT NULL,
    PRIMARY KEY(id)
)

ALTER TABLE stanice
ADD FOREIGN KEY (trat_id) REFERENCES trate(id)

ALTER TABLE spoje
ADD FOREIGN KEY (vlak_id) REFERENCES vlak(id)

ALTER TABLE prijezdy
ADD FOREIGN KEY (stanice_id) REFERENCES stanice(id)

ALTER TABLE spoje
ADD FOREIGN KEY (prijezd_id) REFERENCES prijezdy(id)

ALTER TABLE prijezdy
ADD FOREIGN KEY (smer_id) REFERENCES smer(id)
