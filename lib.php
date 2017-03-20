<?php
/* name=Ekansh Grover(ekansh.grover@colorado.edu) 
 * filename : lib.php
 * Date: 04/26/2016
 * Version 1.1
 */

session_start();
function connect(&$db){
        $mycnf="/etc/project.conf";
        if (!file_exists($mycnf)){
                echo"Error file not found : $mycnf";
                exit;}
$mysql_ini_array=parse_ini_file($mycnf);
$db_host=$mysql_ini_array["host"];
$db_user=$mysql_ini_array["user"];
$db_pass=$mysql_ini_array["pass"];
$db_port=$mysql_ini_array["port"];
$db_name=$mysql_ini_array["dbName"];
$db=mysqli_connect($db_host,$db_user,$db_pass,$db_name,$db_port);
if (!$db) {
        print "Error connecting to DB: ".mysqli_connect_error();
        exit;}}

function data_usage($db,$user){
	$query="SELECT data from users WHERE username=?";
	if($stmt=mysqli_prepare($db,$query)){
		mysqli_stmt_bind_param($stmt,"s",$user);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt,$data);
		while(mysqli_stmt_fetch($stmt)){
			$data=$data;
			if($data>=20971520){
				$_SESSION['go']="no";}
			else{$_SESSION['go']="yes";}
}}}
function authenticate($db,$postUser,$postPass,$ip){
        $query="SELECT userid,email,password,salt FROM users WHERE username=?";
        if($stmt=mysqli_prepare($db,$query)){
                mysqli_stmt_bind_param($stmt,"s",$postUser);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt,$userid,$email,$password,$salt);
                while(mysqli_stmt_fetch($stmt)){
                       // session_regenerate_id();
                        $userid=$userid;
                        $password=$password;
                        $salt=$salt;
                        $email=$email;
                        $epass=hash('sha256',$postPass.$salt);
                        if($epass==$password){
                                $_SESSION['ip']=$_SERVER['REMOTE_ADDR'];
                                $_SESSION['HTTP_USER_AGENT']=md5($_SERVER['SERVER_ADDR'].$_SERVER['HTTP_USER_AGENT']);
                                $_SESSION['created']=time();
                                $_SESSION['userid']=$userid;
                                $_SESSION['email']=$email;
                                $_SESSION['authenticated']="yes";
                                $action="Success";
                        }
        else{
                $_SESSION['authenticated']=null;
                echo"Failed to Login";
                $action="Fail";
                header("Location:/project/index.php");
                }
}
mysqli_stmt_close($stmt);
$User=mysqli_real_escape_string($db,$postUser);
$ip=mysqli_real_escape_string($db,$ip);
if($stmt=mysqli_prepare($db,"INSERT INTO login SET loginid='',ip=?,user=?,date=NOW(),action=?")){
                mysqli_stmt_bind_param($stmt,"sss",$ip,$User,$action);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);}

}}
function checkauth() {
	if(isset($_SESSION['HTTP_USER_AGENT']))
		{
 		if($_SESSION['HTTP_USER_AGENT']!=md5($_SERVER['SERVER_ADDR'].$_SERVER['HTTP_USER_AGENT'])){
  			logout();}}
		else{
			logout();}
	if(isset($_SESSION['ip'])){
		if($_SESSION['ip']!=$_SERVER['REMOTE_ADDR']){
			 logout();}}
		else{
			logout();}
	
	if("POST" == $_SERVER["REQUEST_METHOD"])
		{
		if (isset($_SERVER["HTTP_ORIGIN"]))
			{
		if ($_SERVER["HTTP_ORIGIN"]!="https://100.66.1.8")
		{
		logout();}}
	else{
		logout();}}}


isset ( $_REQUEST['pUser'] ) ? $pUser = strip_tags($_REQUEST['pUser']) : $pUser = " ";
isset ( $_REQUEST['pPass'] ) ? $pPass = strip_tags($_REQUEST['pPass']) : $pPass = " ";
isset ( $_REQUEST['postE'] ) ? $postE = strip_tags($_REQUEST['postE']) : $postE = " ";
isset ( $_REQUEST['postUser'] ) ? $postUser = strip_tags($_REQUEST['postUser']) : $postUser = " ";
isset ( $_REQUEST['postPass'] ) ? $postPass = strip_tags($_REQUEST['postPass']) : $postPass = " ";
isset ( $_REQUEST['s'] ) ? $s = strip_tags($_REQUEST['s']) : $s = " ";
isset ( $_REQUEST['ip'] ) ? $ip = strip_tags($_REQUEST['ip']) : $ip = " ";
isset ( $_REQUEST['name'] ) ? $name = strip_tags($_REQUEST['name']) : $name = " ";
isset ( $_REQUEST['username'] ) ? $username = strip_tags($_REQUEST['username']) : $username = " ";
?>
