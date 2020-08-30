<?php
ini_set('max_execution_time', '120');
$servername = "localhost"; $username = "root"; $password = "";
// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}

//create database
$sql = "drop database if exists pollution";
$conn->query($sql);
$sql = "create database pollution";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully ";
} else {
    echo "Error creating database: " . $conn->error;
}

$sql = "use pollution";
$conn->query($sql);

//create table
$sql = "drop table if exists pollution_data";
$conn->query($sql);
$sql = "create table pollution_data(created_at varchar(255),co float,h float,no2 float,o3 float,p float,pm_10 float,pm_1_0 float,pm_2_5 float,so2 float,t float,ws float)";
if ($conn->query($sql) === TRUE) {
    echo " table created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$file = fopen('PollutionDataCS207.csv', 'r');
$head=fgetcsv($file);
while (!feof($file)) {
	$line = fgetcsv($file);
	$tm=$line[0];
	$to_date_timestamp = strtotime($tm);
    $to_day = strval(date("d", $to_date_timestamp));
    $to_month =strval(date("m", $to_date_timestamp));
    $to_year = strval(date("Y", $to_date_timestamp));
    $final_time=$to_year.'-'.$to_month.'-'.$to_day;
	//echo "$final_time<br>";
	$sql = "insert into pollution_data(created_at,co,h,no2,o3,p,pm_10,pm_1_0,pm_2_5,so2,t,ws) values('".$final_time."',".$line[1].",".$line[2].",".$line[3].",".$line[4].",".$line[5].",".$line[6].",".$line[7].",".$line[8].",".$line[9].",".$line[10].",".$line[11].")";

	$conn->query($sql);
}
fclose($file);

echo " data inserted successfully";

$conn->close();
?>