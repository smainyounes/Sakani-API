<?php 

	/**
	 * 
	 */
	class model_user extends lib_database
	{
		
		function __construct()
		{
			parent::__construct();
		}
	
		private function CheckTokkenExists($tokken)
		{
			$this->query("SELECT * FROM users WHERE tokken = :tokken");

			$this->bind(":tokken", $tokken);

			$res = $this->single();

			if ($res) {
				return true;
			}else{
				return false;
			}
		}

		public function Limiter()
		{
			$this->query("SELECT COUNT(id_user) nbr FROM users WHERE DATE(first_login) = CURDATE()");

			$res = $this->single();

			if ($res->nbr < NEW_USERS) {
				return true;
			}else{
				return false;
			}
		}

		public function NewGuest()
		{
			if (!$this->Limiter()) {
				return ['status' => 'success', 'user_tokken' => token(10) . uniqid(), 'id_user' => rand(100, 999)];
			}

			$i = 0;
			do{
				$i++;
				if ($i == 5) {
					return false;
				}

				$tokken = token(10) . uniqid();
			}while($this->CheckTokkenExists($tokken));	

			$json = json_decode(file_get_contents("http://ip-api.com/json/" . $_SERVER['REMOTE_ADDR']));

			if ($json->status === "success") {
				$sql = "INSERT INTO users(country, state, city, tokken) VALUES(:country, :state, :city, :tokken)";

				$this->query($sql);

				$this->bind(":country", $json->country);
				$this->bind(":state", $json->regionName);
				$this->bind(":city", $json->city);
			}else{
				$sql = "INSERT INTO users(tokken) VALUES(:tokken)";
				$this->query($sql);
			}

			$this->bind(":tokken", $tokken);

			try {
				$this->execute();
				return ['status' => 'success', 'user_tokken' => $tokken, 'id_user' => $this->LastId()];
			} catch (Exception $e) {
				return false;
			}
		}

		public function CheckTokken($id_user, $tokken)
		{
			$this->query("SELECT * FROM users WHERE tokken = :tokken AND id_user = :id");

			$this->bind(":tokken", $tokken);
			$this->bind(":id", $id_user);

			$res = $this->single();

			if ($res) {
				return true;
			}else{
				return false;
			}
		}
	}
 ?>