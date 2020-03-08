<?php
// CSE 5335: Web Data Management and XML
// Project 6: Photo Album using DropBox API
// Name: Shagun Paul
// UTA ID: 1001557958
?>
<html>
<head>
    <title> Using Cloud Storage </title>
<style>
h1{ color: #513C3D; font-style: italic;}
h3{ color: #513C3D; font-style: italic;}
form{ color: #513C3D; font-style: italic;}
input{ margin-bottom: 15px; }
</style>
</head>
<body>
    <div>
    <center><h1> Photo Album using DropBox API </h1></center>
</div>
<hr>
<h3> Upload an Image to DropBox </h3>
<form action="album.php" method="POST" enctype="multipart/form-data">
<p><b> Select image to upload: </b></p>
<input type="file" name="fileToUpload" id="fileToUpload">
<input type="submit" value="Upload Image" name="submit">
<br/>
<hr>
</form>
<?php
// display all errors on the browser
error_reporting(E_ALL);
ini_set('display_errors','Off');
require_once 'demo-lib.php';
demo_init(); // this just enables nicer output
// if there are many files in your Dropbox it can take some time, so disable the max. execution time
set_time_limit( 0 );
require_once 'DropboxClient.php';
/** you have to create an app at @see https://www.dropbox.com/developers/apps and enter details below: */
/** @noinspection SpellCheckingInspection */
$dropbox = new DropboxClient( array(
    'app_key' => "ifzabgcb9aqnzup",      // Put your Dropbox API key here
    'app_secret' => "lf8zi8k63n8n7eg",   // Put your Dropbox API secret here
    'app_full_access' => false,
) );
/**
 * Dropbox will redirect the user here
 * @var string $return_url
 */
$return_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?auth_redirect=1";
// first, try to load existing access token
$bearer_token = demo_token_load( "bearer" );
if ( $bearer_token ) {
    $dropbox->SetBearerToken( $bearer_token );
//    echo "loaded bearer token: " . json_encode( $bearer_token, JSON_PRETTY_PRINT ) . "\n";
} elseif ( ! empty( $_GET['auth_redirect'] ) ) // are we coming from dropbox's auth page?
{
    // get & store bearer token
    $bearer_token = $dropbox->GetBearerToken( null, $return_url );
    demo_store_token( $bearer_token, "bearer" );
} elseif ( ! $dropbox->IsAuthorized() ) {
    // redirect user to Dropbox auth page
    $auth_url = $dropbox->BuildAuthorizeUrl( $return_url );
    die( "Authentication required. <a href='$auth_url'>Continue.</a>" );
}
if(isset($_POST['submit'])) {
    $file = basename($_FILES["fileToUpload"]["name"]);
    $target_file = "C:/xampp/htdocs/project6/" . basename($_FILES["fileToUpload"]["name"]);
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
    $dropbox->UploadFile($file);
    echo "Success!!!" .$file." has been uploaded";
}
?>
<h3> Images in DropBox Directory: </h3>
<form action='album.php' method='POST'> 
<input type='submit' name='list_files' value='List Files'/> </form>
<?php
$file_names=array();
if(isset($_POST['list_files']))
{
    $files = $dropbox->GetFiles( "", false );
    if(empty($files))
    {
        echo " Photo Directory is empty! Please upload new photos..........";
    }
    else {
        $file_names = array_keys($files);
        foreach ($file_names as $f => $f_name) {
            echo "<h4>" . $f_name . "</h4>";
            echo "\t * <a  href='album.php?display=" . $f_name . "' > Download & Display " . $f_name . "</a><br>";
            echo "\t * <a  href='album.php?delete=" . $f_name . "' > Delete " . $f_name . "</a><br>";
            echo "</script>";
        }
    }
}
echo "<hr>";
echo "<h3> Uploaded Images Section: </h3>";
if(isset($_GET['display']) ){
    $display=(string)$_GET['display'];
    echo $display;
    echo "<br>";
    if (strpos($display, ".jpg") or strpos($display, ".JPG"))
    {
        $jpg_files = $dropbox->Search("/", $display, 5);
        $jpg_file = reset( $jpg_files );
        $img = base64_encode( $dropbox->GetThumbnail( $jpg_file->path ) );
        echo "<img src=\"data:image/jpeg;base64,$img\" alt=\"Generating PDF thumbnail failed!\" style=\"border: 1px solid black;\" />";
            $test_file =  basename( $jpg_file->path );
            $dropbox->DownloadFile( $jpg_file->path, $test_file )   ;
    }
}
if(isset($_GET['delete'])){
    $delete=(string)$_GET{'delete'};
    $file=$dropbox->Search("/",$delete,5);
    $f=reset($file);
    $del=$dropbox->Delete($dropbox->GetMetadata( $f->path ));
}
?>
</body>
</html>