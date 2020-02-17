<?php
$image_number = @$_GET['ss'];
if($image_number == 'upload'){ header("Location: //www.cookiee.net/ss/upload/"); }
if($image_number != intval($image_number)){ header("Location: //www.cookiee.net/404.html"); }

$mysqli_server = mysqli_connect("host", "username", "username", "databasename", port);
if (mysqli_connect_errno()){
	die('Connect Error: '.mysqli_connect_error());
}
if(!empty($image_number)){
	$upl_result = mysqli_query($mysqli_server, 'select * from upl_upload_table WHERE upl_index = '.$image_number);
	if($upl_result == false){die('Image is not available');} //결과 없음
	$upl_name = mysqli_fetch_object($upl_result);
	if(empty($upl_name->upl_name)){die('Image is not available');}
	$upl_name = $upl_name->upl_name;
	mysqli_free_result($upl_result);
	mysqli_close($mysqli_server);
	if(empty($upl_name)){
		echo 'Image is not available';
	}else{
		$imgSize = getimagesize("./.files/".$upl_name);
		echo '<body bgcolor="black">';
		echo '<title>'.$upl_name.' ('.$imgSize[0].'x'.$imgSize[1].')</title>';
		echo '<div style="text-align: center"><img src="./img/'.$upl_name.'" alt="Image" style="height:98%; width:98% top:50%; left:50%; max-height:'.$imgSize[1].'px"/></div>';
		echo '</body>';
	}
}else{
	echo 'Image is not available';
}
?>