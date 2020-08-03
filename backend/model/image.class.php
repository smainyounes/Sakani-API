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
			$this->query("SELECT link FROM image WHERE id_img = :id");
			$this->bind(":id", $id_img);

			return $this->single();
		}
		
		/**
		 * Setters
		 */

		public function AddImg($id_local, $img_name)
		{
			$this->query("INSERT INTO image(id_local, link) VALUES(:id, :link)");
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
			$this->query("DELETE FROM image WHERE id_img = :id");
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