<?php
	require 'access.inc'; //Membutuhkan izin akses
	$errors = array(); //Membuat variabel array
	if(isset($_GET['namalama'])){ 
		$namalama = $_GET['namalama']; //Mengambil nilai data lama yang ingin di edit
	}
	if(isset($_POST['update'])){ //Jika menekan button update
		require 'formvalidate.inc'; //Mengambil fungsi-fungsi untuk validasi form
		validateName($errors,$_POST,'name'); //Memvalidasi nama
		if($errors){ //Jika terdapat error
			foreach ($errors as $field => $error) { //Mengecek isi error
				if ($field == 'name') {
					$namError = $error;
				}
			}
		}
		else{ //Jika tidak terdapat error
			$dbc = new PDO('mysql:host=localhost;dbname=forum','root',''); //Membuat db connection
			
			//Memproses query untuk mengupdate nama user sesuai session id member yang sedang login
			$query = $dbc->prepare("UPDATE user SET nama_lengkap = :nama_lengkap WHERE id_user = {$_SESSION['id']}");
			$query->bindValue(':nama_lengkap', $_POST['name']);
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
	<form action="updatenama.php" method="POST" >
		<div class="updateinit" id="loginhead">
			<?php
				if(isset($namalama)){ //Jika terdapat data lama, maka isi form akan terdapat value
					echo "<input type='text' name='name' size='27' class='updatetext' value='$namalama' />";
				}
				else{ //Jika mereset form, maka isi form akan kosong
					echo "<input type='text' name='name' size='27' class='updatetext' placeholder='Nama Baru' />";
				}
			?>
			<?php
					if (isset($namError)){ //Jika terdapat error pada nama
						echo "<p class='upd'>$namError</p>";
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