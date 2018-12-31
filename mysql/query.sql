USE iot;



CREATE TABLE tb_estado(
	id_estado INT(1),
	des_estado VARCHAR(30),
	PRIMARY KEY (id_estado)
)ENGINE = InnoDB CHARSET=UTF8;

INSERT INTO tb_estado(id_estado,des_estado) VALUES(1,'Activado');
INSERT INTO tb_estado(id_estado,des_estado) VALUES(2,'Desactivado');

CREATE TABLE tb_tipo_actuador(
	id_tipo_actuador INT(2),
    nom_tipo_actuador VARCHAR(100),
    PRIMARY KEY (id_tipo_actuador)
)ENGINE = InnoDB CHARSET=UTF8;

INSERT INTO tb_tipo_actuador(id_tipo_actuador,nom_tipo_actuador) VALUES(1,'Relay');
INSERT INTO tb_tipo_actuador(id_tipo_actuador,nom_tipo_actuador) VALUES(2,'Dimmer');

CREATE TABLE tb_ambiente(
	id_ambiente BIGINT AUTO_INCREMENT,
    nom_ambiente VARCHAR(100),
    PRIMARY KEY (id_ambiente)
)ENGINE = InnoDB CHARSET=UTF8;

INSERT INTO tb_ambiente(nom_ambiente) VALUES('default');


CREATE TABLE tb_actuador(
	id_actuador BIGINT AUTO_INCREMENT,
	nom_actuador VARCHAR(100),
    mac_actuador CHAR(17), -- MAC
    ipa_actuador CHAR(15), -- IP ADDRESS
    dig_actuador CHAR(40), -- SHA1
    id_estado INT(1),
    id_ambiente BIGINT,
    PRIMARY KEY (id_actuador),
    CONSTRAINT FK_tb_actuador__tb_estado__id_estado FOREIGN KEY (id_estado) REFERENCES tb_estado(id_estado),
    CONSTRAINT FK_tb_actuador__tb_ambiente__id_ambiente FOREIGN KEY(id_ambiente) REFERENCES tb_ambiente(id_ambiente)
)ENGINE = InnoDB CHARSET=UTF8;

SELECT * FROM tb_actuador;

INSERT INTO tb_actuador(nom_actuador,mac_actuador,ipa_actuador,dig_actuador,id_estado,id_ambiente) VALUES('Dormitorio Gerardo','2C:3A:E8:43:91:CF','172.16.30.132','100',1,1);



CREATE TABLE tb_puerto_actuador(
	id_puerto_actuador BIGINT AUTO_INCREMENT,
    est_puerto_actuador INT(4), -- ESTADO 0-255
    des_puerto_actuador VARCHAR(100),
    id_actuador BIGINT, -- HABILITAR O DESHABILITAR PUERTO
	id_tipo_actuador INT(2),
    id_estado INT(1),
    id_ambiente BIGINT,
    PRIMARY KEY(id_puerto_actuador),
    CONSTRAINT FK_tb_puerto_actuador__tb_actuador__id_actuador FOREIGN KEY (id_actuador) REFERENCES tb_actuador(id_actuador),
    CONSTRAINT FK_tb_puerto_actuador__tb_tipo_actuador__id_tipo_actuador FOREIGN KEY (id_tipo_actuador) REFERENCES tb_tipo_actuador(id_tipo_actuador),
    CONSTRAINT FK_tb_puerto_actuador__tb_estado__id_estado FOREIGN KEY(id_estado) REFERENCES tb_estado(id_estado),
    CONSTRAINT FK_tb_puerto_actuador__tb_ambiente__id_ambiente FOREIGN KEY(id_ambiente) REFERENCES tb_ambiente(id_ambiente)
)ENGINE = InnoDB CHARSET=UTF8;


