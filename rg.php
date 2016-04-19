<?php
//creates a wordpress shortcode called 'upload' to display the page for uploading videos
add_shortcode('upload', 'upload_process_shortcode');
        function upload_process_shortcode(){

?>
<html>
<head>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__).'js/css/jquery.fileupload.css'?>">
</head>
<body>
<div class="container">
    <br>
    <!-- The fileinput-button span is used to style the file input field as button -->
    <span class="btn btn-success fileinput-button">
        <i class="glyphicon glyphicon-plus"></i>
        <span>Select files...</span>
        <!-- The file input field used as target for the file upload widget -->
        <input id="fileupload" type="file" name="files[]" multiple>
    </span>
    <br>
    <br>
    <!-- The global progress bar -->
    <div id="progress" class="progress" value="40" max="100" style="width:50%">
        <div class="progress-bar progress-bar-success"></div>
    </div>
    <!-- The container for the uploaded files -->
    <div id="files" class="files"></div>
    <br>
</div>
<div>
<!-- A form to post metadata of the file to database -->
<form action="<?php echo plugin_dir_url(__FILE__).'next.php'?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="hiddenfType" id="hiddenfType">
    Title:
    <input type="text" name="title" id="title">
   Username:
    <input type="text" name="username" id="username">
<br></br>
   <input type="submit" value="Submit" name="submit">
</form>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?php echo plugin_dir_url(__FILE__).'js/js/vendor/jquery.ui.widget.js'?>"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo plugin_dir_url(__FILE__).'js/js/jquery.iframe-transport.js'?>"></script>
<!-- The basic File Upload plugin -->
<script src="<?php echo plugin_dir_url(__FILE__).'js/js/jquery.fileupload.js'?>"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script>
/*jslint unparam: true */
/*global window, $ */
/*Ajax PUT request to stream the data*/
$(function () {
    'use strict';
    // the location of server-side upload handler:
    var url = "<?php echo plugin_dir_url(__FILE__).'uploader.php'?>";
$('#fileupload').bind('change', function() {
  //this.files[0].type gets the type of your file.
  var file_type=this.files[0].type;
document.getElementById("hiddenfType").value =file_type ;
 //  window.location.href="rg.php?ftype='video/mp4'";
});
    $('#fileupload').fileupload({
        url: url,
        dataType: 'json',
        method: 'PUT',
        multipart: false,
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                data.headers={};
                $('<p/>').text(file.name).appendTo('#files');
            });
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
});
</script>
</body>
</html>
<?php
session_start();
/*retrieves the database IpAddress from options.php*/
$IpAddress = get_option( 'wordra_setting_name', false );
$cluster = Cassandra::cluster()
               ->withContactPoints($IpAddress)

               ->withPort(9042)
               ->build();
$keyspace  = 'video';
$session  = $cluster->connect($keyspace);
$table='moviesnum';
/*retrieves the available movie_id from database to assgin to the video*/
$future=$session->execute(new Cassandra\SimpleStatement("SELECT id FROM $table;"));

foreach ($future as $row) { $id2=$row['id'];}
$id= $id2 + 1;
$_SESSION['id'] = $id;
$_SESSION['IpAddress'] = $IpAddress;

?>
<?php
}?>
