<?php
	$topics = array(); //Membuat array topic untuk menampung idtopic dari setiap baris perulangan
	$temp = 1; //Variabel bantuan, untuk index pada array
	$dbc = new PDO('mysql:host=localhost;dbname=forum','root',''); //Membuat koneksi db

	//Query untuk menampilkan seluruh topic milik user yang sedang login, yang terbaru paling atas
	$query = $dbc->prepare("SELECT * FROM topic, user WHERE topic.id_user = user.id_user AND user.id_user = :id ORDER BY topic.id_topic DESC");
	$query->bindValue(':id', $_SESSION['id']);
	$query->execute();

	function getReply($idTopic, $conn){ //Fungsi untuk mengambil semua reply yang ada pada topic tertentu
		//Memproses query untuk manampilkan reply pada topic
		$reply = $conn->prepare("SELECT * FROM reply, user where id_topic = :idTopic AND user.id_user = reply.id_user");
		$reply->bindValue(':idTopic',$idTopic);
		$reply->execute();

		return $reply;
	}
?>

<div class="head">
	<h1><a href="index.php">HD</a></h1>
	<nav class="horizontal">
		<ul>
			<li><a class="navhover" href="index.php">Home</a></li>
			<li><a class="onpage" href="dashboard.php">Dashboard</a></li>
			<li><a class="navhover" href="profile.php">Profile</a></li>
			<li><a class="navhover" href="logout.php">Logout</a></li>
		</ul>
	</nav>
</div>
<?php
	if($query->rowCount() < 1){ //Jika user yang sedang login belum membuat satupun topic/thread
		echo '<div class="dashboard-content">';
		echo '<ul>';
		echo "<li class='nothread'>You haven't post any thread<br/></li>";
		echo "</ul>";
		echo "</div>";
	}
	else{ //Jika yser yang sedang login sudah pernah membuat topic/thread
		echo '<div class="content">';
		foreach ($query as $row) { //Perulangan untuk menampilkan topic
			$topics[$temp] = $row['id_topic']; //Menyimpan nilai id topic pada array (fungsi array untuk mengidentifikasi id topic saat ingin edit topic)
			$cont = urlencode($row['content']); //Mengencode topic agar bisa dibawa nilainya oleh link

			//Menampilkan topic
			echo '<ul>';
			echo "<li class='title li'>{$row['subject']}</li>";
			echo "<li class='edit-topic-btn'><a href='updatetopic.php?idtopic=$topics[$temp]&page=dashboard&cont=$cont'>Edit</a></li>";
			echo "<li class='thread li'>{$row['content']}<br/></li>";
			$variable = getReply($row['id_topic'],$dbc); //Mendapatkan data-data raplies untuk setiap topic
			if ($variable->rowCount() > 0){ //Jika ada reply
				foreach ($variable as $key) { //Perulangan untuk reply
					//Menampilkan reply
					echo "<li class='reply'>";
					echo "<p class='ts'>Reply from {$key['username']}:</p>";
					echo "<p class='replies'>{$key['reply']}</p>";
			        echo "</li>";
				}
			}
			else{ //Jika tidak ada reply
				echo "<li class='noreply'>No one reply</li>";
			}
			echo "</ul>";
			echo "<hr>";
			$temp += 1;
		}
		echo '</div>';
	}
?>