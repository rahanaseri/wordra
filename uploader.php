<?php
session_start();

$IpAddress = $_SESSION['IpAddress'];
$cluster = Cassandra::cluster()
               ->withContactPoints($IpAddress)
               ->withPort(9042)
               ->build();
$keyspace  = 'video';
$session  = $cluster->connect($keyspace);

$movie_id=$_SESSION['id'];
$fsize=0;
$ids=$movie_id-1;
$table3='data';
$chunkID=0;
$firstTime=TRUE;
$readsize=2048;
$table='movies';
$table2='moviesnum';


/* PUT data comes in on the stdin stream */

$putdata = fopen("php://input", "r");
while ($data = fread($putdata,$readsize)) {
/*calculates the size of the data*/
   $chunkID++;
   $chunksize = strlen($data);
   $fsize=$fsize+$chunksize;
/*inserts the data to database*/
   $content = bin2hex($data);

if($firstTime==TRUE) {
    $statement = new Cassandra\SimpleStatement("INSERT INTO $table (movie_id,uploadTime,title,username,filesize,chunkcount,ftype) VALUES($movie_id,dateOf(now()),'$title','$username',$fsize,$chunkID,'$ftype');");
$session->execute($statement);
  $statement = new Cassandra\SimpleStatement("INSERT INTO $table3 (movie_id,chunkID,chunkData) VALUES ( $movie_id,$chunkID,asciiAsBlob('$content'));");
     $session->execute($statement);
     $firstTime = FALSE;

 }else{
     $statement = new Cassandra\SimpleStatement("UPDATE $table SET filesize=$fsize, chunkcount=$chunkID WHERE movie_id=$movie_id;");
     $session->execute($statement);
     $statement = new Cassandra\SimpleStatement("INSERT INTO $table3 (movie_id,chunkID,chunkData) VALUES ( $movie_id,$chunkID,asciiAsBlob('$content'));");
     $session->execute($statement);
   }

}
fclose($putdata);
/*updates the available movie_id in database for the next video*/
$stmt = new Cassandra\SimpleStatement("DELETE FROM $table2 WHERE id=$ids;");
$session->executeAsync($stmt);
$stmt = new Cassandra\SimpleStatement("INSERT INTO $table2 (id) VALUES ( $movie_id);");
$session->executeAsync($stmt);
?>

