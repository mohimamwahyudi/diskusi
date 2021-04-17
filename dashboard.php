<?php
	require 'access.inc'; //Mengecek izin akses
	require 'formvalidate.inc'; //Mengambil seluruh fungsi untuk validasi form
	$errors = array(); //Membuat array untuk menampung error saat validasi form
	$dbc = new PDO('mysql:host=localhost;dbname=forum','root',''); //Membuat koneksi db
	if (isset($_POST['submit_balasan'])) { //Ketika mengklik button kirim reply
		checkEmpty($errors,$_POST,'balasan'); //Cek apakah inputan reply kosong
		if($errors){ //Jika ada kesalahan dalam validasi form variabel array akan terisi, maka form akan menampilkan warning
			foreach ($errors as $field => $error){ //Mengecek isi array errors
				if ($field == 'balasan'){
					$balError = $error;
				}
			}
		}
		else{
			//Memproses query untuk insert reply ke db
	        $query = $dbc->prepare("INSERT INTO reply VALUES (NULL, :balasan, :id_user, :id_topic);");
	        $query->bindValue(':balasan', $_POST['balasan']);
	        $query->bindValue(':id_user', $_POST['id_user']);
	        $query->bindValue(':id_topic', $_POST['id_topic']);
	        $query->execute();
	        header("Location: dashboard.php"); //Setelah insert reply ke db, kembali merefresh page dashboard
	        exit();
	    }
    }
    if (isset($_POST['simpan_edit_reply'])) { //Ketika selesai mengedit reply
		checkEmpty($errors,$_POST,'editan_baru'); //Cek apakah hasil edit reply kosong
    	if($errors){ //Jika ada kesalahan dalam validasi form variabel array akan terisi, maka form akan menampilkan warning
			foreach ($errors as $field => $error){ //Mengecek isi array errors
				if ($field == 'editan_baru'){
					$balError = $error;
				}
			}
		}
		else{
			//Memproses query untuk update reply
	        $update = $dbc->prepare("UPDATE reply SET reply.reply = :edit WHERE reply.id_reply = :id_reply");
	        $update->bindValue(':edit', $_POST['editan_baru']);
	        $update->bindValue(':id_reply', $_POST['id_reply']);
	        $update->execute();
	        header("Location: dashboard.php");  //Setelah edit reply, merefresh page dashboard
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
<body>
	<?php
		if(isset($_SESSION['status'])){ //Setelah member punya izin akses, maka status sudah ter-set
			if($_SESSION['status'] == 'client'){ //Sebagai client maka menampilkan dashboard client
					include 'clientdashboard.php';

			}
			else{ //Sebagai expert maka menampilkan dashboard expert
				include 'expertdashboard.php';
			}
		}
	?>
</body>
</html>