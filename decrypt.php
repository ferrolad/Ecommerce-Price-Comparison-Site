<?php
function ascii2hex($ascii) {
	$hex = '';
	for ($i = 0; $i < strlen($ascii); $i++) {
		$byte = strtoupper(dechex(ord($ascii{$i})));
		$byte = str_repeat('0', 2 - strlen($byte)).$byte;
		$hex.=$byte;
	}
	return $hex;
}

function hex2ascii($hex){
	$ascii='';
	$hex=str_replace(" ", "", $hex);
	for($i=0; $i<strlen($hex); $i=$i+2) {
		$ascii.=chr(hexdec(substr($hex, $i, 2)));
	}
	return($ascii);
}

function xor2string($a, $b) {
	$xor='';
	for($i=0; $i<strlen($a); $i=$i+2) {
		$add = (string)(dechex(hexdec(substr($a, $i, 2)) ^ hexdec(substr($b, $i, 2))));
		if (strlen($add) == 1) {
			$add = "0".$add;
		}
		$xor.=$add;
	}
	return($xor);
}
$hex1 = ascii2hex("attack at dusk");
echo $hex1."<br/>";
$hex = ascii2hex("attack at dawn");
echo $hex."<br/>";
$encrypt = "09e1c5f70a65ac519458e7e53f36";
$key = xor2string($hex,$encrypt);
echo "key = ".$key."<br/>";
$answer = xor2string($key,$hex1);
echo "answer = ".$answer;
?>