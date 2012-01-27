<?php

	class Auth
	{
		private $expires;
		private $cookie_name;
		private $logged_in;
		private $key;
		
		public function __construct()
		{
			// Expires in 10 days.
			$this->expires = time() + (60*60*24) * 10;
			$this->key = 'ojbrZdBHYnghVjcKQllNb9PYGln6ku';
			$this->cookie_name = 'bubba';
			
			if(!isset($_COOKIE[$this->cookie_name]))
			{
				$data = array
				(
					'added'		=> date("Y-m-d H:i:s", time()),
					'expires'	=> date("Y-m-d H:i:s", $this->expires),
				);
				
				setcookie($this->cookie_name, $this->encrypt($data), $this->expires, '/');
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
		
		public function logged_in()
		{
			return $this->logged_in;
		}
		
		public function login($user, $pass)
		{
			if(Pep::auth_user($user, md5($pass)))
			{
				$data = $this->decrypt($_COOKIE[$this->cookie_name]);
				$data['user'] = $user;
				$data['pass'] = md5($pass);
				
				setcookie($this->cookie_name, $this->encrypt($data), $this->expires, '/');
				$this->logged_in = true;
			}
		}
		
		public function logout()
		{
			$data = $this->decrypt($_COOKIE[$this->cookie_name]);
			unset($data['user']);
			unset($data['pass']);
			
			setcookie($this->cookie_name, $this->encrypt($data), $this->expires, '/');
			$this->logged_in = false;
		}
		
		public function encrypt($data)
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
		
		public function decrypt($data)
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