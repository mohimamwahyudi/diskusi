<?php
	require 'access.inc'; //Mengecek izin akses
	$errors = array(); //Membuat variabel untuk menampung message ketika error pada validasi
	if(isset($_GET['idtopic'])){ //Mengambil nilai dari halaman sebelumnya
		$_SESSION['current_idtopic'] = $_GET['idtopic']; //Mengambil nilai id topic yang akan diedit
		$_SESSION['previous_page'] = $_GET['page']; //Mengambil informasi halaman sebelumnya
		$cont = $_GET['cont'];
	}
	if(isset($_POST['update'])){ //Saat tombol update diklik
		require 'formvalidate.inc'; //Mengambil fungsi-fungsi form validasi
		checkEmpty($errors,$_POST,'topic'); //Cek apakah inputan kosong
		if($errors){ //Jika array errors ada isinya, maka proses submit tidak diproses
			foreach ($errors as $field => $error) { //Mengecek isi array errors
				if ($field == 'topic') {
					$topError = 'No Input';
				}
			}
		}
		else{
			if($_POST['subject'] != ''){ //Jika textbox pada subject tidak kosong, maka subject diupdate, jika kosong diabaikan
				//Proses untuk update query pada subject
				$dbc = new PDO('mysql:host=localhost;dbname=forum','root','');
				
				$query = $dbc->prepare("UPDATE topic SET subject = :subject WHERE id_topic = {$_SESSION['current_idtopic']}");
				$query->bindValue(':subject', $_POST['subject']);
				$query->execute();
			}
			//Proses untuk update query pada topic
			$dbc = new PDO('mysql:host=localhost;dbname=forum','root','');
			
			$query = $dbc->prepare("UPDATE topic SET content = :content WHERE id_topic = {$_SESSION['current_idtopic']}");
			$query->bindValue(':content', $_POST['topic']);
			$query->execute();
			if ($_SESSION['previous_page'] == 'dashboard') { //Jika halaman sebelumnya adalah dashboard
				unset($_SESSION['current_idtopic']);
				unset($_SESSION['previous_page']);
				header('Location: dashboard.php'); //Maka selesai update, kembali ke dashboard
				exit();
			}
			else{ //Jika halaman sebelumnya adalah home
				unset($_SESSION['current_idtopic']);
				unset($_SESSION['previous_page']);
				header('Location: index.php'); //Maka selesai update, kembali ke home
				exit();
			}
		}
	}
	else if(isset($_POST['cancel'])){ //Jika cancel update, kembali ke halaman sebelumnya sesuai informasi halaman sebelumnya
		if ($_SESSION['previous_page'] == 'dashboard') { //Jika halaman sebelumnya dashboard
			unset($_SESSION['current_idtopic']);
			unset($_SESSION['previous_page']);
			header('Location: dashboard.php'); //Kembali ke dashboard
			exit();
		}
		else{ //Jika halaman sebelumnya home
			unset($_SESSION['current_idtopic']); 
			unset($_SESSION['previous_page']);
			header('Location: index.php'); //Kembali ke home
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
<body>
	<form action="updatetopic.php" method="POST">
		<div class="updateinit" id="loginhead">
			<input type="text" name="subject" placeholder="Subject" size="50" class="subject" />
			<?php
				if (isset($cont)){ //Jika variabel sementara telah ter-set
					echo "<textarea name='topic' cols='113' rows='5'>".$cont."</textarea>"; //Textbox ada nilai dari topic lama
				}
				else{
					echo "<textarea name='topic' cols='113' rows='5' placeholder='Put your question here..'></textarea>"; //Textbox menampilkan petunjuk untuk mengisi isi topic yang baru
				}
				if (isset($topError)){ //Menampilkan pesan error ketika inputan update topic kosong
					echo "<p class='upd'>$topError</p>";
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