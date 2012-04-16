<?php
	define ('HOSTNAME', 'http://ccmixter.org/api/query');


	$url = HOSTNAME."?".$_SERVER['QUERY_STRING'];

	$session = curl_init($url);

	curl_setopt($session, CURLOPT_HEADER, false);
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

	$result = curl_exec($session);

	echo $result;
	curl_close($session);
?>
