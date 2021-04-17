<?php
	session_start(); //Memulai session untuk mendefinisikan variabel session saat berhasil login
	$errors = array(); //Membuat array untuk menampung error/kesalahan saat validasi form
	function usernameAvailability($username){ //Membuat fungsi apakah username registered
		$dbc = new PDO('mysql:host=localhost;dbname=forum','root',''); //Membuat koneksi db
		
		//Query untuk mengecek pada db, apakah username terdaftar
		$query = $dbc->prepare("SELECT * FROM user WHERE username = :username");
		$query->bindValue(':username', $_POST['username']);
		$query->execute();
		return $query->rowCount() > 0;
	}
	function checkPassword($username, $password, &$session, &$status){ //Membuat fungsi apakah password benar sesuai username yang registered
		$dbc = new PDO('mysql:host=localhost;dbname=forum','root',''); //Membuat koneksi db
		
		//Query untuk mengecek kecocokkan username dan password hasil input dengan data pada db
		$query = $dbc->prepare("SELECT * FROM user WHERE username = :username AND password = SHA2(:password, 0)");
		$query->bindValue(':username', $username);
		$query->bindValue(':password', $password);
		$query->execute();
		foreach ($query as $row) { //Mengambil beberapa data pada db
			$session = $row['id_user']; //parameter terdefinisi nilainya berisi id user
			$status = $row['id_level']; //parameter terdefinisi nilainya berisi status user
		}
		return $query->rowCount() > 0;
	}
	if(isset($_POST['login'])){ //Saat button login dipencet
		require 'formvalidate.inc'; //Mengambil fungsi2 yang ada di file include
		checkEmpty($errors,$_POST,'username'); //Username tidak boleh kosong
		checkEmpty($errors,$_POST,'password'); //Password tidak boleh kosong
		if($errors){ //Jika ada kesalahan dalam validasi form variabel array akan terisi, maka form akan menampilkan warning
			foreach ($errors as $field => $error){ //Mengecek isi array errors
				if ($field == 'username'){
					$userError = $error;
				}
				if ($field == 'password'){
					$passError = $error;
				}
			}
		}
		else{ //Jika tidak ada kesalahan pada validasi form, lanjut ke pengecekan ketersediaan username& password
			if (checkPassword($_POST['username'], $_POST['password'], $session, $status)){ //Jika username& password benar, maka lanjut login
				$_SESSION['isMember'] = true; //Login sebagai member true
				$_SESSION['id'] = $session; //Menyimpan infomasi id user
				if ($status == '1'){ //Menentukan status user yang login
					$_SESSION['status'] = 'client'; //Variabel session ditetapkan
				}
				else if($status == '2'){
					$_SESSION['status'] = 'expert'; //Variabel session ditetapkan
				}
			}
			else if(!usernameAvailability($_POST['username'])){ //Jika pada fungsi checkPassword tidak lolos, lalu cek username apakah terdaftar
				$userError = 'Username is not registered';
			}
			else{ //Jika lolos fungsi usernameAvaialbility berarti yang membuat tidak lolos fungsi checkPassword adalah kesalahan pada password
				$passError = 'Wrong password!';
			}
		}
	}
	if(isset($_SESSION['isMember'])){ //Jika member sudah login tetapi tanpa sengaja kembali ke halaman login, maka user dengan otomatis dikembalikan ke beranda
		if($_SESSION['isMember'] == true){
			header('Location: index.php'); //Redirect ke home
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
<body class="loginbg">
	<div class="cardlayout">
		<?php
			include 'formlogin.inc'; //Include form layout
		?>
	</div>
</body>
</html>