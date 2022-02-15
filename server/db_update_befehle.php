<?php
$dbUpdates = array();
$dbUpdates[0] = array("CREATE TABLE ","db_config"," (parameter varchar(64) COLLATE utf8mb4_german2_ci NOT NULL, wert_i int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_german2_ci");
$dbUpdates[1] = array("ALTER TABLE ","db_config"," ADD PRIMARY KEY (parameter)");



