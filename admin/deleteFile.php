<?php
	include "config.inc.php";
	include "uploadedFile.php";
	include "alias.php";
	adminPage();
	
	if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST'){
		$fileID = $_POST['fileID'];
		$key = $_POST['key'];
		$conn = getConnection();
		$file = new Uploadedfile( $conn );
		$file = $file->load($fileID);
		
		if( $key == hash('md5',KEY.$file->getPath() ) ){
			 menubar();
			
			//echo 'Valid Key!';
			//delete file
			$filePath = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, UPLOADS_DIR.$file->getPath());
			
			if( unlink( $filePath ) ){
				echo '<p>Deleted File</p>';
				$aliasID = $file->getAliasID();
				
				if( $file->delete() ){
					echo '<p>Deleted Record</p>';
					if( $aliasID > 0 ){
						//delete the alias
						$alias = new Alias($conn);	
						$alias = $alias->load( $aliasID );
						if( $alias->getId() > 0 ){
							if( $alias->delete() > 0){
								echo '<p>Alias deleted successfully.</p>';
							}
						}
						
					}
					
				}else{
					echo '<p>Could not deleted file record</p>';
				}
				
			}else{
				echo '<h1>Could not delete file!</h1>';
			}
			
			
		}
	}else{
?>		
	<html>
		<head>
			<title>Are you sure you wish to delete this file?</title>
		</head>
		<body>
			<?php menubar();  ?>
			<?php
				//id
				$fileID = $_REQUEST['fileID'];
				$key = $_REQUEST['key'];
				
				$conn = getConnection();
				$file = new Uploadedfile( $conn );
				$file = $file->load($fileID);
				if( $key == hash('md5',KEY.$file->getPath() ) ){
					if( $file->getId() > 0 ){
			?>
						Are you sure you wish to delete: <?php echo $file->getPath();  ?>?
						<form method="post" action="deleteFile.php">
							<input name="fileID" type="hidden" value="<?php echo $file->getId(); ?>" />
							<input name="key" type="hidden" value="<?php echo $key; ?>" />
							<input type="submit" value="Yes" /> <a href="/admin">Cancel</a>
						</form>
			<?php
					}
				}else{
					echo '<h1>Invalid Security code</h1>';
				}
			?>
		</body>
	</html>	
<?php	
	}
?>