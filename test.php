<?php

	// --- CREATE THE DATABASE
	$db_conn = mysqli_connect("localhost", "root", "");
	if (!$db_conn)
		die("Unable to connect: " . mysqli_connect_error());


	mysqli_query($db_conn, "CREATE DATABASE newDatabase;");
	
	// --- CREATE THE TABLE
	mysqli_select_db($db_conn, "newDatabase");
	$cmd = "CREATE TABLE clist (
		Zipcode int(5) NOT NULL PRIMARY KEY,
		Name varchar(25),
		State varchar(2),
		Longitude float(6,4),
		Latitude float(6,4),
		gap int(1)
	);";

	mysqli_query($db_conn, $cmd);

	$cmd = "LOAD DATA LOCAL INFILE 'zip_codes_usa.csv' INTO TABLE clist FIELDS TERMINATED BY ',';";
	mysqli_query($db_conn, $cmd);

	echo "<h1>CAR DATABASE CONTENTS</h1>";
	$cmd = "SELECT * FROM clist";
	
	$records = mysqli_query($db_conn, $cmd);
	while($row = mysqli_fetch_array($records))
		echo( "\t\t\t<tr> <td>" . " " . $row['Zipcode'] . " " .  $row['Name'] . " " . $row['State'] . " " .  $row['Longitude'] . " " . $row['Latitude'] . " " . $row['gap'] . "<br>" . PHP_EOL );

	mysqli_close($db_conn);
?>