<?php
	session_start(); //Memulai session
	if(isset($_SESSION['isMember'])){ //Jika member yang sudah login kembali ke halaman welcome, maka akan dihalangi dan diarahkan kembali ke halaman home/index
		if($_SESSION['isMember'] == true){
			header('Location: index.php');
			exit();
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Project</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body id="welcome">
	<div class="buttonlayout">
		<a href="login.php" class="welcomebtn">Already Have an Account?</a>
		<a href="registrasi.php" class="welcomebtn">Become a Member Now!</a>
	</div>
</body>
</html>