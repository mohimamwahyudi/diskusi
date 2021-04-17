<?php
	require 'access.inc'; //Membutuhkan izin akses
	$errors = array(); //Membuat variabel array
	if(isset($_GET['umurlama'])){
		$umurlama = $_GET['umurlama']; //Mengambil nilai data lama yang ingin di edit
	}
	if(isset($_POST['update'])){ //Jika menekan button update
		require 'formvalidate.inc'; //Mengambil fungsi-fungsi untuk validasi form
		validateAge($errors,$_POST,'umur'); //Memvalidasi umur
		if($errors){ //Jika terdapat error
			foreach ($errors as $field => $error) { //Mengecek isi error
				if ($field == 'umur') {
					$ageError = $error;
				}
			}
		}
		else{ //Jika tidak terdapat error
			$dbc = new PDO('mysql:host=localhost;dbname=forum','root',''); //Membuat db connection
			
			//Memproses query untuk mengupdate umur user sesuai session id member yang sedang login
			$query = $dbc->prepare("UPDATE user SET umur = :umur WHERE id_user = {$_SESSION['id']}");
			$query->bindValue(':umur', $_POST['umur']);
			$query->execute();
			header('Location: profile.php'); //Selesai update, redirect ke halaman profile
			exit();
		}
	}
	else if(isset($_POST['cancel'])){ //Jika menekan button cancel
		header('Location: profile.php'); //Kembali ke halaman profile tanpa perubahan apa-apa
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
	<form action="updateumur.php" method="POST" >
		<div class="updateinit" id="loginhead">
			<?php
				if(isset($umurlama)){ //Jika terdapat data lama, maka isi form akan terdapat value
					echo "<input type='text' name='umur' size='27' class='updatetext' value='$umurlama' />";
				}
				else{ //Jika mereset form, maka isi form akan kosong
					echo "<input type='text' name='umur' size='27' class='updatetext' placeholder='Umur Baru' />";
				}
			?>
			<?php
					if (isset($ageError)){ //Jika terdapat error pada umur
						echo "<p class='upd'>$ageError</p>";
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