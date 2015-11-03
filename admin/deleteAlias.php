<?php
	include "config.inc.php";
	//include "uploadedFile.php";
	include "alias.php";
	adminPage();
	
	if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST'){
		$aliasID = $_POST['aliasID'];
		$key = $_POST['key'];
		$conn = getConnection();
		$alias = new Alias( $conn );
		$alias = $alias->load($aliasID);
		
		if( $key == hash('md5',KEY.$alias->getAlias() ) ){
			 menubar();
			
			//echo 'Valid Key!';
			//delete file
			//$filePath = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, UPLOADS_DIR.$file->getPath());
			
			//if( unlink( $filePath ) ){
			//	echo '<p>Deleted File</p>';
			//	$aliasID = $file->getAliasID();
				
				if( $alias->delete() ){
					echo '<p>Deleted Alias Successfully</p>';
					//if( $aliasID > 0 ){
					//	//delete the alias
					//	$alias = new Alias($conn);	
					//	$alias = $alias->load( $aliasID );
					//	if( $alias->getId() > 0 ){
					//		if( $alias->delete() > 0){
					//			echo '<p>Alias deleted successfully.</p>';
					//		}
					//	}
						
					//}
					
				}else{
					echo '<p>Could not deleted alias record</p>';
				}
				
			//}else{
			//	echo '<h1>Could not delete file!</h1>';
			//}
			
			
		}
	}else{
?>		
	<html>
		<head>
			<title>Are you sure you wish to delete this alias?</title>
		</head>
		<body>
			<?php menubar();  ?>
			<?php
				//id
				$aliasID = $_REQUEST['aliasID'];
				$key = $_REQUEST['key'];
				
				$conn = getConnection();
				$alias = new Alias( $conn );
				$alias = $alias->load($aliasID);
				if( $key == hash('md5',KEY.$alias->getAlias() ) ){
					if( $alias->getId() > 0 ){
			?>
						Are you sure you wish to delete: <?php echo $alias->getAlias();  if($alias->getType() == "url") { ?> (points to: <?php echo $alias->getPointer(); ?>)? <?php } ?>
						<form method="post" action="deleteAlias.php">
							<input name="aliasID" type="hidden" value="<?php echo $alias->getId(); ?>" />
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