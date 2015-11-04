<?php
include "config.inc.php";
adminPage();
?>
<html>
<head>
	<title>File Upload</title>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/cupertino/jquery-ui.css" />
	<link rel="stylesheet" href="/css/jquery.tagit.css" />
	
	<link rel="stylesheet" href="//cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" />
	
	<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
	<script src="/js/tag-it.min.js"></script>
	
	<script src="//cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
	
	<script type="text/javascript">
		function copyToClipboard (text) {
			window.prompt ("Copy to clipboard: Ctrl+C, Enter", text);
		}
	</script>
	<style>
		#file-upload{
			margin: 0 auto;
			max-width: 1300px;
		}
	
		fieldset,
		.dataTable{ width: 100%; }
		
		fieldset{margin: 10px 0; }
		
		.path,
		div.directory{ width: 100%; }
		
		div.directory{
			background-image: url('/css/folder.png');
		}
		
		div.file{
			background-image: url('/css/file.png');
		}
		
		div.directory,
		div.file{
			min-height: 32px;
			line-height: 32px;
			background-repeat: no-repeat;
			background-position: 0 0;
			padding-left: 40px;
			clear: both;
			margin: 3px 0;
			
		}
		
		div.file a{
			line-height: 32px;
			vertical-align: middle;
			display: inline-block;
		}
		
		a.clipboard{
			cursor: pointer;
		}
		
		span.dirSeparator,
		span.directory{ display: inline-block; }
		
		#file_browser, .path{
			padding: 5px;
			border: 2px solid #ddd;
			display: block;
			width: 100%;
		}
		
		#file_browser{ min-height: 300px; }
		
	</style>
</head>
<body>
	
	<div id="file-upload">
		<?php
			menubar();
		?>
	
		<h1>File Browser</h1>
		<?php
			$conn  = getConnection();
		
			$d = (isset($_REQUEST['d']) ? urldecode($_REQUEST['d']) : '');
			$s = (isset($_REQUEST['s']) ? $_REQUEST['s'] : '');
			
			$path = UPLOADS_DIR;
			
			if( $d != "" && $s != ""){
				if( md5($d.KEY) == $s ){
					$extPath = preg_replace('#/+#','/', $d);
					$path = $path.DIRECTORY_SEPARATOR.$d;
				}else{
					echo 'Invalid Security Key';
				}
			}
			
			$path = realpath($path);
			
			$dh  = opendir($path);
			while (false !== ($filename = readdir($dh))) {
				if($filename != "." && $filename != ".."){
					$files[] = $filename;
				}
			}
			sort($files);
			
			echo '<div class="path">Path: ';
				if( $extPath != "/" ){
					$folders = explode(DIRECTORY_SEPARATOR, $extPath );
					$fullPath = "";
					foreach($folders as $folder){
						$fullPath.= DIRECTORY_SEPARATOR.$folder;
						$p = $fullPath;
						if ($folder !== end($array) && $folder !== reset($array)){
							echo '<span class="dirSeparator"> / </span>';
						}
						echo '<span class="directory"><a href="?d='.urlencode($p).'&s='.md5($p.KEY).'">'.$folder.'</a></span>';
					}
				}
			echo '</div>';
			
			echo '<div id="file_browser">';
			foreach($files as $file){
				$p = $extPath.DIRECTORY_SEPARATOR.$file;
				if( is_dir(UPLOADS_DIR.$p) ){
					echo '<div class="directory"><a href="?d='.urlencode($p).'&s='.md5($p.KEY).'">'.$file.'</a></div>';
				}else{
					$query =  $conn->prepare("SELECT `id` FROM `uploadedfile` where `path` = :PATH");
					$query->bindParam(':PATH', substr(trim($p),1) );
					$query->execute();
					$modifyFileLink = $query->fetchAll(PDO::FETCH_COLUMN);
					$fileID = "";
					if( sizeof($modifyFileLink ) == 1 ){
						$fileID = $modifyFileLink[0];
					}
					
					
				
					
					echo '<div class="file"><a target="_blank" href="'.WEB_ROOT.UPLOAD_FOLDER_NAME.$p.'">'.$file.'</a> <a class="clipboard" onclick="copyToClipboard(\''.WEB_ROOT.preg_replace('#/+#','/',UPLOAD_FOLDER_NAME.$p).'\')"><img src="/css/clipboard.png" alt="Copy File Path to Clipboard" title="Copy File Path to Clipboard"/></a>'.($fileID != "" ? ' | <a href="/admin/modifyFile.php?fileID='.$fileID.'&key='.hash('md5',KEY.substr(trim($p),1) ).'">Modify</a> | <a href="/admin/deleteFile.php?fileID='.$fileID.'&key='.hash('md5',KEY.substr(trim($p),1) ).'">Delete</a> ' : '').' </div>';
				}
			}
			echo '</div>';
			
		?>
		
		
		
		
	</div>
</body>
</html>	

