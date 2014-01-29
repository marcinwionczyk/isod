<?php
/**
 * KindEditor PHP
 *
 */

require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'JSON.php');

$php_path = dirname(__FILE__) . '/../../';
$php_url = dirname($_SERVER['PHP_SELF']) . '/../../';

//Ścieżka katalogu głównego, można podać ścieżkę bezwzględną, np. /var/www/attached/
$root_path = $php_path . '../attached/';
//Katalog główny URL, można podać ścieżkę bezwzględną, np. http://www.yoursite.com/attached/
$root_url = $php_url . '../attached/';
//Rozszerzenie pliku
$ext_arr = array('gif', 'jpg', 'jpeg', 'png', 'bmp');

//nazwa katalogu
$dir_name = empty($_GET['dir']) ? '' : trim($_GET['dir']);
if (!in_array($dir_name, array('', 'image', 'flash', 'media', 'file'))) {
	echo "Invalid Directory name.";
	exit;
}
if ($dir_name !== '') {
	$root_path .= $dir_name . '/';
	$root_url .= $dir_name . "/";
	if (!file_exists($root_path)) {
		mkdir($root_path);
	}
}

//Ustawić ścieżkę i URL, według parametrów path, 
if (empty($_GET['path'])) {
	$current_path = realpath($root_path) . '/';
	$current_url = $root_url;
	$current_dir_path = '';
	$moveup_dir_path = '';
} else {
	$current_path = realpath($root_path) . '/' . $_GET['path'];
	$current_url = $root_url . $_GET['path'];
	$current_dir_path = $_GET['path'];
	$moveup_dir_path = preg_replace('/(.*?)[^\/]+\/$/', '$1', $current_dir_path);
}
echo realpath($root_path);
//Sort of form，name or size or type
$order = empty($_GET['order']) ? 'name' : strtolower($_GET['order']);

//Not allowed .. move to the previous directory
if (preg_match('/\.\./', $current_path)) {
	echo 'Access is not allowed.';
	exit;
}
//The last character is not /
if (!preg_match('/\/$/', $current_path)) {
	echo 'Parameter is not valid.';
	exit;
}
//Directory does not exist or is not a directory
if (!file_exists($current_path) || !is_dir($current_path)) {
	echo 'Directory does not exist.';
	exit;
}

//Directory traversal obtain file information
$file_list = array();
if ($handle = opendir($current_path)) {
	$i = 0;
	while (false !== ($filename = readdir($handle))) {
		if ($filename{0} == '.') continue;
		$file = $current_path . $filename;
		if (is_dir($file)) {
			$file_list[$i]['is_dir'] = true; //Whether folder
			$file_list[$i]['has_file'] = (count(scandir($file)) > 2); //Whether the folder that contains the file
			$file_list[$i]['filesize'] = 0; //File Size
			$file_list[$i]['is_photo'] = false; //Whether pictures
			$file_list[$i]['filetype'] = ''; //Class file extension judge
		} else {
			$file_list[$i]['is_dir'] = false;
			$file_list[$i]['has_file'] = false;
			$file_list[$i]['filesize'] = filesize($file);
			$file_list[$i]['dir_path'] = '';
			$file_ext = strtolower(array_pop(explode('.', trim($file))));
			$file_list[$i]['is_photo'] = in_array($file_ext, $ext_arr);
			$file_list[$i]['filetype'] = $file_ext;
		}
		$file_list[$i]['filename'] = $filename; //File name contains the extension
		$file_list[$i]['datetime'] = date('Y-m-d H:i:s', filemtime($file)); //The file was last modified time
		$i++;
	}
	closedir($handle);
}

//Sequence
function cmp_func($a, $b) {
	global $order;
	if ($a['is_dir'] && !$b['is_dir']) {
		return -1;
	} else if (!$a['is_dir'] && $b['is_dir']) {
		return 1;
	} else {
		if ($order == 'size') {
			if ($a['filesize'] > $b['filesize']) {
				return 1;
			} else if ($a['filesize'] < $b['filesize']) {
				return -1;
			} else {
				return 0;
			}
		} else if ($order == 'type') {
			return strcmp($a['filetype'], $b['filetype']);
		} else {
			return strcmp($a['filename'], $b['filename']);
		}
	}
}
usort($file_list, 'cmp_func');

$result = array();
//Relative to the root directory on a directory
$result['moveup_dir_path'] = $moveup_dir_path;
//Relative to the root directory of the current directory
$result['current_dir_path'] = $current_dir_path;
//The URL of the current directory
$result['current_url'] = $current_url;
//Number of files
$result['total_count'] = count($file_list);
//File list array
$result['file_list'] = $file_list;

//Output JSON string
header('Content-type: application/json; charset=UTF-8');
$json = new Services_JSON();
echo $json->encode($result);
?>