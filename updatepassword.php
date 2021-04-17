<?php
	require 'access.inc'; //Mengecek izin akses
	$errors = array(); //Membuat variabel array
	if(isset($_POST['update'])){ //Jika menekan button update
		require 'formvalidate.inc'; //Mengambil fungsi-fungsi untuk validasi form
		validatePass($errors,$_POST,'password'); //Memvalidasi password
		validateConfirm($errors, $_POST, 'confirm', 'password'); //Memvalidasi confirmation password
		if($errors){ //JIka terdapat error
			foreach ($errors as $field => $error) { //Mengecek isi error
				if ($field == 'password') { //Jika password terdapat kesalahan
					$pasError = $error;
				}
				if ($field == 'confirm') { //Jika confirmation password tidak sama
					$conError = $error;
				}
			}
		}
		else{ //Jika tidak terdapat error
			$dbc = new PDO('mysql:host=localhost;dbname=forum','root',''); //Membuat db connection
			
			//Memproses query untuk mengupdate password user sesuai session id member yang sedang login
			$query = $dbc->prepare("UPDATE user SET password = SHA2(:password,0) WHERE id_user = {$_SESSION['id']}");
			$query->bindValue(':password', $_POST['password']);
			$query->execute();
			header('Location: profile.php'); //Selesai update, kembali ke page profile
			exit();
		}
	}
	else if(isset($_POST['cancel'])){ //Jika menekan button cancel
		header('Location: profile.php'); //Kembali ke page profile tanpa melakukan perubahan
		exit();
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Project</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<form action="updatepassword.php" method="POST" >
		<div class="updateinit">
			<input type="password" name="password" size="27" class="updatetext" placeholder="Password Baru" />
			<?php
					if (isset($pasError)){ //Jika terdapat kesalahan pada password
						echo "<p class='upd'>$pasError</p>";
					}
			?>
		</div>
		<div class="updateinit">
			<input type="password" name="confirm" size="27" class="updatetext" placeholder="Re-Enter Password" />
			<?php
					if (isset($conError)){
						echo "<p>$conError</p>"; //Jika terdapat kesalahan pada confirmation password
					}
			?>
		</div>
		<div class="updateinit">
			<input class="button" type="submit" value="Update" name="update"/>
			<input class="button" type="submit" value="Reset" name="reset"/>
			<input class="button" type="submit" value="Cancel" name="cancel"/>
		</div>
	</form>
</body>
</html>