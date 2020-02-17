<?php
	date_default_timezone_set('Asia/Seoul');
	$mysqli_server = mysqli_connect("host", "username", "username", "databasename", port);
	if (mysqli_connect_errno()){
		die('Connect Error: '.mysqli_connect_error());
	}
	
	$headers = apache_request_headers();
	$allowed_ext = array('jpg','jpeg','png','gif');	
	$time_stamp = date("Y-m-d H:i:s");
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$uploaddir = str_replace(basename(__FILE__), "", realpath(__FILE__)).".files/";
		
		$tmp = explode('.',$_FILES['file']['name']);
		$file_ext = strtolower(end($tmp));
		$upl_result = mysqli_query($mysqli_server, 'select * from upl_upload_table order by upl_index desc limit 1');
		$image_number = intval(mysqli_fetch_object($upl_result)->upl_index)+1;
		$uploadfile = $uploaddir . $image_number .'.'. $file_ext;
		$upl_result = mysqli_query($mysqli_server, 'INSERT INTO upl_upload_table(upl_index, upl_name, upl_date) VALUES('.$image_number.', "'.$image_number.'.'.$file_ext.'", "'.$time_stamp.'")');
		mysqli_close($mysqli_server);
		
		if(in_array($file_ext,$allowed_ext) === false){
			echo '-1';
			return -1;
		}else if($headers['Content-Length'] > 3145728){ //3MB
			echo '-2';
			return -2;
		}else{
			if(is_uploaded_file($_FILES["file"]["tmp_name"])){
				if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)){
					echo '{"File_size":"' . $_FILES["file"]["size"] . ' bytes", "File_status":' . $_FILES["file"]["error"] . ', "File_link":"https://www.cookiee.net/ss/'.$image_number.'"}';
				}
			}
		}
	}else{
		echo 'Err! The Request is not Post!';
	}
?>