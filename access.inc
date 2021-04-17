<?php
	session_start(); //Memulai session
	if (!isset($_SESSION['isMember'])){ //Jika bukan merupakan member/jika belum login
		header("Location: welcome.php"); //Maka user diarahkan ke halaman welcome atau halama awal
		exit();
}
?>