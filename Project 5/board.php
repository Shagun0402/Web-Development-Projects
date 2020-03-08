<?php
  // CSE 5335: Web Data Management Project 5
  // Project 5: A Message Board using PHP and MySQL
  // Name: Shagun Paul
  // UTA ID: 1001557958
?>

<html>
<head>
  <title> Message Board </title>
</head>
<body style="background-color: #D7D2CF;">
  <h2 align="center" style="color: #6E464A; font-family: 'Comic Sans MS';"> Hello User! Welcome to Message Board </h2>
<form align="right" method="get" action="board.php">
  <input type="submit" name="out" value=" Logout "/>
</form>
<form action =board.php method=POST>
<center><input type="text" id="newmessage" name="newmessage"/></center>
<center><br><input type="submit" name="submit" value="New Post"/></br></center>
</form>
<?php
 session_start();
 error_reporting(E_ALL);
 ini_set('display_errors','On');
 try 
 {
   $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
   if(isset($_GET['out']))
   {
    session_destroy();
    header('Location: login.php');
    exit();
  }

  if(isset($_POST['uname']) && isset($_POST['pass']))
  {
    $get_query = 'SELECT username,password from USERS where username="'.$_POST['uname'].'"';
    $result = $dbh->query($get_query,PDO::FETCH_ASSOC);
    $result = $result->fetchAll();
    if($result[0]['password']== md5($_POST['pass']))
    {
     
      $_SESSION["postedby"] = $result[0]['username'];
    }
    else
    {
      header('Location: login.php');
      exit();
    }

  }

  if(isset( $_SESSION['postedby']))
  {
    if(isset($_POST["newmessage"]))
    {
      $query = 'INSERT INTO POSTS VALUES(:id,:replyto,:postedby,now(),:message)';
      $statement = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
      $statement->execute(array(':id' => uniqid(), ':replyto' => null, ':postedby'=> $_SESSION['postedby'],':message'=> $_POST['newmessage']));
    }
    if(isset($_GET["replyto"]))
     {
       $unique_id = uniqid();
       $query = 'INSERT INTO POSTS VALUES(:id,:replyto,:postedby,now(),:message)';
       $statement = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
       $statement->execute(array(':id' => $unique_id, ':replyto' => null, ':postedby'=> $_SESSION['postedby'],':message'=> $_GET['reply']));
       $update = 'UPDATE posts SET replyto=:replyid where id=:uid';
       $statement = $dbh->prepare($update, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
       $statement->execute(array(':replyid' =>$_GET['replyto'] , ':uid'=>$unique_id));
     }
  
    $dbh->beginTransaction();
    $dbh->exec('delete from users where username="smith"');
    $dbh->exec('insert into users values("smith","' . md5("mypass") . '","John Smith","smith@cse.uta.edu")')
         or die(print_r($dbh->errorInfo(), true));
    $dbh->commit();
   $sql = 'select * from posts inner join users where posts.postedby = users.username order by datetime DESC';
  
   echo "<pre>";
   foreach ($dbh->query($sql) as $A)  
   {
      
       echo '<form>';
       echo '<input type=hidden name="replyto" value="'.$A['id'].'"/>';
       echo '<b> Message ID: </b>'.$A['id']."\n";
       if($A['replyto']!=null)
        echo'<b> Replied to a Message having Message ID as: </b>'.$A['replyto']."\n";
       echo'<b> Username: </b>'.$A['username']."\n";
       echo'<b> Full Name: </b>'.$A['fullname']."\n";
       echo'<b> Date and Time: </b>'.$A['datetime']."\n";
       echo'<b> Message: </b>'.$A['message']."\n";
       echo '<input type="text" id="reply" name="reply"/>';
       echo '<button type="submit" formaction="board.php"> Reply </button></form>';
       echo "\n\n\n\n";
  }
   echo "</pre>";
 }
 else
 {
    header('Location: login.php');
    exit();
 }

 } 
catch (PDOException $e) 
{
  echo " OOPS! An Error just Occured!: " . $e->getMessage() . "<br/>";
  die();
}
?>
</body>
</html>