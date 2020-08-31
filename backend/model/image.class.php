<?php 

	/**
	 * 
	 */
	class model_image extends lib_database
	{
		
		function __construct()
		{
			parent::__construct();
		}

		/**
		 * Getters
		 */

		public function GetImages($id_local)
		{
			$this->query("SELECT * FROM image WHERE id_local = :id");
			$this->bind(":id", $id_local);

			return $this->resultSet();
		}

		public function GetImgLink($id_img)
		{
			$this->query("SELECT lien FROM image WHERE id_image = :id");
			$this->bind(":id", $id_img);

			$res = $this->single();
			return $res->lien;
		}
		
		public function CheckImg($id_local, $id_img)
		{
			$this->query("SELECT id_image FROM image WHERE id_image = :id_img AND id_local = :id_local");

			$this->bind(":id_img", $id_img);
			$this->bind(":id_local", $id_local);

			$res = $this->single();

			if ($res) {
				return true;
			}else{
				return false;
			}
		}

		/**
		 * Setters
		 */

		public function AddImg($id_local, $img_name)
		{
			$this->query("INSERT INTO image(id_local, lien) VALUES(:id, :link)");
			$this->bind(":id", $id_local);
			$this->bind(":link", $img_name);

			try {
				$this->execute();
				return array('state' => 'success', 'data' => ['filename' => PUBLIC_URL.'img/'.$img_name, 'id_img' => $this->LastId()]);
				
			} catch (Exception $e) {
				return array('state' => 'error', 'data' => ['msg' => 'img was not inserted in the database']);
			}
		}

		public function DeleteImg($id_img)
		{
			$this->query("DELETE FROM image WHERE id_image = :id");
			$this->bind(":id", $id_img);

			try {
				$this->execute();
				return true;
			} catch (Exception $e) {
				return false;
			}
		}

		public function DeleteAllImgLocal($id_local)
		{
			$this->query("DELETE FROM image WHERE id_local = :id");
			$this->bind(":id", $id_local);

			try {
				$this->execute();
				return true;
			} catch (Exception $e) {
				return false;
			}
		}

		public function SelectMain($id_local, $id_img)
		{
			if (!$this->CheckImg($id_local, $id_img)) {
				return false;
			}

			$this->ResetMain($id_local);

			$this->query("UPDATE image SET main = 1 WHERE id_image = :id");

			$this->bind(":id", $id_img);

			try {
				$this->execute();
				return true;
			} catch (Exception $e) {
				return false;
			}
		}

		public function ResetMain($id_local)
		{
			$this->query("UPDATE image SET main = 0 WHERE id_local = :id");

			$this->bind(":id", $id_local);

			try {
				$this->execute();
				return true;
			} catch (Exception $e) {
				return false;
			}
		}

	}


 ?>