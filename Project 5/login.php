<?php
  // CSE 5335: Web Data Management Project 5
  // Project 5: A Message Board using PHP and MySQL
  // Name: Shagun Paul
  // UTA ID: 1001557958
?>
<html>
<head>
<style>
input[type=text]
{
    background-color: white ;
    color: #3CBC8D;
}
input[type=password]
{
    background-color: white;
    color: #3CBC8D ;
}
</style>
</head>
<body style="background-color: #D7D2CF;">
  <h2 align="center" style="color: #6E464A; font-family: 'Comic Sans MS';"> A Message Board using PHP and MySQL </h2>
  <form name="form" action="board.php" method="POST">
              <table align="center" border="0">
                  <tbody>
                      <tr>
                          <br><br>
                          <td style="background-color: #D7D2CF;"><b> Login: </b></td>
                          <td><input type="text" id="uname" name="uname"/></td>
                      </tr>
                      <tr>
                          <td style="background-color: #D7D2CF;"><b><br> Password: </br></b></td>
                          <td><br><input type="password" id="pass" name="pass"/></br></td>
                      </tr>
                     
                  </tbody>
              </table>
              <center><b><br><input type="submit" name="sub" id="sub"/></br></b></center>
          </form>
</body>
</html>