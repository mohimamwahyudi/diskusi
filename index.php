<?php
	require 'access.inc'; //Mengecek izin akses
	require 'formvalidate.inc'; //Mengambil fungsi-fungsi validasi
	$errors = array(); //Membuat variabel untuk menampung message ketika error pada validasi 
	$dbc = new PDO('mysql:host=localhost;dbname=forum','root',''); //Membuat koneksi db
	if(isset($_POST['topinput'])){ //Ketika mengklik button kirim topic
		checkEmpty($errors,$_POST,'topic'); //Cek apakah inputan topic kosong
		if($errors){ //Jika array errors ada isinya, maka proses submit tidak diproses
			foreach ($errors as $field => $error){ //Mengecek isi array errors
				if ($field == 'topic'){
					$topiError = $error;
				}
			}
		}
		else{
			if($_POST['subject'] == ''){ //Jika inputan subject kosong, akan diizinkan tetapi value subject menjadi 'No Subject'
				$sub = 'No Subject';
			}
			else{
				$sub = $_POST['subject'];
			}
			
			//Proses query untuk insert topic ke db
			$query = $dbc->prepare("INSERT INTO topic (subject, content, id_user) VALUES (:subject, :content, :id_user)");
			$query->bindValue(':subject', $sub);
			$query->bindValue(':content', $_POST['topic']);
			$query->bindValue(':id_user', $_SESSION['id']);
			$query->execute();
			header('Location: index.php'); //Setelah insert topic ke db, kembali merefresh page home
			exit();
		}
	}
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
	        header("Location: index.php"); //Setelah insert reply ke db, kembali merefresh page home
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
	        header("Location: index.php"); //Setelah edit reply, merefresh page home
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
			if($_SESSION['status'] == 'client'){ //Sebagai client maka menampilkan home client
					include 'clienthome.php';

			}
			else{ //Sebagai expert maka menampilkan home expert
				include 'experthome.php';
			}
		}
	?>
</body>
</html>