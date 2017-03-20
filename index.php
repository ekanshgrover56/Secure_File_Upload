<?php
/* name=Ekansh Grover(ekansh.grover@colorado.edu) 
 * filename : index.php
 * Date: 04/26/2016
 * Version 1.1
 */

session_start();
$_SESSION['ip']=$_SERVER['REMOTE_ADDR'];
$ip=$_SERVER['REMOTE_ADDR'];
include_once('/var/www/html/project/header.php');
echo"<style>body  {background-image: url(cloud-wallpaper-hd-background-5.png);background-size: cover;}</style></body>
<form method=post action=user.php><fieldset><legend>Login:</legend>
    <table><tr><td> Username: </td><td><input type=text name=postUser></td></tr>
    <tr><td>Password:</td><td> <input type=password name=postPass></td></tr>
    <tr><td colspan=10> <input type=hidden name=s value=1><input type=submit name=submit value=Login></td></tr>
   </fieldset></table></form>";
?>
