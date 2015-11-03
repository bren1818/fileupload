<?php
include "config.inc.php";
//include "uploadedFile.php";
include "alias.php";
adminPage();

$conn = getConnection();


if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST'){
	$aliasText = issetOrBlank($_POST['alias']);
	$pointer = issetOrBlank( $_POST['pointer'] );
	
	//echo "Alias: ".$alias.", pointer: ".$pointer;
	
	if( $aliasText != "" && $pointer != ""){
		//we can add them if the alias doesn't already exist.
		
		if( ! startsWith($aliasText, "/" ) ){
			$aliasText = "/".$aliasText;
		}
		
		$alias = new Alias($conn);	
		$alias->setAlias( $aliasText ); //must start with '/'
		$numAliases = sizeof( $alias->getObjectsLikeThis() );
			
		$alias->setType("url");
		$alias->setPointer( $pointer );
		if( $alias->getAlias() != "/" && $numAliases == 0 && $alias->save() > 0 ){
			echo '<h1>Alias created OK!</h1>';
			echo $alias->getAlias()." will now point to: ".$pointer;
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
	
	echo "<a href='/admin'>Back Home</a>";
	
	exit;
}

?>
<html>
<head>
	<title>Alias Creation</title>
	<style>
		#file-upload{
			margin: 0 auto;
			max-width: 800px;
		}
		
		input,select,file,textarea{
			width: 100%;
		}
		
		input[type="submit"]{
			margin: 10px 0;
		}
		
	</style>
</head>
<body>
	<div id="file-upload">
	<?php
	menubar();
	?>
	<fieldset>
		<legend>Alias Details</legend>
		<form method="post" action="addAlias.php">
			<table>
				<tr>
					<td>
						Alias:
					</td>
					<td>
						<input type="text" name="alias" value="" placeholder="/alias"/>
					</td>
				</tr>
				<tr>
					<td>
						Pointer:
					</td>
					<td>
						<input type="text" name="pointer" value="" placeholder="http://google.ca" />
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="submit" name="submit" value="Save" />
					</td>
				</tr>
			</table>		
		</form>
	</fieldset>
	</div>
</body>
</html>	