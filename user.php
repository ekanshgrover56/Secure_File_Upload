<?php
/* name=Ekansh Grover(ekansh.grover@colorado.edu) 
 * filename : user.php
 * Date: 04/26/2016
 * Version 1.1
 */

session_start();
$ip=$_SERVER['REMOTE_ADDR'];
$_SESSION['ip']=$_SERVER['REMOTE_ADDR'];

include_once('/var/www/html/project/lib.php');
include_once('/var/www/html/project/header.php');

connect($db);
if(!isset($_SESSION['postUser'])){
	$_SESSION['postUser']=$postUser;
	authenticate($db,$postUser,$postPass,$ip);
}else{
        $query="SELECT ?, count(?) FROM login WHERE date BETWEEN DATE_SUB(NOW(),INTERVAL 1 HOUR) AND NOW() AND action='Fail'";
        if($stmt=mysqli_prepare($db,$query)){
                mysqli_stmt_bind_param($stmt,"ss",$ip,$ip);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt,$ip,$count);
                while(mysqli_stmt_fetch($stmt)){
                        $count=$count;}}
                if($count>5){
                        header("Location:/project/index.php");
                        exit;}
                else{
                        if(!isset($_SESSION['authenticated'])){
                                authenticate($db,$postUser,$postPass,$ip);}}}

checkauth();


