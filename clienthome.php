<?php
	$conn = new PDO('mysql:host=localhost;dbname=forum','root',''); //Membuat koneksi db

	function getReply($idTopic, $conn){ //Fungsi untuk mengambil semua reply yang ada pada topic tertentu
		//Memproses query untuk manampilkan reply pada topic
		$reply = $conn->prepare("SELECT * FROM reply, user where id_topic = :idTopic AND user.id_user = reply.id_user");
		$reply->bindValue(':idTopic',$idTopic);
		$reply->execute();

		return $reply;
	}
	function topicOwner($idTopic, $conn, &$owner){ //Fungsi untuk mencari tahu topic milik user yang sedang login
		//Memproses query untuk mencari tahu pemilik dari idTopic
		$topic = $conn->prepare("SELECT * FROM topic, user where id_topic = :idTopic AND user.id_user = topic.id_user");
		$topic->bindValue(':idTopic',$idTopic);
		$topic->execute();

		foreach ($topic as $key) {
			$owner = $key['id_user']; //Menetapkan pemilik dari idTopic
		}

		return $topic->rowCount() > 0;
	}
?>

<div class="head">
	<h1><a href="index.php">HD</a></h1>
	<nav class="horizontal">
		<ul>
			<li><a class="onpage" href="index.php">Home</a></li>
			<li><a class="navhover" href="dashboard.php">Dashboard</a></li>
			<li><a class="navhover" href="profile.php">Profile</a></li>
			<li><a class="navhover" href="logout.php">Logout</a></li>
		</ul>
	</nav>
</div>
<div class="subcontent">
	<form action="index.php" method="POST">
		<input type="text" name="subject" placeholder="Subject" size="50" class="subject" />
		<textarea name="topic" cols="80" rows="5" placeholder="Put your question here.."></textarea>
		<input type="submit" name="topinput" value="Send" class="button" />
	</form>
	<hr>
</div>
<div class="content">
	<?php
		$topics = array(); //Membuat array topic untuk menampung idtopic dari setiap baris perulangan
		$temp = 1; //Variabel bantuan, untuk index pada array
		$dbc = new PDO('mysql:host=localhost;dbname=forum','root',''); //Membuat koneksi db
	
		//Memproses query untuk menampilkan seluruh topic, yang terbaru paling atas
		$query = $dbc->prepare("SELECT * FROM topic, user WHERE topic.id_user = user.id_user ORDER BY topic.id_topic DESC");
		$query->execute();
		foreach ($query as $row) { //Perulangan untuk menampilkan topic
			$owner = ''; //Variabel bantuan untuk menampung id user dari topic yang dipilih
			$topics[$temp] = $row['id_topic']; //Menyimpan nilai id topic pada array (fungsi array untuk mengidentifikasi id topic saat ingin edit topic)
			$cont = urlencode($row['content']); //Mengencode topic agar bisa dibawa nilainya oleh link
			topicOwner($row['id_topic'],$conn,$owner); //Fungsi untuk mencari pemilik dari id topic

			if ($owner == $_SESSION['id']){ //Jika pada perulangan saat ini, pemilik id topic sama dengan session id, maka bisa diedit
				echo '<ul>';
				echo "<li class='ts li'><span>Topic Starter : {$row['username']}</span></li>";
				echo "<li class='edit-topic-btn'><span><a href='updatetopic.php?idtopic=$topics[$temp]&page=index&cont=$cont'>Edit</a></span></li>";
				echo "<li class='title li'>{$row['subject']}</li>";
				echo "<li class='thread li'>{$row['content']}</li>";
			}
			else{ //Menampilkan topic milik user lain
				echo '<ul>';
				echo "<li class='ts li'>Topic Starter : {$row['username']}</li>";
				echo "<li class='title li'>{$row['subject']}</li>";
				echo "<li class='thread li'>{$row['content']}<br/></li>";
			}
			$variable = getReply($row['id_topic'],$conn); //Mendapatkan nilai reply untuk setiap topic (tiap perulangan)
			if ($variable->rowCount() > 0){ //Jika ada reply
				echo "<li class='reply'>";
				foreach ($variable as $key) { //Menampilkan seluruh replies
					echo "<p class='ts'>Reply from {$key['username']}:</p>";
					echo "<p class='replies'>{$key['reply']}</p>";
				}
				echo "</li>";
			}
			else{ //Jika tidak ada reply
				echo "<li class='noreply'>No one reply</li>";
			}
			echo "</ul>";
			echo "<hr>";
		}
	?>
</div>