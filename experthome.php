<?php
	$conn = new PDO('mysql:host=localhost;dbname=forum','root',''); //Membuat koneksi db

	//Memproses query untuk menampilkan seluruh topic, yang terbaru paling atas
	$topik = $conn->prepare("SELECT * FROM topic, user WHERE topic.id_user = user.id_user ORDER BY topic.id_topic DESC");
	$topik->execute();

	function getReply($idTopic, $conn){ //Untuk mengambil reply pada setiap topic
		//Memproses query untuk menampilkan reply sesuai topicnya
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
			<li><a class="onpage" href="index.php">Home</a></li>
			<li><a class="navhover" href="dashboard.php">Dashboard</a></li>
			<li><a class="navhover" href="profile.php">Profile</a></li>
			<li><a class="navhover" href="logout.php">Logout</a></li>
		</ul>
	</nav>
</div>
<div class="content">
	<?php
		foreach ($topik as $row) { //Perulangan untuk menampilkan topic
			echo '<ul>';
			echo "<li class='ts li'>Topic Starter : {$row['username']}</li>";
			echo "<li class='title li'>{$row['subject']}</li>";
			echo "<li class='thread li'>{$row['content']}<br/></li>";
			$variable = getReply($row['id_topic'],$conn); //Mengambil replies untuk setiap topic
			if ($variable->rowCount() > 0){ //Jika ada reply
				echo "<li class='reply'>";
				foreach ($variable as $key) { //Perulangan untuk menampilkan reply
					if ($key['id_user'] == $_SESSION['id']) { //Jika id user pada reply sama dengan session id, maka bisa diedit
						if (isset($_POST['edit_reply']) && $key['id_reply'] == $_POST['reply_yg_diedit']) { //Jika tombol edit topic diklik
							//Maka akan muncul 1 baris form untuk edit reply
							echo "<p class='ts'>Reply from me:</p>";
							echo "<form action=\"index.php\" method=\"POST\">";
							echo "<div class=\"replying\">";
							echo "<input type=\"text\" name=\"editan_baru\" value=\"{$key['reply']}\" class=\"updreplytext\">";
							echo "<input type=\"hidden\" name=\"id_reply\" value=\"{$key['id_reply']}\">";
							echo "<button type=\"submit\" name=\"simpan_edit_reply\" class=\"buttonupdreply\"><img src=\"assets/send-button.png\" width=\"30\" height=\"30\" alt=\"https://www.flaticon.com/authors/google\"></button>";
							echo "</div>";
							echo "</form>";
						}
						else { //Jika tidak klik tombol edit, maka tidak dalam kondisi edit. Dan tampil reply normal
							echo "<form action=\"index.php\" method=\"POST\">";
							echo "<div class='reset'>";
							echo "<p class='tsreply'>Reply from me:</p>";
							echo "<input class=\"buttoneditreply\" type=\"submit\" value=\"Edit\" name=\"edit_reply\">";
							echo "</div>";
							echo "<input type=\"hidden\" value=\"{$key['id_reply']}\" name=\"reply_yg_diedit\">";
							echo "</form>";
							echo "<p class='replies'>{$key['reply']}</p>";
						}
					}
					else { //menampilkan reply dari expert lain
						echo "<p class='ts'>Reply from {$key['username']}:</p>";
						echo "<p class='replies'>{$key['reply']}</p>";
					}
				}
				echo "</li>";
			}
			else{ //Jika tidak ada reply
				echo "<li class='noreply'>No one reply</li>";
			}

			//Form textbox dan button untuk membuat reply
			echo "<li class=\"replying\">";
			echo "<form action=\"index.php\" method=\"POST\">";
			echo "<div class=\"replying\">";
			echo "<input type=\"text\" name=\"balasan\" placeholder=\"Type here to reply\" class=\"replytext\">";
			echo "<button type=\"submit\" name=\"submit_balasan\" class=\"buttonreply\"><img src=\"assets/send-button.png\" width=\"30\" height=\"30\" alt=\"https://www.flaticon.com/authors/google\"></button>";
			echo "</div>";
			echo "<input type=\"hidden\" value=\"{$_SESSION['id']}\" name=\"id_user\">";
			echo "<input type=\"hidden\" value=\"{$row['id_topic']}\" name=\"id_topic\">";
			echo "</form>";
			echo "</li>";
			echo "</ul>";
			echo "<hr>";
		}
	?>
</div>