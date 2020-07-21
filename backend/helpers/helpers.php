<?php 

	function shortenText($text, $chars = 15)
	{
	
		if (strlen($text) > $chars+1) // if you want...
		{
		    $text = substr($text, 0, $chars);
		    return $text." ...";
		}else{
			return $text;
		}
	}


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

 ?>