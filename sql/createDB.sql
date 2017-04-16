CREATE TABLE vlak(
    id INT NOT NULL AUTO_INCREMENT,
    cislo INT NOT NULL,
    jmeno VARCHAR(16),
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

-- DATA --

-- STANICE --
INSERT INTO  `d158163_spoje`.`stanice` (
`id` ,
`trat_id` ,
`jmeno` ,
`vzdalenost`
)
VALUES (
'1',  '2',  'Brno hl.n.',  '0'
), (
'2',  '2',  'Blansko',  '22'
), (
'3',  '2',  'Skalice nad Svitavou',  '38'
), (
'4',  '2',  'Letovice',  '47'
), (
'5',  '2',  'Březová nad Svitavou',  '57'
), (
'6',  '2',  'Svitavy',  '74'
), (
'7',  '2',  'Česká Třebová',  '91'
), (
'8',  '2',  'Ústí nad Orlicí',  '101'
), (
'9',  '2',  'Choceň',  '116'
), (
'10',  '2',  'Pardubice hl.n.',  '151'
), (
'11',  '2',  'Kolín',  '193'
), (
'12',  '2',  'Praha-Libeň',  '250'
), (
'13',  '2',  'Praha hl.n.',  '255'
);

-- PRIJEZDY --
INSERT INTO  `d158163_spoje`.`prijezdy` (
`id` ,
`stanice_id` ,
`prijezd` ,
`odjezd` ,
`smer_id` ,
`cislo_spoje`
)
VALUES (
'1',  '1',  '00:54:00',  '01:11:00',  '1',  '1'
), (
'2',  '2', NULL , NULL ,  '1',  '1'
), (
'3',  '3', NULL , NULL ,  '1',  '1'
), (
'4',  '4', NULL , NULL ,  '1',  '1'
), (
'5',  '5', NULL , NULL ,  '1',  '1'
), (
'6',  '6', NULL , NULL ,  '1',  '1'
), (
'7',  '7', NULL , NULL ,  '1',  '1'
), (
'8',  '8', NULL , NULL ,  '1',  '1'
), (
'9',  '9', NULL , NULL ,  '1',  '1'
), (
'10',  '10',  '02:42:00',  '02:52:00',  '1',  '1'
), (
'11',  '11', NULL , NULL ,  '1',  '1'
), (
'12',  '12', NULL , NULL ,  '1',  '1'
), (
'13',  '13',  '03:45:00',  '04:06:00',  '1',  '1'
);
INSERT INTO  `d158163_spoje`.`prijezdy` (
`id` ,
`stanice_id` ,
`prijezd` ,
`odjezd` ,
`smer_id` ,
`cislo_spoje`
)
VALUES (
'14',  '1',  '04:31:00',  '04:31:00',  '1',  '2'
), (
'15',  '2',  '04:50:00',  '04:52:00',  '1',  '2'
), (
'16',  '3', NULL , NULL ,  '1',  '2'
), (
'17',  '4',  '05:08:00',  '05:09:00',  '1',  '2'
), (
'18',  '5', NULL , NULL ,  '1',  '2'
), (
'19',  '6',  '05:26:00',  '05:27:00',  '1',  '2'
), (
'20',  '7',  '05:37:00',  '05:38:00',  '1',  '2'
), (
'21',  '8', NULL , NULL ,  '1',  '2'
), (
'22',  '9', NULL , NULL ,  '1',  '2'
), (
'23',  '10',  '06:10:00',  '06:12:00',  '1',  '2'
), (
'24',  '11', NULL , NULL ,  '1',  '2'
), (
'25',  '12', NULL , NULL ,  '1',  '2'
), (
'26',  '13',  '07:06:00',  '07:06:00',  '1',  '2'
);
INSERT INTO  `d158163_spoje`.`prijezdy` (
`id` ,
`stanice_id` ,
`prijezd` ,
`odjezd` ,
`smer_id` ,
`cislo_spoje`
)
VALUES (
'27',  '1',  '05:38:00',  '05:38:00',  '1',  '3'
), (
'28',  '2', NULL , NULL ,  '1',  '3'
), (
'29',  '3', NULL , NULL ,  '1',  '3'
), (
'30',  '4', NULL , NULL ,  '1',  '3'
), (
'31',  '5', NULL , NULL ,  '1',  '3'
), (
'32',  '6', NULL , NULL ,  '1',  '3'
), (
'33',  '7',  '06:36:00',  '06:37:00',  '1',  '3'
), (
'34',  '8', NULL , NULL ,  '1',  '3'
), (
'35',  '9', NULL , NULL ,  '1',  '3'
), (
'36',  '10',  '07:08:00',  '07:10:00',  '1',  '3'
), (
'37',  '11',  '07:29:00',  '07:30:00',  '1',  '3'
), (
'38',  '12', NULL , NULL ,  '1',  '3'
), (
'39',  '13',  '08:06:00',  '08:06:00',  '1',  '3'
);


-- SPOJE --
INSERT INTO  `d158163_spoje`.`spoje` (
`id` ,
`vlak_id` ,
`prijezd_id` ,
`provozni_den`
)
VALUES (
'1',  '1',  '1',  '2017-03-13'
), (
'2',  '1',  '2',  '2017-03-13'
), (
'3',  '1',  '3',  '2017-03-13'
), (
'4',  '1',  '4',  '2017-03-13'
), (
'5',  '1',  '5',  '2017-03-13'
), (
'6',  '1',  '6',  '2017-03-13'
), (
'7',  '1',  '7',  '2017-03-13'
), (
'8',  '1',  '8',  '2017-03-13'
), (
'9',  '1',  '9',  '2017-03-13'
), (
'10',  '1',  '10',  '2017-03-13'
), (
'11',  '1',  '11',  '2017-03-13'
), (
'12',  '1',  '12',  '2017-03-13'
), (
'13',  '1',  '13',  '2017-03-13'
);
INSERT INTO  `d158163_spoje`.`spoje` (
`id` ,
`vlak_id` ,
`prijezd_id` ,
`provozni_den`
)
VALUES (
'14',  '2',  '14',  '2017-03-13'
), (
'15',  '2',  '15',  '2017-03-13'
), (
'16',  '2',  '16',  '2017-03-13'
), (
'17',  '2',  '17',  '2017-03-13'
), (
'18',  '2',  '18',  '2017-03-13'
), (
'19',  '2',  '19',  '2017-03-13'
), (
'20',  '2',  '20',  '2017-03-13'
), (
'21',  '2',  '21',  '2017-03-13'
), (
'22',  '2',  '22',  '2017-03-13'
), (
'23',  '2',  '23',  '2017-03-13'
), (
'24',  '2',  '24',  '2017-03-13'
), (
'25',  '2',  '25',  '2017-03-13'
), (
'26',  '2',  '26',  '2017-03-13'
);
INSERT INTO  `d158163_spoje`.`spoje` (
`id` ,
`vlak_id` ,
`prijezd_id` ,
`provozni_den`
)
VALUES (
'27',  '3',  '27',  '2017-03-13'
), (
'28',  '3',  '28',  '2017-03-13'
), (
'29',  '3',  '29',  '2017-03-13'
), (
'30',  '3',  '30',  '2017-03-13'
), (
'31',  '3',  '31',  '2017-03-13'
), (
'32',  '3',  '32',  '2017-03-13'
), (
'33',  '3',  '33',  '2017-03-13'
), (
'34',  '3',  '34',  '2017-03-13'
), (
'35',  '3',  '35',  '2017-03-13'
), (
'36',  '3',  '36',  '2017-03-13'
), (
'37',  '3',  '37',  '2017-03-13'
), (
'38',  '3',  '38',  '2017-03-13'
), (
'39',  '3',  '39',  '2017-03-13'
);




-- VLAK --

INSERT INTO  `d158163_spoje`.`vlak` (
`id` ,
`cislo` ,
`typ` ,
`jmeno`
)
VALUES 
('1',  '476',  'EN',  'Metropol'), 
('2',  '580',  'RJ',  'Leoš Janáček'),
('3',  '578',  'Ex',  'František Kmoch');
;