INSERT INTO tb_puerto_actuador(est_puerto_actuador,des_puerto_actuador,id_actuador,id_tipo_actuador,id_estado,id_ambiente) VALUES(1,'Escritorio',1,1,1,1);
INSERT INTO tb_puerto_actuador(est_puerto_actuador,des_puerto_actuador,id_actuador,id_tipo_actuador,id_estado,id_ambiente) VALUES(1,'Centro',1,1,1,1);
INSERT INTO tb_puerto_actuador(est_puerto_actuador,des_puerto_actuador,id_actuador,id_tipo_actuador,id_estado,id_ambiente) VALUES(1,'Closet',1,1,1,1);
INSERT INTO tb_puerto_actuador(est_puerto_actuador,des_puerto_actuador,id_actuador,id_tipo_actuador,id_estado,id_ambiente) VALUES(1,'Libre',1,1,1,1);
INSERT INTO tb_puerto_actuador(est_puerto_actuador,des_puerto_actuador,id_actuador,id_tipo_actuador,id_estado,id_ambiente) VALUES(1,'Luz Panel',1,1,1,1);


SELECT * FROM tb_puerto_actuador;

UPDATE tb_puerto_actuador SET est_puerto_actuador=0 WHERE id_puerto_actuador=1;
UPDATE tb_puerto_actuador SET est_puerto_actuador=0 WHERE id_puerto_actuador=2;
UPDATE tb_puerto_actuador SET est_puerto_actuador=0 WHERE id_puerto_actuador=3;
UPDATE tb_puerto_actuador SET est_puerto_actuador=0 WHERE id_puerto_actuador=4;


CREATE TABLE tb_tipo_sensor(
	id_tipo_sensor INT(2),
    nom_tipo_sensor VARCHAR(100),
    PRIMARY KEY(id_tipo_sensor)
);

SELECT * FROM tb_tipo_sensor;
INSERT INTO tb_tipo_sensor(id_tipo_sensor,nom_tipo_sensor) VALUES('1','Touch boton');
INSERT INTO tb_tipo_sensor(id_tipo_sensor,nom_tipo_sensor) VALUES('2','Interruptor');
INSERT INTO tb_tipo_sensor(id_tipo_sensor,nom_tipo_sensor) VALUES('3','PIR');


CREATE TABLE tb_sensor(
	id_sensor BIGINT AUTO_INCREMENT,
    nom_sensor VARCHAR(100),
    mac_sensor CHAR(17),
    ipa_sensor CHAR(15),
    dig_sensor CHAR(40),
    id_estado INT(1),
    id_ambiente BIGINT,
    PRIMARY KEY(id_sensor),
    CONSTRAINT FK_tb_sensor__tb_estado__id_estado FOREIGN KEY(id_estado) REFERENCES tb_estado(id_estado),
    CONSTRAINT FK_tb_sensor__tb_ambiente__id_ambiente FOREIGN KEY(id_ambiente) REFERENCES tb_ambiente(id_ambiente)
)ENGINE = InnoDB CHARSET=UTF8;


SELECT * FROM tb_sensor;
INSERT INTO tb_sensor(nom_sensor,mac_sensor,ipa_sensor,dig_sensor,id_estado,id_ambiente) VALUES('Dormitorio Gerardo','2C:3A:E8:43:91:CF','172.16.30.132','100',1,1);



CREATE TABLE tb_puerto_sensor(
	id_puerto_sensor BIGINT AUTO_INCREMENT,
    des_puerto_sensor VARCHAR(100),
    id_sensor BIGINT,
    id_tipo_sensor INT(2),
	id_estado INT(1),
    id_ambiente BIGINT,
    PRIMARY KEY (id_puerto_sensor),
    CONSTRAINT FK_tb_puerto_sensor__tb_sensor__id_sensor FOREIGN KEY(id_sensor) REFERENCES tb_sensor(id_sensor),
    CONSTRAINT FK_tb_puerto_sensor__tb_tipo_sensor__id_tipo_sensor FOREIGN KEY(id_tipo_sensor) REFERENCES tb_tipo_sensor(id_tipo_sensor),
    CONSTRAINT FK_tb_puerto_sensor__tb_estado__id_estado FOREIGN KEY(id_estado) REFERENCES tb_estado(id_estado),
    CONSTRAINT FK_tb_puerto_sensor__tb_ambiente__id_ambiente FOREIGN KEY(id_ambiente) REFERENCES tb_ambiente(id_ambiente)
)ENGINE = InnoDB CHARSET=UTF8;


