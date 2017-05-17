CREATE TABLE vlak(
    id INT NOT NULL,
    cislo INT NOT NULL,
    jmeno VARCHAR(16),
    typ CHAR(2),
    PRIMARY KEY(id)
);

CREATE TABLE spoje(
    id INT NOT NULL,
    vlak_id INT NOT NULL, 
    smer_id INT NOT NULL, 
    provozni_den DATE NOT NULL,
    cislo_spoje INT NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE trate(
    id INT NOT NULL,
    cislo INT NOT NULL,
    delka INT NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE stanice(
    id INT NOT NULL,
    trat_id INT NOT NULL, 
    jmeno VARCHAR(30) NOT NULL,
    vzdalenost INT NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE smer(
  id int NOT NULL,
  smer CHAR(1) NOT NULL,
  PRIMARY KEY(id)
);

CREATE TABLE prijezdy(
    id INT NOT NULL,
    stanice_id INT NOT NULL,
    prijezd TIME,
    odjezd TIME,
    cislo_spoje INT NOT NULL,
    PRIMARY KEY(id)
)

ALTER TABLE stanice
ADD FOREIGN KEY (trat_id) REFERENCES trate(id)

ALTER TABLE spoje
ADD FOREIGN KEY (vlak_id) REFERENCES vlak(id)

ALTER TABLE spoje
ADD FOREIGN KEY (smer_id) REFERENCES smer(id)

ALTER TABLE prijezdy
ADD FOREIGN KEY (stanice_id) REFERENCES stanice(id)

