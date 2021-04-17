<?php 
	$dbc = new PDO ('mysql:host=localhost;dbname=forum','root',''); //Membuat koneksi db
	//Memproses query untuk menampilkan seluruh topic yang telah direply oleh user yang sedang login, topic yang terbaru paling atas
	$statement = $dbc->prepare("SELECT * FROM user c, reply a,topic b WHERE a.id_user = :id AND a.id_topic = b.id_topic AND b.id_user = c.id_user GROUP BY b.id_topic ORDER BY b.id_topic DESC");
	$statement->bindValue(':id', $_SESSION['id']);
	$statement->execute();

	function getReply($idTopic, $conn){ //Mendapatkan reply dari topic
		//Query untuk menampilkan seluruh reply untuk setiap idtopic
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
	if($statement->rowCount() < 1){ //Jika user yang sedang login belum pernah mereply topic manapun
		echo '<div class="dashboard-content">';
		echo '<ul>';
		echo "<li class='nothread'>You haven't reply any thread<br/></li>";
		echo "</ul>";
		echo "</div>";
    } 
    else{ //Jika user yang sedang login sudah pernah mereply topic
		echo '<div class="content">';
		foreach ($statement as $row) { //Perulangan untuk menampilkan topic yang sudah pernah dibalas oleh user yang sedang login
			//Menampilkan topic
			echo '<ul>';
			echo "<li class='ts li'>Topic Starter : {$row['username']}</li>";
			echo "<li class='title li'>{$row['subject']}</li>";
			echo "<li class='thread li'>{$row['content']}<br/></li>";
			$variable = getReply($row['id_topic'],$dbc); //Mendapatkan reply di setiap topic
			if ($variable->rowCount() > 0){ //Tentu saja harus ada reply
				foreach ($variable as $key) { //Perulangan untuk menampilkan reply
					echo "<li class='reply'>";
			    	if (isset($_POST['edit_reply']) && $key['reply'] == $_POST['reply_yg_diedit']) { //Jika button edit reply diklik maka memasuki kondisi sedang edit reply
			    		//Pada kondisi ini, akan muncul 1 baris form untuk edit reply
		            	echo "<form action=\"dashboard.php\" method=\"POST\">";
				        echo "<p class='ts'>Reply from me:</p>";
		            	echo "<div class=\"replying\">";
			            echo "<input type=\"text\" name=\"editan_baru\" value=\"{$key['reply']}\" class='updreplytext'>";
				        echo "<input type=\"hidden\" name=\"id_reply\" value=\"{$key['id_reply']}\">";
						echo "<button type=\"submit\" name=\"simpan_edit_reply\" class=\"buttonupdreply\"><img src=\"assets/send-button.png\" width=\"30\" height=\"30\" alt=\"https://www.flaticon.com/authors/google\"></button>";
				        echo "</div>";
			            echo "</form>";
			        	echo "</li>";
		    		}
				    else if($_SESSION['id'] == $key['id_user']){ //Memfilter reply, hanya menampilkan reply dari user yang sedang login
				    	//Menampilkan reply
				        echo "<form action=\"dashboard.php\" method=\"POST\">";
				        echo "<p class='ts'>Reply from me:</p>";
					    echo "<input class=\"buttoneditreply\" type=\"submit\" value=\"Edit\" name=\"edit_reply\">";
					    echo "<p class='replies'>{$key['reply']}</p>";
				        echo "<input type=\"hidden\" value=\"{$key['reply']}\" name=\"reply_yg_diedit\">";
				        echo "</form>";
				        echo "</li>";
				    }
				}
			}
			
			//Form textbox dan button untuk membuat reply
		    echo "<li class=\"replying\">";
			echo "<form action=\"dashboard.php\" method=\"POST\">";
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
    	echo '</div>';
    }
?>