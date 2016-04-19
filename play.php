<?php
session_start();
$IpAddress = $_SESSION['IpAddress'];
$cluster = Cassandra::cluster()
               ->withContactPoints($IpAddress)
               ->withPort(9042)
               ->build();
$keyspace  = 'video';
$session  = $cluster->connect($keyspace);
$table='movies';
$table2='data';
$movie_id=$_GET["id"];
$future=$session->execute(new Cassandra\SimpleStatement("SELECT ftype,title,filesize,chunkcount FROM $table WHERE movie_id=$movie_id;"));
foreach ($future as $row) {
$chunkcount=$row['chunkcount'];
$fSize=$row['filesize'];
$title=$row['title'];
$fType=$row['ftype'];
}
header("Content-Type:".$fType);
header('Content-Length:'.$fSize);
header('Content-Disposition: inline; filename='.$title);

for ($chunkID = 1; $chunkID <= $chunkcount; $chunkID++) {
$future2=$session->execute(new Cassandra\SimpleStatement("SELECT blobasascii(chunkData) as chunky FROM $table2 WHERE movie_id=$movie_id AND chunkID=$chunkID;"));
foreach ($future2 as $row2) {
$content=$row2['chunky'];
$data= hex2bin($content);
print($data);
}}
?>