SELECT * FROM tb_puerto_sensor;

INSERT INTO tb_puerto_sensor(des_puerto_sensor,id_sensor,id_tipo_sensor,id_estado,id_ambiente) VALUES('Boton Touch Escritorio',1,1,1,1);
INSERT INTO tb_puerto_sensor(des_puerto_sensor,id_sensor,id_tipo_sensor,id_estado,id_ambiente) VALUES('Boton Touch Centro',1,1,1,1);
INSERT INTO tb_puerto_sensor(des_puerto_sensor,id_sensor,id_tipo_sensor,id_estado,id_ambiente) VALUES('Boton Touch Closet',1,1,1,1);
INSERT INTO tb_puerto_sensor(des_puerto_sensor,id_sensor,id_tipo_sensor,id_estado,id_ambiente) VALUES('Boton Touch Libre',1,1,1,1);
INSERT INTO tb_puerto_sensor(des_puerto_sensor,id_sensor,id_tipo_sensor,id_estado,id_ambiente) VALUES('Boton Touch Libre',1,2,1,1);

CREATE TABLE tb_puerto_actuador_puerto_sensor(
	id_puerto_actuador_puerto_sensor BIGINT AUTO_INCREMENT,
    id_puerto_actuador BIGINT,
    id_puerto_sensor BIGINT,
    id_estado INT(1),
    PRIMARY KEY(id_puerto_actuador_puerto_sensor),
    CONSTRAINT FK_tb_puerto_actuador_puerto_sensor__tb_pue_act__id_pue_act FOREIGN KEY(id_puerto_actuador) REFERENCES tb_puerto_actuador(id_puerto_actuador),
    CONSTRAINT FK_tb_puerto_actuador_puerto_sensor__tb_pue_sen__id_pue_sen FOREIGN KEY(id_puerto_sensor) REFERENCES tb_puerto_sensor(id_puerto_sensor),
    CONSTRAINT FK_tb_puerto_actuador_puerto_sensor__tb_estado__id_estado FOREIGN KEY(id_estado) REFERENCES tb_estado(id_estado)
)ENGINE = InnoDB CHARSET=UTF8;

SELECT * FROM tb_puerto_actuador_puerto_sensor;


INSERT INTO tb_puerto_actuador_puerto_sensor(id_puerto_actuador,id_puerto_sensor,id_estado) VALUES(1,1,1);
INSERT INTO tb_puerto_actuador_puerto_sensor(id_puerto_actuador,id_puerto_sensor,id_estado) VALUES(2,1,1);

INSERT INTO tb_puerto_actuador_puerto_sensor(id_puerto_actuador,id_puerto_sensor,id_estado) VALUES(3,2,1);


INSERT INTO tb_puerto_actuador_puerto_sensor(id_puerto_actuador,id_puerto_sensor,id_estado) VALUES(2,3,1);
INSERT INTO tb_puerto_actuador_puerto_sensor(id_puerto_actuador,id_puerto_sensor,id_estado) VALUES(3,3,1);



INSERT INTO tb_puerto_actuador_puerto_sensor(id_puerto_actuador,id_puerto_sensor,id_estado) VALUES(1,4,1);
INSERT INTO tb_puerto_actuador_puerto_sensor(id_puerto_actuador,id_puerto_sensor,id_estado) VALUES(2,4,1);
INSERT INTO tb_puerto_actuador_puerto_sensor(id_puerto_actuador,id_puerto_sensor,id_estado) VALUES(3,4,1);
INSERT INTO tb_puerto_actuador_puerto_sensor(id_puerto_actuador,id_puerto_sensor,id_estado) VALUES(4,4,1);



INSERT INTO tb_puerto_actuador_puerto_sensor(id_puerto_actuador,id_puerto_sensor,id_estado) VALUES(1,5,1);


















SELECT * FROM tb_puerto_actuador_puerto_sensor;




SELECT pa.est_puerto_actuador FROM tb_actuador a
INNER JOIN tb_puerto_actuador pa ON pa.id_actuador=a.id_actuador
WHERE a.ipa_actuador='172.16.30.132';


