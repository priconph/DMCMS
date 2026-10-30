<?php
	require_once("view_only.page_header.php");
	/* If page does not exist load home page */
	// require_once('../../common_functions/get_username.php');
	require_once('../common_functions/get_username.php');
	if(!isset($_GET['page'])){
		$page = "view_only.home.php";
	}else{
		$page = $_GET['page'];
	}
	require_once($page);
	require_once("page_footer.php");
?>