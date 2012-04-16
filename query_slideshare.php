<?php
require_once('lib/config.php');

$search = $_GET['search'];
$limit = ($_GET['limit'])?$_GET['limit']:15;
$cc = ($_GET['cc'])?$_GET['cc']:0;
$cc_adapt = ($_GET['cc_adapt'])?$_GET['cc_adapt']:0;
$cc_commercial = ($_GET['cc_commercial'])?$_GET['cc_commercial']:0;
$offset = ($_GET['offset'])?$_GET['offset']:0;

$ts=time();
$hash=sha1(SLIDESHARE_SECRET.$ts);
$url = "http://www.slideshare.net/api/2/search_slideshows?api_key=".SLIDESHARE_KEY."&ts=$ts&hash=$hash&q=".$search."&items_per_page=".$limit."&page=".($offset+1);
$url .= "&cc=".$cc."&cc_adapt=".$cc_adapt."&cc_commercial=".$cc_commercial;

try {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$res = curl_exec($ch);
	curl_close($ch);
} catch (Exception $e) {
// Log the exception and return $res as blank
}
echo json_encode(new SimpleXMLElement($res));
?>
