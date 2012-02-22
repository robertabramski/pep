<?php

	class Auth
	{
		private $expires;
		private $cookie_name;
		private $logged_in;
		private $key;
		
		public function __construct()
		{
			$this->expires = time() + (60*60*24) * floatval(Pep::get_setting('cookie_expiration'));
			$this->key = Pep::get_setting('cookie_key');
			$this->cookie_name = Pep::get_setting('cookie_name');
			
			if(!isset($_COOKIE[$this->cookie_name]))
			{
				setcookie($this->cookie_name, $this->encrypt(array()), $this->expires, '/', '', false, true);
			}
			else
			{
				$saved = $_COOKIE[$this->cookie_name];
				setcookie($this->cookie_name, $saved, $this->expires, '/', '', false, true);
			}
			
			$this->check_login();
		}
		
		private function check_login()
		{	
			$this->logged_in = false;
			
			$data = $this->decrypt($_COOKIE[$this->cookie_name]);
			 
			$user = $data['user'];
			$pass = $data['pass'];
			
			if($user && $pass) $this->logged_in = Pep::auth_user($user, $pass);
		}
		
		public function authed_user($value = '')
		{
			$user = Pep::get_authed_user();
			return empty($value) ? $user : $user[$value];
		}
		
		public function logged_in()
		{
			return $this->logged_in;
		}
		
		public function not_logged_in()
		{
			return !$this->logged_in;
		}
		
		public function login($user, $pass)
		{
			if(Pep::auth_user($user, md5($pass)))
			{
				$data = $this->decrypt($_COOKIE[$this->cookie_name]);
				$data['user'] = $user;
				$data['pass'] = md5($pass);
				
				setcookie($this->cookie_name, $this->encrypt($data), $this->expires, '/', '', false, true);
				$this->logged_in = true;
			}
			else
			{
				$this->logged_in = false;
			}
			
			return $this->logged_in;
		}
		
		public function logout()
		{
			$data = $this->decrypt($_COOKIE[$this->cookie_name]);
			unset($data['user']);
			unset($data['pass']);
			
			setcookie($this->cookie_name, $this->encrypt($data), $this->expires, '/', '', false, true);
			$this->logged_in = false;
		}
		
		private function encrypt($data)
		{
			return base64_encode
			(
				mcrypt_encrypt
				(
					MCRYPT_RIJNDAEL_256, 
					md5($this->key), 
					serialize($data), 
					MCRYPT_MODE_CBC, 
					md5(md5($this->key))
				)
			);
		}
		
		private function decrypt($data)
		{
			return unserialize
			(
				rtrim
				(
					mcrypt_decrypt(MCRYPT_RIJNDAEL_256, 
					md5($this->key), 
					base64_decode($data), 
					MCRYPT_MODE_CBC, 
					md5(md5($this->key))), "\0"
				)
			);
		}
	}
	
?>