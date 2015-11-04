<?php
//some instances may bark if date isn't set
date_default_timezone_set("America/New_York");

define('ROOT_DIR',  $_SERVER["DOCUMENT_ROOT"]  );
define('UPLOAD_FOLDER_NAME','UPLOADS/');
define('UPLOADS_DIR', ROOT_DIR.DIRECTORY_SEPARATOR.UPLOAD_FOLDER_NAME.DIRECTORY_SEPARATOR);
define('WEB_ROOT', 'http://localhost/');
define('KEY', 'SECURITY_CHECK'.date('Y-m-d') );
define('SESSION_LENGTH_MIN', 5 );



function getConnection() {
	$mode = 2;
	if( $mode == 1 ){
		$dbName = "fileupload"; //Database Name
		$dbUser = ""; 		//Database User
		$dbPass = ""; 			//Database Password
		$dbHost = "";
		$port = '3306';
	}else if( $mode == 2 ){	
		$dbName = "fileupload"; 			//Database Name
		$dbUser = ""; 			//Database User
		$dbPass = ""; 				//Database Password
		$dbHost = "";
		$port = '3307';
	}else if( $mode == 3 ){
		//prod connection
	}
	
	$dbc = null;
	try {
		$dbc = new PDO('mysql:host='.$dbHost.';port='.$port.';dbname='.$dbName, $dbUser, $dbPass);
		$dbc->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}
	catch(PDOException $e) {
		echo "<h2>An error has occurred connecting to the database</h2>";
		echo "<p>".$e->getMessage()."</p>";
		file_put_contents('PDOErrorsLog.txt', $e->getMessage(), FILE_APPEND);
	}
	return $dbc;
}

function issetOrBlank($input){
	//replace ' ' with -
	return trim(  str_replace( ' ', '-', (isset($input) ? $input : "") ) ) ;
}

function adminPage(){
	session_start();
	if( !isset($_SESSION['expiry']) || (strtotime( date("Y-m-d H:i:s") ) > $_SESSION['expiry'] ) ){
		$_SESSION = array();
		session_destroy();
		header("location: /admin/login.php");
	}else{
		$_SESSION['expiry'] = strtotime( date("Y-m-d H:i:s") ) + 60 * SESSION_LENGTH_MIN;	
	}
}

function menubar(){
	?>
	<div id="menuBar" style="margin: 10px 0px">
		<?php
			if(basename($_SERVER["SCRIPT_FILENAME"], '.php') != "index" ){
		?>		
			<a href="index.php">Home</a>
		<?php
			}
		?>
		
		<a href="addFile.php">Upload a File</a>
		<a href="addAlias.php">Create Alias</a>
		<a href="stats.php">File Stats</a>
		<a href="filebrowser.php">File Browser</a>
		<a href="logout.php">Log Out</a>
	</div>	
	<?php
}

function mkpath($path)
  {
    if(@mkdir($path) or file_exists($path)) return true;
    return (mkpath(dirname($path)) and mkdir($path, 0755, true));
  }

  function path2url($file, $Protocol='http://') {
    return $Protocol.$_SERVER['HTTP_HOST'].str_replace($_SERVER['DOCUMENT_ROOT'], '', $file);
}

function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

function endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
}

function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 

    // Uncomment one of the following alternatives
     $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow)); 

    return round($bytes, $precision) . ' ' . $units[$pow]; 
} 

function pa($array){
	echo '<pre>'.print_r($array,true).'</pre>';
}

function render404($pointer){
	ob_clean();
	?>
	<html>
		<head>
			<title>
				File/Alias: '<?php echo $pointer; ?>' was not found.
			</title>
		</head>
		<body>
			<div class="sorry" style="max-width: 400px; margin: 0 auto; padding: 50px 0;">
				<h1>We're sorry but...</h1>
				<h2>'<?php echo $pointer; ?>' was not found.</h2>
				<p><center><img src="http://i0.kym-cdn.com/photos/images/masonry/000/120/937/tumblr-tumbeasts.jpg" /></center></p>
			</div>
		</body>
	</html>
	<?php
}
function renderExpired($pointer){
	ob_clean();
	?>
	<html>
		<head>
			<title>
				File/Alias: '<?php echo $pointer; ?>' was has expired.
			</title>
		</head>
		<body>
			<div class="sorry" style="max-width: 400px; margin: 0 auto; padding: 50px 0;">
				<h1>We're sorry but...</h1>
				<h2>'<?php echo $pointer; ?>' has expired.</h2>
				<p><center><img src="http://i0.kym-cdn.com/photos/images/masonry/000/120/937/tumblr-tumbeasts.jpg" /></center></p>
			</div>
		</body>
	</html>
	<?php
}
?>



