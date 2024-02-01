<?php
function chkarray($source, $default = 0){
	if($source == ""){
		$target = $default;
	}else{
		$target = $source;
	}
	return $target;
}

$levelID = !empty($_POST["levelid"]) ? $_POST["levelid"] : exit("levelid required\n(you can add 'gdps' to post parameter for a gdps dl)");
$levelID = preg_replace("/[^0-9]/", '', $levelID);
$url = !empty($_POST["gdps"]) ? $_POST["gdps"] : "https://www.boomlings.com/database";
$url = "$url/downloadGJLevel22.php";
$post = ['gameVersion' => '21', 'binaryVersion' => '33', 'gdw' => '0', 'levelID' => $levelID, 'secret' => 'Wmfd2893gb7', 'inc' => '1', 'extras' => '0'];
$ch = curl_init($url);
// might need to add a proxy for robtop's server?
// curl_setopt($ch, CURLOPT_PROXY, "host.com:80");
// curl_setopt($ch, CURLOPT_PROXYUSERPWD, "username:password");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);
curl_setopt($ch, CURLOPT_USERAGENT, " ");
$result = curl_exec($ch);
curl_close($ch);

switch($result) {
	case "":
		exit("Connection failure to $url");
	case "-1":
		exit("Level doesn't exist");
	case "No no no":
		exit("Robtop?");
}

$level = explode('#', $result)[0];
$resultarray = explode(':', $level);
$levelarray = array();
$x = 1;
foreach($resultarray as &$value){
	if ($x % 2 == 0) {
		$levelarray["a$arname"] = $value;
	}else{
		$arname = $value;
	}
	$x++;
}
if($levelarray["a4"] == ""){
	exit("An error has occured.<br>Error code: ".htmlspecialchars($result,ENT_QUOTES));
}
$uploadDate = time();
//old levelString
$levelString = chkarray($levelarray["a4"]);
$gameVersion = chkarray($levelarray["a13"]);
if(substr($levelString,0,2) == 'eJ'){
	$levelString = str_replace("_","/",$levelString);
	$levelString = str_replace("-","+",$levelString);
	$levelString = gzuncompress(base64_decode($levelString));
	if($gameVersion > 18){
		$gameVersion = 18;
	}
}

exit("$levelString");
?>
