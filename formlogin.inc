<form action="login.php" method="POST" >
	<div class="logininit" id="loginhead">
		<label>Login</label>
	</div>
	<div class="logininit">
		<input type="text" name="username" size="27" placeholder="Username" class="logintext" />
		<?php
				if (isset($userError)){ //Jika terdapat kesalahan pada username
					echo "<p>$userError</p>";
				}
		?>
	</div>
	<div class="logininit">
		<input type="password" name="password" size="27" placeholder="Password" class="logintext" />
		<?php
				if (isset($passError)){ //Jika terdapat kesalahan pada password
					echo "<p>$passError</p>";
				}
		?>
	</div>
	<div class="logininit">
		<input class="button" type="submit" value="Login" name="login"/>
	</div>
	<div class="logininit">
		<input class="button" type="submit" value="Reset" name="reset"/>
	</div>
	<hr class="logindivider">
	<div class="askaccount">
		<label>Don't have an account?</label>
	</div>
	<div class="logininit">
		<a class="button" href="registrasi.php">Sign Up</a>
	</div>
</form>