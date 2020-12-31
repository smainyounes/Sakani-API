<?php 

	/**
	 * 
	 */
	class model_history extends lib_database
	{
		
		function __construct()
		{
			parent::__construct();
		}

		public function Search($id_user, $wilaya, $commune, $type, $vl)
		{
			$this->query("INSERT INTO search_history(id_user, wilaya, commune, type, vl) VALUES(:id_user, :wilaya, :commune, :type, :vl)");

			$this->bind(":id_user", $id_user);
			$this->bind(":wilaya", $wilaya);
			$this->bind(":commune", $commune);
			$this->bind(":type", $type);
			$this->bind(":vl", $vl);

			try {
				$this->execute();
				return true;
			} catch (Exception $e) {
				return false;
			}
		}

		public function Detail($id_user, $id_local, $action = "click")
		{
			$this->query("INSERT INTO local_history(id_user, id_local, action) VALUES(:id_user, :id_local, :action)");

			$this->bind(":id_user", $id_user);
			$this->bind(":id_local", $id_local);
			$this->bind(":action", $action);

			try {
				$this->execute();
				return true;
			} catch (Exception $e) {
				return false;
			}
		}

		public function CountVuLocal($id_local)
		{
			$this->query("SELECT COUNT(DISTINCT id_user) AS nbr FROM local_history WHERE id_local = :id");

			$this->bind(":id", $id_local);

			return $this->single()->nbr;
		}

		public function CountVuLocalByDate($id_local, $date)
		{
			$this->query("SELECT COUNT(DISTINCT id_user) AS nbr FROM local_history WHERE id_local = :id AND DATE(time_clicked) = :date");

			$this->bind(":id", $id_local);
			$this->bind(":date", $date);

			return $this->single()->nbr;
		}
	}

 ?>