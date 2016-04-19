<body>
<br></br>
<b size="4" color="black">Your video has been successfully uploaded!</b>
<br></br>
<br></br>

<?php
/*gets the meta data of the video from upload.php and loads it to database*/
    session_start();
    $table='movies';
    $ftype = $_POST['hiddenfType'];
    $title = $_POST['title'];
    $username = $_POST['username'];
    $IpAddress=$_SESSION['IpAddress'];
    $movie_id=$_SESSION['id'];
$cluster = Cassandra::cluster()
               ->withContactPoints($IpAddress)
               ->withPort(9042)
               ->build();
$keyspace  = 'video';
$session  = $cluster->connect($keyspace);

$statement = new Cassandra\SimpleStatement("UPDATE $table SET title='$title',ftype='$ftype',username='$username' WHERE movie_id=$movie_id;");
    $session->execute($statement);
?>
</body>
</html>
