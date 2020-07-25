<?php 

	function DeletePic($link)
	{
		if (file_exists($link)) {
			return unlink($link);
		}

		return false;
	}

	function token($length = 20) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ&:,';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	function UploadPics()
	{
		$upname = "";
		$realname = "";
		$error = "";

		// image mime to be checked 
		$imagetype = array(image_type_to_mime_type(IMAGETYPE_GIF), image_type_to_mime_type(IMAGETYPE_JPEG),
		    image_type_to_mime_type(IMAGETYPE_PNG), image_type_to_mime_type(IMAGETYPE_BMP));
		
		$FOLDER = "img/";
		$myfile = $_FILES["imgprod"];
		$keepName = false; // change this for file name.
		for ($i = 0; $i < count($myfile["name"]); $i++) {
		    if ($myfile["name"][$i] <> "" && $myfile["error"][$i] == 0) {
		        // file is ok
		        if (in_array($myfile["type"][$i], $imagetype)) {
		            //Set file name
		            if($keepName) {
		                $file_name =  $myfile["name"][$i];
		            } else {
		                // get extention and set unique name
		                $file_extention = @strtolower(@end(@explode(".", $myfile["name"][$i])));
		                $file_name = date("Ymd") . '_' . rand(10000, 990000) . '.' . $file_extention;
		            }
		            if (!move_uploaded_file($myfile["tmp_name"][$i], $FOLDER . $file_name)) {
		            	$error = "file not moved";
		            }
		        } else {
		        	$error = "invalid file type";
		        }
		    }
		    $all[] = array("filename"=> $myfile["name"][$i], "uploadedname"=> $file_name, "error"=> $error);
		}		

		return $all;
	}

	function compressImage($source, $destination, $quality = 90)
	{
		$info = getimagesize($source);
		switch ($info['mime']) {
			case 'image/jpeg':
				$image = imagecreatefromjpeg($source);
				break;
			case 'image/png':
				$image = imagecreatefrompng($source);
				break;
			case 'image/gif':
				$image = imagecreatefromgif($source);
				break;
		}
	     imagejpeg($image, $destination, $quality);
	}

	function UploadPic($file, $dir = "img/", $prefix = "")
	{
		$imagetype = array(image_type_to_mime_type(IMAGETYPE_GIF), image_type_to_mime_type(IMAGETYPE_JPEG),
		    image_type_to_mime_type(IMAGETYPE_PNG), image_type_to_mime_type(IMAGETYPE_BMP));

		if ($file['name'] !== "" && $file['error'] == 0) {
			// file uploaded
			if (in_array($file["type"], $imagetype)) {
				// accepted file type
				$file_extention = @strtolower(@end(@explode(".", $file["name"])));
				$file_name = $prefix."_". date("YmdHis") . rand(10000, 9999999) . ".";

				if ($file['size'] < (2 * 1000 * 1000)) {
					// perfect size 
					if (move_uploaded_file($file['tmp_name'], $dir . $file_name . $file_extention)) {
						// file moved
						return array('status' => 'success', 'filename' => $file_name . $file_extention);
					}else{
						return array('status' => 'error', 'msg' => 'file could not be moved');
					}
				}else{
					// file too big so compress
					compressImage($file["tmp_name"], $dir . $file_name . "jpeg");
					if (file_exists($dir . $file_name . "jpeg")) {
						// file been compressed
						return array('status' => 'success', 'filename' => $file_name . "jpeg");
					}else{
						// file wasnt compressed
						return array('status' => 'error', 'msg' => 'file could not be compressed');
					}					
				}
			}else{
				// file type not accepted
				return array('status' => 'error', 'msg' => 'file type not accepted');
			}
		}else{
			// file didnt upload
			return array('status' => 'error', 'msg' => 'file could not be uploaded');
		}
	}

 ?>