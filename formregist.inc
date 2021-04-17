<form action="registrasi.php" method="POST" >
	<div class="registinit" id="registhead">
		<label>Register</label>
	</div>
	<div class="registinit">
		<input type="text" name="name" size="27" class="registtext" placeholder="Nama" />
		<?php
				if (isset($namError)){ //Jika terdapat kesalahan pada nama
					echo "<p>$namError</p>";
				}
		?>
	</div>
	<div class="registinit">
		<input type="text" name="email" size="27" class="registtext" placeholder="Email" />
		<?php
				if (isset($emaError)){ //Jika terdapat kesalahan pada email
					echo "<p>$emaError</p>";
				}
		?>
	</div>
	<div class="registinit">
		<input type="text" name="age" size="27" class="registtext" placeholder="Age" />
		<?php
				if (isset($ageError)){ //Jika terdapat kesalahan pada umur
					echo "<p>$ageError</p>";
				}
		?>
	</div>
	<div class="registinit">
		<input type="text" name="username" size="27" class="registtext" placeholder="Username" />
		<?php
				if (isset($cheError)){ //Jika terdapat kesalahan pada username
					echo "<p>$cheError</p>";
				}
		?>
	</div>
	<div class="registinit">
		<input type="password" name="password" size="27" class="registtext" placeholder="Password" />
		<?php
				if (isset($pasError)){ //Jika terdapat kesalahan pada password
					echo "<p>$pasError</p>";
				}
		?>
	</div>
	<div class="registinit">
		<input type="password" name="confirm" size="27" class="registtext" placeholder="Re-Enter Password" />
		<?php
				if (isset($conError)){ //Jika terdapat kesalahan pada confirmation password
					echo "<p>$conError</p>";
				}
		?>
	</div>
	<div class="registbtn">
		<label class="label">Register as:</label>
		<select name="status" class="button">
			<option value="1">Client</option>
			<option value="2">Expert</option>
		</select>
	</div>
	<div class="registbtn">
		<input class="button" type="submit" value="Sign Up" name="regist"/>
	</div>
	<div class="registbtn">
		<input class="button" type="submit" value="Reset" name="reset"/>
	</div>
	<hr class="registdivider">
	<div class="askaccount">
		<label>Already have an account?</label>
	</div>
	<div class="logininit">
		<a class="button" href="login.php">Sign In</a>
	</div>
</form>