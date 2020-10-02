<?php 

	/**
	 * 
	 */
	class model_admin extends lib_database
	{
		
		function __construct()
		{
			parent::__construct();
		}

		public function AddAdmin()
		{
			if ($_POST['password'] !== $_POST['repassword']) {
				return false;
			}

			$this->query("INSERT INTO admin(username, password) VALUES(:username, :password)");

			$this->bind(":username", $_POST['username']);
			$this->bind(":password", password_hash($_POST['password'], PASSWORD_DEFAULT));

			try {
				$this->execute();
				return true;
			} catch (Exception $e) {
				return false;
			}
		}

		public function ChangePassword()
		{
			$this->query("SELECT * FROM admin WHERE id_admin = :id");

			$this->bind(":id", $_SESSION['admin']);

			$res = $this->single();

			if (password_verify($_POST['oldpass'], $res->password)) {
				$this->query("UPDATE admin SET password = :password WHERE id_admin = :id");

				$this->bind(":password", $_POST['password']);
				$this->bind(":id", $_SESSION['admin']);

				try {
					$this->execute();
					return true;
				} catch (Exception $e) {
					return false;
				}

			}

			return false;
		}

		public function Login()
		{
			$this->query("SELECT * FROM admin WHERE username = :username");

			$this->bind(":username", $_POST['username']);

			$res = $this->single();

			if ($res && password_verify($_POST['password'], $res->password)) {

				$_SESSION['admin'] = $res->id_admin;
				$this->UpdateLastLogin($res->id_admin);

				return true;				
			}else{
				return false;
			}
		}

		public function Logout()
		{
			// remove all session variables
			session_unset(); 

			// destroy the session
			session_destroy(); 
		}

		private function UpdateLastLogin($id_admin)
		{
			$this->query("UPDATE admin SET last_login = CURRENT_TIMESTAMP() WHERE id_admin = :id");

			$this->bind(":id", $id_admin);

			$this->execute();
		}

		public function ListAdmins()
		{
			$this->query("SELECT * FROM admin");

			return $this->resultSet();
		}

		public function DetailAdmin()
		{
			$this->query("SELECT * FROM admin WHERE id_admin = :id");

			$this->bind(":id", $_SESSION['admin']);

			return $this->single();
		}

		public function EtatAgence($id_agence, $etat)
		{
			if ($etat !== "active" && $etat !== "desactive" && $etat !== "ban" && $etat !== "pending") {
				return false;
			}

			$this->query("UPDATE agence SET etat_agence = :etat WHERE id_agence = :id");

			$this->bind(":etat", $etat);
			$this->bind(":id", $id_agence);

			try {
				$this->execute();
				return true;
			} catch (Exception $e) {
				return false;
			}
		}

	}

 ?>