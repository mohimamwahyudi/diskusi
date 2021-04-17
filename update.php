<?php
	require 'access.inc';
	if(isset($_POST['update'])){
		$dbc = new PDO('mysql:host=localhost;dbname=forum','root','');
		
		$query = $dbc->prepare("UPDATE user SET password = SHA2(:password,0) WHERE id_user = {$_SESSION['id']}");
		$query->bindValue(':password', $_POST['password']);
		$query->execute();
		header('Location: profile.php');
		exit();
	}
	elseif (isset($_POST['update_profil_expert'])) {
		$dbc = new PDO ('mysql:host=localhost;dbname=forum','root','');
		$dbc ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
				$query=$dbc->prepare("UPDATE user SET nama_lengkap= :nama, email= :email,umur= :umur WHERE id_user = {$_SESSION['id']}");
				$query->bindValue(':nama', $_POST['nama']);
		        $query->bindValue(':email', $_POST['email']);
		        $query->bindValue(':umur', $_POST['umur']);
		        $query->execute();
				if($query->rowCount()>0){
					header('Location:index.php');
					exit();

				}
			
		} catch (PDOException $e) {
			echo $e -> getMassage();
			
		}
		
	
		
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<?php
		include 'formupdate.inc';
	?>
</body>
</html>