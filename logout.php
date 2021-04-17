<?php
	require 'access.inc'; //Cek izin akses
	unset($_SESSION['isMember']); //Meng-unset variabel session untuk isMember
	unset($_SESSION['id']); //Meng-unset variabel session untuk id
	unset($_SESSION['status']); //Meng-unset variabel session untuk status
	unset($_SESSION['usernamecheck']); //Meng-unset variabel session untuk usernamecheck
	if(isset($_POST['ok'])){ //Jika user menekan tombol ok, maka dialihkan ke home. Dan karena di home ada izin akses member juga, maka user akan diarahkan ke halaman welcome atau halaman awal
		header('Location: index.php');
		exit();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Project PAW</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="cardlayout">
		<form action="logout.php" method="POST">
			<div class="logoutinit">
				<input type="submit" name="ok" value="OK" class="button" />
			</div> 
		</form>
	</div>
</body>
</html>