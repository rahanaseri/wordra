<?php
//creates a shortcode called 'list'
add_shortcode("list", "list_process_shortcode");

function list_process_shortcode(){
//this page displays a link to play each uploaded video in the database
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(__FILE__).'list.css'?>">
</head>
<body>
<br></br>
<b size="4" color="red">Choose a video to watch</b>
<br></br>
<br></br>
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
$future=$session->execute(new Cassandra\SimpleStatement("SELECT title,movie_id FROM $table;"));

foreach ($future as $row) {
$id_int=$row['movie_id'];
$title=$row['title'];
$id=strval($id_int);
if ( is_user_logged_in() ) {

$url=plugin_dir_url(__FILE__).'play.php?id='.$id;
}else{
$url=plugin_dir_url(__FILE__).'login.php';
}

?>

<div>
 <a class="high" href="<?php echo $url?>"><?php echo $title?> </a>
<br></br>
</div>
<?php
}
?>
</body>
</html>

<?php
}
?>


