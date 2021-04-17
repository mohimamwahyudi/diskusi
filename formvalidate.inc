<?php 
	function checkEmpty(&$errors, $field_list, $field_name){
		if(!isset($field_list[$field_name]) || empty($field_list[$field_name])){ //Cek agar form tidak kosong
			$errors[$field_name] = 'Required! Field is not allowed to be empty';
		}
	}
	function validateName(&$errors, $field_list, $field_name){
		$pattern = "/^[a-zA-Z ]+$/"; //Pattern untuk nama, bisa huruf bisa spasi
		if(!isset($field_list[$field_name]) || empty($field_list[$field_name])){
			$errors[$field_name] = 'Required! Field is not allowed to be empty'; //Cek agar form tidak kosong
		}
		else if(!preg_match($pattern, $field_list[$field_name])){
			$errors[$field_name] = 'Field should be alphabetical'; //Selain alfabet dan spasi, dilarang
		}
	}
	function validateEmail(&$errors, $field_list, $field_name){
		if(!isset($field_list[$field_name]) || empty($field_list[$field_name])){
			$errors[$field_name] = 'Required! Field is not allowed to be empty'; //Cek agar form tidak kosong
		}
		else if(!filter_var($field_list[$field_name], FILTER_VALIDATE_EMAIL)){
			$errors[$field_name] = 'Email format is not valid'; //Format email harus benar
		}
	}
	function validatePass(&$errors, $field_list, $field_name){
		$alpha = "/[a-zA-Z]/"; //Alfabet
		$num = "/[0-9]/"; //Numerik
		$antisimbol = "/^[a-zA-Z0-9]+$/"; //Selain karakter yang ada pada pattern ini akan invalid
		if(!isset($field_list[$field_name]) || empty($field_list[$field_name])){
			$errors[$field_name] = 'Required! Field is not allowed to be empty'; //Cek agar form tidak kosong
		}
		else if(!(preg_match($alpha, $field_list[$field_name]) && preg_match($num, $field_list[$field_name]) && preg_match($antisimbol, $field_list[$field_name])) || strlen($field_list[$field_name]) < 8){
			$errors[$field_name] = 'Password should be alphanumeric with 8 characters or more'; //Password harus mengandung alfanumerik tanpa simbol satupun dan minimal panjang harus 8 karakter
		}
	}
	function validateConfirm(&$errors, $field_list, $field_name, $field_name2){
		if(!isset($field_list[$field_name]) || empty($field_list[$field_name])){
			$errors[$field_name] = 'Required! Field is not allowed to be empty'; //Cek agar form tidak kosong
		}
		else if($field_list[$field_name] != $field_list[$field_name2]){
			$errors[$field_name] = 'Password confirmation must match password'; //Confirmation Password harus sama dengan Password
		}
	}
	function validateUsed(&$errors, $field_list, $field_name, $field_name2){
		if(!isset($field_list[$field_name]) || empty($field_list[$field_name])){
			$errors[$field_name] = 'Required! Field is not allowed to be empty'; //Cek agar form tidak kosong
		}
		else if($field_list[$field_name] == $field_list[$field_name2]){
			$errors[$field_name] = 'Username already used'; //Username tidak boleh sama dengan user lain
		}
	}
	function validateAge(&$errors, $field_list, $field_name){
		if (is_numeric($field_list[$field_name])){
			$num = intval($field_list[$field_name]); //Jika input termasuk hanya numerik, maka buat variabel tambahan untuk mengubah ke integer (untuk menghilangkan float)
		}
		if(!isset($field_list[$field_name]) || empty($field_list[$field_name])){
			$errors[$field_name] = 'Required! Field is not allowed to be empty'; //Cek agar form tidak kosong
		}
		else{
			if (!isset($num)){
				$errors[$field_name] = 'Field should be numerical'; //Jika variabel tambahan tadi tidak terdefinisi, maka inputan bukan numerik
			}
			else{
				if (!($num == $field_list[$field_name])){ //Jika variabel tambahan tadi nilainya tidak sama dengan inputan asli, berarti inputan bukan integer (inputan tidak boleh float)
					$errors[$field_name] = 'Should be integer';
				}
			}
		}
	}
?>