switch($s){
	default:
	case 1:
		if(!isset($_SESSION['postUser'])){
	        	$_SESSION['postUser']=$postUser;
		}
		//echo $_SESSION['postUser'];
		echo"<br>WELCOME $postUser <br>";
		echo"<br><br><a href=user.php?s=2> FILES UPLOADED </a><br>";
		echo"<br><br><a href=user.php?s=3> UPLOAD FILE </a><br>";
		echo"<br><br><a href=user.php?s=41> SHARING FILES </a><br>";
 		echo"<br><br><a href=user.php?s=100> FILES SHARED WITH YOU </a><br>";
		if($_SESSION['postUser']=="admin"){
			echo"<br><br><a href=user.php?s=77> ADD USER </a><br>";
			echo"<br><br><a href=user.php?s=190> ACCESS LIST </a><br>";}
		echo"<br><br><a href=user.php?s=911> LOGOUT </a><br>";
		echo"</center>";
		break;
 		
	case 2:
		
		echo"<table><tr><td><u><b> FILES </b></u></td></tr>\n";
		if($stmt=mysqli_prepare($db,"SELECT name FROM files WHERE owner=?")){
			mysqli_stmt_bind_param($stmt,"s",$_SESSION['postUser']);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt,$name);
			while(mysqli_stmt_fetch($stmt)){
				$name=htmlspecialchars($name);
				echo"<tr><td><a href=upload/".$name.">$name</td></a>\n";}
				mysqli_stmt_close($stmt);}echo"</table>";
			break;
	case 3:
		$user=$_SESSION['postUser'];
		data_usage($db,$user);
		if($_SESSION['go']=="yes"){	
		echo"
		<form action=upload.php method=post enctype=multipart/form-data>
		<fieldset><legend>Upload File:</lengend>
		Select File to upload:
		<input type=file name=file>
		<input type=submit value=Upload>
		<input type=hidden name=postUser value=".$postUser.">
		</fieldset> </form>
		</body>";
		break;}else{echo"YOU DONT HAVE ANY SPACE IN YOUR ACCOUNT";}
	case 41:
		echo"<table><tr><td><u><b> WHICH FILE YOU WANT TO SHARE </b></u></td></tr>
			<form method=POST action=user.php>
			<fieldset><lengend> Select File:</legend>";
		if($stmt=mysqli_prepare($db,"select name from files where owner=?")){
                        mysqli_stmt_bind_param($stmt,"s",$_SESSION['postUser']);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_bind_result($stmt,$name);
                        while(mysqli_stmt_fetch($stmt)){
                                $name=htmlspecialchars($name);
                                echo"<tr><td><a href=upload/".$name.">$name</td></a>\n";}
                        mysqli_stmt_close($stmt);}
			echo"<tr><td>NAME OF THE FILE</td><td><input type=text id=name name=\"name\" value=\"\"></td></tr>";
			echo"<tr><td><input type=hidden name=s value=42><input type=submit name=submit value=submit></td></tr></fieldset></form></table>";
                        break;
	case 42:
		echo"<table><tr><td><u><b> WITH WHOM YOU WANT TO SHARE $name </b></u></td></tr>";
		echo"<form method=POST action=user.php>
			<fieldset><legend> Select User:</lengend>";
                if($stmt=mysqli_prepare($db,"SELECT username FROM users;")){
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_bind_result($stmt,$username);
                        while(mysqli_stmt_fetch($stmt)){
                                $username=htmlspecialchars($username);
                                echo"<tr><td>".$username."</td></a>\n";}
                         echo"<tr><td>NAME OF THE USER</td><td><input type=text id=name name=\"username\" value=\"\"></td></tr>";
                        echo"<tr><td><input type=hidden name=s value=43><input type=hidden name=name value=".$name."><input type=submit name=submit value=submit></td></tr></fieldset></form></table>";        
			mysqli_stmt_close($stmt);}echo"</table>";
                        break;
	case 43:
		if($stmt=mysqli_prepare($db,"insert into share (name,user) values (?,?);")){
			mysqli_stmt_bind_param($stmt,"ss",$name,$username);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);}
		echo"File is shared";
		break;
	case 100:
                echo"<table><tr><td><u><b> FILES SHARED WITH YOU </b></u></td></tr>\n";
                if($stmt=mysqli_prepare($db,"SELECT name FROM share WHERE user=?")){
                        mysqli_stmt_bind_param($stmt,"s",$_SESSION['postUser']);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_bind_result($stmt,$name);
                        while(mysqli_stmt_fetch($stmt)){
                                $name=htmlspecialchars($name);
                                echo"<tr><td><a href=upload/".$name.">$name</td></a>\n";}
                                mysqli_stmt_close($stmt);}echo"</table>";
                        break;
	
	case 77:
		if($_SESSION['postUser']=="admin"){		
		echo"
		<center><form method=post action=user.php>
		<fieldset><legend> Add User </legend>
        	<table><tr><td>Username:</td><td><input type=text name=pUser value=\"\"></td></tr>
             	<tr><td>Password:</td><td><input type=password name=pPass value=\"\"></td></tr>
             	<tr><td>Email:</td><td><input type=text name=postE value=\"\"></td></tr>
             	<tr><td colspan=2><input type=hidden name=s value=78><input type=submit name=submit value=Create></td></tr></table></fieldset></form></center>";
 		break;}
		else{echo"WATCH YOUR BACK";
			break;}
	case 78:
		$pUser=mysqli_real_escape_string($db,$pUser);
       	 	$pPass=mysqli_real_escape_string($db,$pPass);
        	$postE=mysqli_real_escape_string($db,$postE);
        	$salt=hash('sha256',$postE);
        	$P=hash('sha256',$pPass.$salt);
        	if($stmt=mysqli_prepare($db,"INSERT INTO users SET userid='',username=?,password=?,salt=?,email=?,data=0")){
                	mysqli_stmt_bind_param($stmt,"ssss",$pUser,$P,$salt,$postE);
                	mysqli_stmt_execute($stmt);
                	mysqli_stmt_close($stmt);}
        	header("Location:/project/user.php");
  		break;
	case 190:
		if($_SESSION['postUser']=="admin"){
                	echo "<table><tr><td><b><u> LOGS </b></u> </td></tr> \n";
                	$query="select ip,user,date,action from login";
                	$result=mysqli_query($db,$query);
                	while($row=mysqli_fetch_row($result)){
                        	echo" <tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td></tr> \n";}
                        	echo "</table>";
   		                }else{echo"YOU ARE NOT ADMIN";}
            	break;
	case 911:
		session_destroy();
	        header("Location:/project/index.php");
        	break;

} 
?>


