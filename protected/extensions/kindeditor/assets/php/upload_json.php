<?php
/**
 * KindEditor PHP
 */
date_default_timezone_set('Europe/Warsaw');
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'JSON.php');

$php_path = dirname(__FILE__) . '/../../';
$php_url = dirname($_SERVER['PHP_SELF']) . '/../../';

//Zapisz ścieżkę do pliku
$save_path = $php_path . '../attached/';
//Zapisz URL
$save_url = $php_url . '../attached/';
//Dopuszczalne rozszerzenia plików
$ext_arr = array(
	//'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
	//'flash' => array('swf', 'flv'),
	'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
	'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
	'image' => array('jpg', 'png'),
	'file' => array('zip', 'rar'),
);
//Maksymalny rozmiar pliku
$max_size = 1024*800;
$save_path = realpath($save_path) . '/';
//błędy PHP

if (!empty($_FILES['imgFile']['error'])) {
	switch($_FILES['imgFile']['error']){
		case '1':
			$error = 'Obrazek przekracza dopuszczalny rozmiar określony w php.ini';
			break;
		case '2':
			$error = 'Więcej niż dopuszczalny rozmiar';
			break;
		case '3':
			$error = 'Obrazek wgrany tylko częściowo';
			break;
		case '4':
			$error = 'Proszę wybrać obrazek';
			break;
		case '6':
			$error = 'niemożna znaleźć katalogu tymczasowego';
			break;
		case '7':
			$error = 'Błędny zapis plików na dysku twardym';
			break;
		case '8':
			$error = 'Wgrywanie plików zostało wstrzymane';
			break;
		case '999':
		default:
			$error = 'Nieznany błąd';
	}
	alert($error);
}

//Prześlij pliki
if (empty($_FILES) === false) {
	//Orginalna nazwa pliku
	$file_name = $_FILES['imgFile']['name'];
	//nazwa pliku tymczasowego
	$tmp_name = $_FILES['imgFile']['tmp_name'];
	//Rozmiar pliku
	$file_size = $_FILES['imgFile']['size'];
	//Sprawdź nazwę pliku
	if (!$file_name) {
		alert("Proszę wybrać plik.");
	}
	//Sprawdź w katalogu
	if (@is_dir($save_path) === false) {
		alert("Ścieżka zapisu nie istnieje.");
	}
	//Sprawdź katalog zapisu
	if (@is_writable($save_path) === false) {
		alert("Brak uprawnień do zapisu.");
	}
	//Sprawdź czy przesłane
	if (@is_uploaded_file($tmp_name) === false) {
		alert("Przesyłanie nie powiodło się.");
	}
	//Sprawdź rozmiar pliku
	if ($file_size > $max_size) {
		alert("Rozmiar przesłanego pliku przekracza dozwolony limit");
	}
	//Sprawdź nazwę katalogu
	$dir_name = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);
	if (empty($ext_arr[$dir_name])) {
		alert("Nazwa katalogu jest nieprawidłowa");
	}
	//Rozszerzenie pliku
	$temp_arr = explode(".", $file_name);
	$file_ext = array_pop($temp_arr);
	$file_ext = trim($file_ext);
	$file_ext = strtolower($file_ext);
	//Sprawdź rozszerzenie
	if (in_array($file_ext, $ext_arr[$dir_name]) === false) {
		alert("Niedozwolone rozszerzenie pliku " . implode(",", $ext_arr[$dir_name]));
	}
	//Utwórz folder
	if ($dir_name !== '') {
		$save_path .= $dir_name . "/";
		$save_url .= $dir_name . "/";
		if (!file_exists($save_path)) {
			mkdir($save_path);
		}
	}
	$ymd = date("Ymd");
	$save_path .= $ymd . "/";
	$save_url .= $ymd . "/";
	if (!file_exists($save_path)) {
		mkdir($save_path);
	}
	//Nowa nazwa pliku
	$new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;
	//Przenoszenie plików
	$file_path = $save_path . $new_file_name;
	if (move_uploaded_file($tmp_name, $file_path) === false) {
		alert("Nie udało się załadować pliku.");
	}
	@chmod($file_path, 0644);
	$file_url = $save_url . $new_file_name;

	header('Content-type: text/html; charset=UTF-8');
	$json = new Services_JSON();
	echo $json->encode(array('error' => 0, 'url' => $file_url));
	exit;
}

function alert($msg) {
	header('Content-type: text/html; charset=UTF-8');
	$json = new Services_JSON();
	echo $json->encode(array('error' => 1, 'message' => $msg));
	exit;
}
?>
