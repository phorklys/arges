<?php
$dbUpdates = array();
$dbUpdates[0] = array("DROP TABLE IF EXISTS ", "db_config", ", ", "veranstaltungen");
$dbUpdates[1] = array("CREATE TABLE ", "db_config", " ( parameter varchar(64) COLLATE utf8mb4_german2_ci NOT NULL, wert_i int(11) DEFAULT NULL, PRIMARY KEY (parameter) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_german2_ci");
$dbUpdates[2] = array("INSERT INTO ", "db_config", " (parameter, wert_i) VALUES ('version', NULL)");
$dbUpdates[3] = array("CREATE TABLE ", "veranstaltungen", " ( ID int(10) UNSIGNED NOT NULL AUTO_INCREMENT, Name varchar(127) NOT NULL, Bild varchar(255) NOT NULL, Datum date NOT NULL, Einlass time DEFAULT NULL, Beginn time DEFAULT NULL, Ort varchar(127) DEFAULT NULL, PRIMARY KEY (ID) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_german2_ci");

$testdatensatz = array();
$testdatensatz['veranstaltungen'] = "testdaten/veranstaltungen.csv";