SELECT pa.est_puerto_actuador FROM tb_actuador a
INNER JOIN tb_puerto_actuador pa ON pa.id_actuador=a.id_actuador
WHERE a.ipa_actuador='172.16.30.132' AND dig_actuador='3b18b7aba0d421d7a850e9363a87e5fc097775a0';



SELECT * FROM tb_puerto_sensor;
SELECT * FROM tb_puerto_actuador_puerto_sensor;


SELECT CONCAT('d=',dig_actuador) as d FROM tb_actuador
													WHERE a.ipa_actuador='172.16.30.132' AND dig_actuador='3b18b7aba0d421d7a850e9363a87e5fc097775a0';
                                                    
                                                    
UPDATE tb_actuador set dig_actuador=100 where id_actuador=1;


SELECT dig_sensor FROM tb_sensor where ipa_sensor='172.16.30.132';


SELECT * FROM tb_puerto_actuador;

UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=1 WHERE id_puerto_actuador IN
(SELECT mpaps.id_puerto_actuador_puerto_sensor FROM tb_puerto_actuador_puerto_sensor mpaps
INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor
INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor
WHERE s.ipa_sensor='172.16.30.132' AND ps.id_estado=1 AND s.id_estado=1);

SELECT pa.est_puerto_actuador,pa.id_tipo_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor INNER JOIN tb_puerto_actuador pa ON mpaps.id_puerto_actuador=pa.id_puerto_actuador WHERE s.ipa_sensor='172.16.30.132' LIMIT 1,1;



UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=1 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador_puerto_sensor FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='172.16.30.132' AND ps.id_estado=1 AND s.id_estado=1);



SELECT COUNT(*) as count_sensor FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='172.16.30.132' ;

SELECT pa.est_puerto_actuador, pa.id_tipo_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor INNER JOIN tb_puerto_actuador pa ON mpaps.id_puerto_actuador=pa.id_puerto_actuador WHERE s.ipa_sensor='172.16.30.132' LIMIT 1,1;


SELECT pa.est_puerto_actuador, pa.id_tipo_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor INNER JOIN tb_puerto_actuador pa ON mpaps.id_puerto_actuador=pa.id_puerto_actuador WHERE s.ipa_sensor='172.16.30.132' LIMIT 1,1;


SELECT * FROM tb_puerto_actuador;
SELECT * FROM tb_puerto_actuador_puerto_sensor;



UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador = (SELECT mpaps.id_puerto_actuador_puerto_sensor FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='172.16.30.132' AND ps.id_estado=1 AND s.id_estado=1 LIMIT 0,1);

UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=1 WHERE id_puerto_actuador = (SELECT mpaps.id_puerto_actuador_puerto_sensor FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='172.16.30.132' AND ps.id_estado=1 AND s.id_estado=1 LIMIT 0,1);

SELECT pa.id_puerto_actuador,pa.est_puerto_actuador, pa.id_tipo_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor INNER JOIN tb_puerto_actuador pa ON mpaps.id_puerto_actuador=pa.id_puerto_actuador WHERE s.ipa_sensor='172.16.30.132' LIMIT 1,1;





SELECT ps.id_puerto_sensor FROM  tb_puerto_sensor ps INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='172.16.30.132' ORDER BY ps.id_puerto_sensor LIMIT 0,1;

SELECT COUNT(*) AS count_sensor FROM  tb_puerto_sensor ps INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='172.16.30.132' ORDER BY ps.id_puerto_sensor;

SELECT pa.est_puerto_actuador,ps.id_tipo_sensor FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor INNER JOIN tb_puerto_actuador pa ON mpaps.id_puerto_actuador=pa.id_puerto_actuador WHERE s.ipa_sensor='172.16.30.132' AND ps.id_puerto_sensor=1 ORDER BY pa.id_puerto_actuador ASC LIMIT 0,1;










show tables;












SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='172.16.30.132' AND ps.id_puerto_sensor=1 AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC;




SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='172.16.30.132' AND ps.id_puerto_sensor=1 AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC;