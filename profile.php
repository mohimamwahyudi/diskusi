<?php
	require 'access.inc'; //Mengecek izin akses
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Project PAW</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="head">
		<h1><a href="index.php">HD</a></h1>
		<nav class="horizontal">
			<ul>
				<li><a class="navhover" href="index.php">Home</a></li>
				<li><a class="navhover" href="dashboard.php">Dashboard</a></li>
				<li><a class="onpage" href="profile.php">Profile</a></li>
				<li><a class="navhover" href="logout.php">Logout</a></li>
				
			</ul>
		</nav>
	</div>
	<div class="contentprofile">
		<ul class='centered'>
			<?php
				if(isset($_SESSION['status'])){ //Mengecek status dari user
					if($_SESSION['status'] == 'client'){ //Avatar icon untuk client
						echo "<li><img src='assets/client.png' alt='https://icons8.com/icon/set/profile/color' width='200' height='200'></li>";
					}
					else{ //Avatar icon untuk expert
						echo "<li><img src='assets/expert.png' alt='https://icons8.com/icon/set/profile/color' width='200' height='200'></li>";
					}
				}
			?>
		</ul>
		<?php
			$dbc = new PDO('mysql:host=localhost;dbname=forum','root',''); //Membuat koneksi db
		
			//Memproses query untuk menampilkan seluruh profile user
			$query = $dbc->prepare("SELECT * FROM user,level WHERE :id = user.id_user AND level.id_level = user.id_level");
			$query->bindValue(':id', $_SESSION['id']);
			$query->execute();
			foreach ($query as $row) { //Perulangan untuk menampilkan data
				$namalama = urlencode($row['nama_lengkap']); //Encode nama agar bisa dibawa oleh link bila nama ada spasi
				$_SESSION['usernamecheck'] = $row['username']; //Variabel session untuk bantuan saat edit username

				//Menampilkan profile user beserta tombol edit di sisi kanannya
				echo "<ul>";
				echo "<li class='lishade'><span class='pad'>Status Level</span><span class='bigpad'>: {$row['keterangan']}</span></li>"; //Status tidak bisa diedit
				echo "<li class='lishade'><span class='pad'>Nama Lengkap</span><span class='bigpad'>: {$row['nama_lengkap']}</span><span class='f-right'><a class='black bold' href='updatenama.php?namalama=$namalama'>O</a></span></li>"; //Nama dengan tombol edit membawa value nama
				echo "<li class='lishade'><span class='pad'>Umur</span><span class='bigpad'>: {$row['umur']}</span><span class='f-right'><a class='black bold' href='updateumur.php?umurlama={$row['umur']}'>O</a></span></li>"; //Umur dengan tombol edit membawa value umur
				echo "<li class='lishade'><span class='pad'>Email</span><span class='bigpad'>: {$row['email']}</span><span class='f-right'><a class='black bold' href='updateemail.php?emaillama={$row['email']}'>O</a></span></li>"; //Email dengan tombol edit membawa value email
				echo "<li class='lishade'><span class='pad'>Username</span><span class='bigpad'>: {$row['username']}</span><span class='f-right'><a class='black bold' href='updateusername.php?userlama={$row['username']}'>O</a></span></li>"; //Username dengan tombol edit membawa value username
				echo "<li class='lishade'><span class='pad'>Password</span><span class='bigpad'>: ***</span><span class='f-right'><a class='black bold' href='updatepassword.php'>O</a></span></li>"; //Password dengan tombol edit tidak membawa value password karena user harus mengetik password sendiri
				echo "</ul>";
			}
		?>
	</div>
</body>
</html>