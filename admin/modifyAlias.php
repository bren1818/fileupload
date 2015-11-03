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
			
			
			$aliasText = issetOrBlank($_POST['alias']);
			$pointer = issetOrBlank( $_POST['pointer'] );
	

			if( $aliasText != "" && $pointer != ""){
				//we can add them if the alias doesn't already exist.
				
				if( ! startsWith($aliasText, "/" ) ){
					$aliasText = "/".$aliasText;
				}
				
				$alias = new Alias($conn);	
				$alias->setAlias( $aliasText ); //must start with '/'
				$numAliases = sizeof( $alias->getObjectsLikeThis() );
					
				if( $numAliases != 0){
					//is this because we only updated the pointer, or because it actually already exists?
					//simple check, if the hash with the new aliasText is the same as the old, it's just an update
					if( $key == hash( 'md5',KEY.$alias->getAlias() )  ){
						$numAliases = 0;
					}
				}	
					
					
			
				
				$alias->setPointer( $pointer );
				$alias->setId( $aliasID );
				
				if( $alias->getAlias() != "/" && $numAliases == 0 && $alias->save() > 0 ){
					echo '<h1>Alias Saved OK!</h1>';
					if( $alias->getType() != "url" ){
						echo $alias->getAlias()." will point to file ID: ".$pointer;
					}else{
						echo $alias->getAlias()." will point to: ".$pointer;
					}
					
				}else{
					if( $numAliases > 0){
						echo '<p>The Alias: '.$alias->getAlias().' already exists</p>';
					}else if($alias->getAlias() == "/"){
						echo '<p>Alias cannot be saved as /</p>'; //shouldnt occur
					}else{
						echo '<p>Couldnt save...</p>';
					}
				}
			}
			
			
		
			
		}
	}else{
?>		
	<html>
		<head>
			<title>Modify this alias?</title>
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
					
						<form method="post" action="modifyAlias.php">
							<fieldset>
							<legend>Alias Details</legend>
							<h3>Modify: <?php echo $alias->getAlias();  if($alias->getType() == "url") { ?> (points to: <?php echo $alias->getPointer(); ?>)? <?php } ?></h3>
							<form method="post" action="addAlias.php">
								<table>
									<tr>
										<td>
											Alias:
										</td>
										<td>
											<input type="text" name="alias" value="<?php echo $alias->getAlias(); ?>" placeholder="/alias"/>
										</td>
									</tr>
									<tr>
										<td>
											Pointer:
										</td>
										<td>
											<?php if( $alias->getType() == "url" ){ ?>
											<input type="text" name="pointer" value="<?php echo $alias->getPointer(); ?>" placeholder="http://google.ca" />
											<?php }else{ ?>
											You Cannot modify a file pointer, but you can update the alias of the file.
											<input type="hidden" name="pointer" value="<?php echo $alias->getPointer(); ?>" />
											<?php } ?>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<input name="aliasID" type="hidden" value="<?php echo $alias->getId(); ?>" />
											<input name="key" type="hidden" value="<?php echo $key; ?>" />
											<input type="submit" name="submit" value="Update" /> <a href="/admin">Cancel</a>
										</td>
									</tr>
								</table>		
							</form>
						</fieldset>
						
						
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