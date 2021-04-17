<?php
	session_start(); //Memulai session agar jika user yang telah login kembali ke form regist/form login, maka akan dikembalikan ke home
	$errors = array(); //Membuat array untuk menampung error/kesalahan saat validasi form
	function checkUsername(&$errors, $username){ //Fungsi untuk mengecek ketersediaan username, apakah sudah dipakai oleh user lain
		$dbc = new PDO('mysql:host=localhost;dbname=forum','root',''); //Membuat koneksi db

		//Memproses query untuk menampilkan username
		$check = $dbc->prepare("SELECT username FROM user WHERE username = :username");
		$check->bindValue(':username', $username);
		$check->execute();
		$pattern = "/^[a-zA-Z_]+$/"; //Selain karakter pada pattern akan invalid
		if(!isset($username) || empty($username)){ //Username tidak boleh kosong
			$errors['checkuse'] = 'Required! Field is not allowed to be empty';
		}
		else if(!preg_match($pattern, $username)){ //Username hanya boleh alfabet, garis bawah diperbolehkan
			$errors['checkuse'] = 'Field should be alphabetical';
		}
		else if ($check->rowCount() > 0){ //Username telah dipakai user lain
			$errors['checkuse'] = 'Username already used';
		}
	}
	if(isset($_POST['regist'])){ //Saat button register dipencet
		require 'formvalidate.inc'; //Mengambil fungsi untuk validasi form
		validateName($errors, $_POST, 'name'); //Memvalidasi nama yang ingin didaftarkan
		validateAge($errors, $_POST, 'age'); //Memvalidasi umur yang ingin didaftarkan
		validateEmail($errors, $_POST, 'email'); //Memvalidasi email yang ingin didaftarkan
		validatePass($errors, $_POST, 'password'); //Memvalidasi password yang ingin didaftarkan
		validateConfirm($errors, $_POST, 'confirm', 'password'); //Memvalidasi confirmation password
		checkUsername($errors, $_POST['username']); //Memvalidasi dan mengecek ketersediaan username, tidak boleh sama dengan user lain
		if($errors){ //Jika ada kesalahan dalam validasi form variabel array akan terisi, maka form akan menampilkan warning
			foreach ($errors as $field => $error) { //Mengecek isi error
				if ($field == 'name') {
					$namError = $error;
				}
				if ($field == 'password') {
					$pasError = $error;
				}
				if ($field == 'confirm'){
					$conError = $error;
				}
				if ($field == 'checkuse'){
					$cheError = $error;
				}
				if ($field == 'email'){
					$emaError = $error;
				}
				if ($field == 'age'){
					$ageError = $error;
				}
			}
		}
		else{ //Jika tidak ada kesalahan validasi, lanjut ke pemasukkan data user baru ke database
			if(isset($_POST['status'])){ //Penetapan status sesuai data yang diinputkan
				switch ($_POST['status']) {
					case '1':
						$status = '1'; //Status keterangan client
						break;
					case '2':
						$status = '2'; //Status keterangan expert
						break;
				}
			}
			
			$dbc = new PDO('mysql:host=localhost;dbname=forum','root',''); //Membuat koneksi db

			//Memproses query untuk insert data yang didaftarkan ke db
			$query = $dbc->prepare("INSERT INTO user (nama_lengkap, umur, email, username, password, id_level) VALUES (:nama_lengkap, :umur, :email, :username, SHA2(:password,0), :id_level)");
			$query->bindValue(':nama_lengkap', $_POST['name']);
			$query->bindValue(':umur', $_POST['age']);
			$query->bindValue(':email', $_POST['email']);
			$query->bindValue(':username', $_POST['username']);
			$query->bindValue(':password', $_POST['password']);
			$query->bindValue(':id_level', $status);
			$query->execute();
			header('Location: login.php'); //Selesai insert data, redirect ke halaman login
			exit();
		}
	}
	if(isset($_SESSION['isMember'])){ //Jika sudah login tetapi kembali ke form registrasi, maka dihalangi dan dikembalikan ke page home/index
		if($_SESSION['isMember'] == true){
			header('Location: index.php'); //Diarahkan ke home/index
			exit();
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Project PAW</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body class="registbg">
	<div class="cardlayout" id="registlayout">
		<?php
			include 'formregist.inc'; //Include form layout
		?>
	</div>
</body>
</html>