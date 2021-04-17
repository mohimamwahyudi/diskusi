<?php
	require 'access.inc'; //Membutuhkan izin akses
	$errors = array(); //Membuat variabel array
	function checkUsername(&$errors, $username){ //Fungsi untuk mengecek ketersediaan username, apakah sudah dipakai oleh user lain
		$dbc = new PDO('mysql:host=localhost;dbname=forum','root',''); //Membuat koneksi db

		//Memproses query untuk mengecek apakah username telah dipakai oleh user lain
		$check = $dbc->prepare("SELECT username FROM user WHERE username = :username");
		$check->bindValue(':username', $username);
		$check->execute();
		$pattern = "/^[a-zA-Z_]+$/"; //Selain karakter yang ada pada pattern ini akan invalid
		if(!isset($username) || empty($username)){ //Username tidak boleh kosong
			$errors['checkuse'] = 'Required! Field is not allowed to be empty'; //Cek apakah kosong
		}
		else if(!preg_match($pattern, $username)){ //Username hanya boleh alfabet, garis bawah diperbolehkan
			$errors['checkuse'] = 'Field should be alphabetical';
		}
		else if ($check->rowCount() > 0){ //Username telah dipakai user lain
			$errors['checkuse'] = 'Username already used';
		}
	}
	if(isset($_GET['userlama'])){ 
		$userlama = $_GET['userlama']; //Mengambil nilai data lama yang ingin di edit
	}
	if(isset($_POST['update'])){ //Jika menekan button update
		require 'formvalidate.inc'; //Mengambil fungsi-fungsi untuk validasi form
		checkUsername($errors,$_POST['username']); //Memvalidasi username
		if($errors){ //Jika terdapat error
			foreach ($errors as $field => $error) { //Mengecek isi error
				if ($field == 'checkuse') {
					if($_POST['username'] == $_SESSION['usernamecheck']){ //Jika username terdapat pada database maka terdeteksi sebagai "telah digunakan user lain", tapi jika username milik sendiri tidak ada perubahan maka akan dikembalikan ke page profile tanpa melakukan perubahan
						header('Location: profile.php');
						exit();
					}
					else{
						$cheError = $error;
					}
				}
			}
		}
		else{ //Jika tidak terdapat error
			$dbc = new PDO('mysql:host=localhost;dbname=forum','root',''); //Membuat db connection
			
			//Memproses query untuk mengupdate username user sesuai session id member yang sedang login
			$query = $dbc->prepare("UPDATE user SET username = :username WHERE id_user = {$_SESSION['id']}");
			$query->bindValue(':username', $_POST['username']);
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
	<form action="updateusername.php" method="POST" >
		<div class="updateinit" id="loginhead">
			<?php
				if(isset($userlama)){ //Jika terdapat data lama, maka isi form akan terdapat value
					echo "<input type='text' name='username' size='27' class='updatetext' value='$userlama' />";
				}
				else{ //Jika mereset form, maka isi form akan kosong
					echo "<input type='text' name='username' size='27' class='updatetext' placeholder='Username Baru' />";
				}
			?>
			<?php
					if (isset($cheError)){ //Jika terdapat error pada username
						echo "<p class='upd'>$cheError</p>";
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