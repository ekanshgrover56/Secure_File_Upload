<?php
/* name=Ekansh Grover(ekansh.grover@colorado.edu) 
 * filename : lib.php
 * Date: 04/26/2016
 * Version 1.1
 */

session_start();
include_once('/var/www/html/project/header.php');
include_once('/var/www/html/project/lib.php');
if(isset($_FILES['file'])){
	//echo "INSIDE"; 
	$file=$_FILES['file'];
	$file_name=$file['name'];
	$file_tmp=$file['tmp_name'];
	$file_size=$file['size'];
	$file_ext=explode('.',$file_name);
	$file_ext=strtolower(end($file_ext));
	$allowed=array('txt','jpg','pdf');
	
	if(in_array($file_ext,$allowed)){
		//echo"INSIDE 2"; 
		if($file_error==0){
			//echo "error";
			if($file_size<=2097152){
				$name_file=$_FILES['file']['name'];
				$tmp_name=$_FILES['file']['tmp_name'];
				$local_dir="upload/";
				//echo $name_file,$tmp_name,$local_dir;
				$upload=move_uploaded_file($tmp_name,$local_dir.$name_file);
				if($upload){
					echo 'uploaded this file '.$name_file.$postUser;
					connect($db);
					if($stmt=mysqli_prepare($db,"INSERT INTO files SET fileid='',name=?,owner=?")){
						mysqli_stmt_bind_param($stmt,"ss",$name_file,$_SESSION['postUser']);
						mysqli_stmt_execute($stmt);
						mysqli_stmt_close($stmt); }
					if($stmt=mysqli_prepare($db,"UPDATE users SET data=data + ? WHERE username=?")){
						mysqli_stmt_bind_param($stmt,"ss",$file_size,$_SESSION['postUser']);
						mysqli_stmt_execute($stmt);
						mysqli_stmt_close($stmt);}}
				else{echo"LOL";}}}}}
?>
