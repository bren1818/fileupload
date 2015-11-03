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
			 
			 
			 $fileUpdate = new Uploadedfile();
			 
			$fileUpdate->setCampus( issetOrBlank($_POST['file-campus']) );
			$fileUpdate->setCategory( issetOrBlank($_POST['file-category']) );
			$fileUpdate->setFaculty( issetOrBlank($_POST['file-faculty']) );
			$fileUpdate->setExpiry( (trim($_POST['file-expiry']) == "" ? "0000-00-00 00:00:00" : (  date('Y-m-d H:i:s', strtotime( ($_POST['file-expiry']) ) ) ) ) ); ///0000-00-00 00:00:00	
			$fileUpdate->setDescription( ($_POST['file-description']) );
			
			if( issetOrBlank($_POST['file-tags']) != $file->getTags() ){
			
				//sort the tags
				$tags = array_filter(array_map('trim', explode(',', issetOrBlank($_POST['file-tags']) )));
				asort($tags);
				$tags = implode(', ', $tags);
				$fileUpdate->setTags(  $tags ); 
				
				$query = "SELECT `option` FROM `options` WHERE `group_id` = 4 order by `sort`";
				$Otags =	$conn->query($query);
				$Otags->execute();
				$Otags = $Otags->fetchAll(PDO::FETCH_COLUMN);
				
				//echo $tags;
				$newTags = explode("," , $fileUpdate->getTags() );
				$addTags = array_diff ($newTags, $Otags);
				
				$pos = sizeof($Otags);
				foreach($addTags as $tag){
					$pos++;
					$newTag = $conn->prepare("INSERT INTO `fileupload`.`options` (`group_id`, `option`, `sort`) VALUES ('4', :tag, :pos)");
					$newTag->bindParam(':tag', $tag);
					$newTag->bindParam(':pos', $pos);
					$newTag->execute();
				
				}
			
			}else{
				$fileUpdate->setTags(  $file->getTags() );
			}
			
			
			$fileUpdate->setType( issetOrBlank($_POST['file-type']) );
			$fileUpdate->setUploadDate( $file->getUploadDate() );
			
			//see if active has changed
			
			//alias - see if it 
			$fileUpdate->setActive(  issetOrBlank($_POST['file-active']) == "" ? 0 : 1 );
			 
			if( !isset($_FILES["file-file"]) ) {
				//no new file
				$fileUpdate->setUploader( $file->getUploader() );
				$fileUpdate->setSize( $file->getSize() );
				$fileUpdate->setContentType( $file->getContentType() );
				$fileUpdate->setExtension( $file->getExtension() );
				$fileUpdate->setPath( $file->getPath() );
			}else{
				//$file->setUploader( ($_SESSION['username'] != "" ? $_SESSION['username'] : 'birwin' ); //from session?
				
				//$file->setSize($_FILES["file-file"]['size']);
				//$file->setContentType( issetOrBlank($_FILES["file-file"]['type']) );
				//$file->setExtension( pathinfo($_FILES["file-file"]['name'], PATHINFO_EXTENSION) );
				
				//delete existing file
				
			}
			
			$fileAlias = issetOrBlank($_POST['file-alias']);
			if( ! startsWith($fileAlias, "/" ) ){
				$fileAlias = "/".$fileAlias;
			}
			
		//	echo "Alias: ".$file->getAliasID();
		//	echo "Alias Text: ".$fileAlias;
			
			//check if alias is new or changed.
			$aliasFind = "SELECT `id` FROM `alias` WHERE `alias` = :ALIASTEXT";
			$aliasFind = $conn->prepare($aliasFind);
			$aliasFind->bindParam(':ALIASTEXT', $fileAlias);
			$aliasFind->execute();
			$aliasFind = $aliasFind->fetchAll(PDO::FETCH_ASSOC);
			
			//echo "Alias ID: ".$aliasFind[0]['id'];
			
			if( $aliasFind[0]['id'] == $file->getAliasID() ){
				//echo "No Alias Update";
				$fileUpdate->setAliasID( $file->getAliasID() );
			}else{
				//need to update the alias...
				//echo 'Alias ID: '.$file->getAliasID().' needs to point to: '.$fileAlias;
				
				if( $fileAlias == "/"){
					//remove the alias
					echo "Delete alias".$file->getAliasID();
					$fileUpdate->setAliasID(0);
					$aliasUpdate = "DELETE FROM `alias` where `id` = :ALIASID";
					$aliasUpdate = $conn->prepare($aliasUpdate);
					$aliasUpdate->bindParam(':ALIASID', $file->getAliasID());
					if( $aliasUpdate->execute() ){
						echo "Alias deleted Successfully.";
						
					}
					
				}else{
					
					//new or Update
					
					//ensure it is available!
					$alias = new Alias($conn);
					$alias->setAlias($fileAlias);
					$numAliases = sizeof( $alias->getObjectsLikeThis() );
					
					if( $numAliases == 0){
					
						if( $file->getAliasID() == 0){
		
							$alias->setPointer( $file->getId() );
							$alias->setType('file');
							if( $alias->save() > 0 ){
								//echo "Alias Saved";
								$fileUpdate->setAliasID( $alias->getId() );
							}
							
							
						}else{
							$aliasUpdate = "UPDATE `alias` set `alias` = :NEWALIAS where `id` = :ALIASID";
							$aliasUpdate = $conn->prepare($aliasUpdate);
							$aliasUpdate->bindParam(':NEWALIAS', $fileAlias);
							$aliasUpdate->bindParam(':ALIASID', $file->getAliasID());
							$fileUpdate->setAliasID( $file->getAliasID() );
							if( $aliasUpdate->execute() > 0){
								//echo "Alias updated Successfully.";
							}
						}
					}else{
						echo "Alias '".$fileAlias."' already exists!";
						$fileUpdate->setAliasID( $file->getAliasID() );
					}
				}
			}
			
			//pa( $fileUpdate );
			 $fileUpdate->setConnection($conn);
			 $fileUpdate->setId($fileID);
			 if( $fileUpdate->save() > 0 ){
				//echo "Saved." ;
				$changes = $fileUpdate->compareTo( $file ); 
				echo "Modifications: <ul>";
				foreach($changes as $k=>$v){
					if($v != "un-modified"){
						echo '<li>'.$k.'</li>';
					}
				}
				echo '</ul>';
				echo '<a href="/admin">Home</a>';
			 }
			
			 
		}else{
			echo "Invalid Security Code!";
			echo '<a href="/admin">Home</a>';
		}
	}else{
		
?>		
	<html>
		<head>
			<title>Modify file?</title>
			<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/cupertino/jquery-ui.css" />
	<link rel="stylesheet" href="/css/jquery.tagit.css" />
	
	<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
	<script src="/js/tag-it.min.js"></script>
	
	<script type="text/javascript">
		$(function(){
			$( "input[type='date']" ).datepicker({ minDate: -0, maxDate: "+5Y", dateFormat: "yy-mm-dd" });
			
			$('.tags-select').tagit({
				availableTags: [<?php
					$conn = getConnection();
					//options - group_type = 4
					$query = "SELECT * FROM `options` WHERE `group_id` = 4 order by `sort`";
					 foreach ($conn->query($query) as $row) {
						echo "'".$row['option']."', ";
					 }
					 echo "'_LAST_'";
				 
				?>],
				caseSensitive: false,
				tagLimit: 10
				
			});
					
		});
	</script>
	<style>
		#file-upload{
			margin: 0 auto;
			max-width: 800px;
		}
		
		input,select,file,textarea{
			width: 100%;
		}
		
		input[type="checkbox"]{
			width: auto;
		}
		
		input[type="submit"]{
			margin: 10px 0;
		}
		
		td{ vertical-align: top; }
		
	</style>
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
			
			
			<fieldset>
		<legend>File Details</legend>
		
		<form method="post" action="modifyFile.php">
		
		<table>
		
		<tr>
			<td>Upload Date:</td><td><?php echo $file->getUploadDate(); ?></td>
		</tr>
		
		<tr>
			<td>File Name:</td><td><?php echo substr( $file->getPath(), (strrpos($file->getPath(), DIRECTORY_SEPARATOR) + 1) ); ?></td>
		</tr>
		
		<tr>
			<td>File Path:</td><td><?php echo $file->getPath(); ?></td>
		</tr>
		
		<tr>
			<td>File Size:</td><td><?php echo formatBytes( $file->getSize() ); ?></td>
		</tr>
		
		
		<tr>
		<td>Campus:</td><td><select name="file-campus">
			<?php
				$query = "SELECT * FROM `options` WHERE `group_id` = 2 order by `sort`";
				//$conn = getConnection();
				 foreach ($conn->query($query) as $row) {
					if( $file->getCampus() == $row['option'] ){
						echo '<option value="'.$row['option'].'" selected>'.$row['option'].'</option>';
					}else{ 
						echo '<option value="'.$row['option'].'">'.$row['option'].'</option>';
					}
					
				 }
			?>
		</select></td>
		</tr>
		
		<tr>
		<td>Category:</td> <td><select name="file-category">
			<?php
				$query = "SELECT * FROM `options`  WHERE `group_id` = 1 order by `sort`";
				
				 foreach ($conn->query($query) as $row) {
					if( $file->getCategory() == $row['option'] ){
						echo '<option value="'.$row['option'].'" selected>'.$row['option'].'</option>';
					 }else{
						echo '<option value="'.$row['option'].'">'.$row['option'].'</option>';
					 }	
				 }
			?>
		</select></td>
		</tr>
		
				<tr>
		<td>Faculty:</td> <td><select name="file-faculty">
			<?php
				$query = "SELECT * FROM `options`  WHERE `group_id` = 3 order by `sort`";
				
				 foreach ($conn->query($query) as $row) {
					if( $file->getFaculty() == $row['option'] ){
						echo '<option value="'.$row['option'].'" selected>'.$row['option'].'</option>';
					}else{
						echo '<option value="'.$row['option'].'">'.$row['option'].'</option>';
					}
				}
			?>
		</select></td>
		</tr>
		
		<tr>
			<td>
				Description:
			</td>	
			<td>
				<textarea name="file-description" style="width: 100%; min-height: 100px;"><?php echo $file->getDescription(); ?></textarea>
			</td>
		</tr>
		
		<tr>
			<td>
				Expiry *leave blank for no expiry*
			</td>
			<td>
				<input type="date" name="file-expiry" value="<?php
					//if has expiry, convert date
					if( $file->getExpiry() != "0000-00-00 00:00:00" || $file->getExpiry() != "" ){
						echo date('Y-m-d', strtotime( $file->getExpiry() ) );
					}
				?>" />
			</td>
		</tr>
		
		<tr>
			<td>
				Tags
			</td>
			<td>
				<input class="tags-select" data-placeholder="Add some tags" name="file-tags" value="<?php echo $file->getTags(); ?>"  />
			</td>
		</tr>
		
		<tr>
			<td>File Type</td>
			<td>
			<?php
				$fileTypes = array("-","Word","Excel","PDF","Image","Audio","Video");
			?>
				<select name="file-type">
					<?php
						foreach($fileTypes as $type){
							if( $type == $file->getType() ){
								echo '<option value="'.$type.'" selected>'.$type.'</option>';
							}else{
								echo '<option value="'.$type.'">'.$type.'</option>';
							}
						}
					?>
				</select>
			</td>
		</tr>
		
		<tr>
			<td>File</td>
			<td>
				<p>If you wish to overide the file, select a new one here, otherwise leave it blank.</p>
				<input type="file" name="file-file" disabled/><br />
				<?php
				$fileUploadPath = path2url(DIRECTORY_SEPARATOR.UPLOAD_FOLDER_NAME.DIRECTORY_SEPARATOR.$file->getPath() );
				echo '<p>Current file: <a href="'.$fileUploadPath. '" target="_blank">'.substr( $file->getPath(), (strrpos($file->getPath(), DIRECTORY_SEPARATOR) + 1) ).'</a>';
				?>
			</td>
		</tr>
		
		<tr>
			<td>Move File on new uploaded?</td>
			<td>Check this box, if you want a newly updated file to sit in a location as designated by the {Campus}/{Category}/{Faculty}.<br />Leaving this unchecked with a new file, will just replace the existing file. *Note, ensure the replacement file is the same type as the previous.<br />
			<input type="checkbox" name="moveFile" value="1" disabled/>
			</td>
		<tr>	
		
		<tr>
			<td>File Active: </td>
			<td>
				<input type="checkbox" value="1" name="file-active" <?php if( $file->getActive() == 1){ echo " checked"; } ?>/>
			</td>
		</tr>
		
		<tr>
			<td>Alias</td>
			<td>
				<input name="file-aliasID" type="hidden" value="<?php echo $file->getAliasID(); ?>" />
				
				<?php  if( $file->getAliasID() > 0 ){
					//load the alias text...
					$alias = new Alias($conn);
					$alias = $alias->load( $file->getAliasID() );
					if( is_object($alias) && $alias->getId() > 0){
						?>
						<input name="file-alias" type="text" value="<?php echo $alias->getAlias(); ?>" placeholder="/alias" />
						
						<?php
					}else{
						?>
						<input name="file-alias" type="text" value="" placeholder="/alias" />
						<?php
					}
					
				}else{
				?>
					<input name="file-alias" type="text" value="" placeholder="/alias" />
				<?php 
				} 
				?>
			</td>
		</tr>
		
		<tr>
			<td colspan="2">
			<input name="fileID" type="hidden" value="<?php echo $file->getId(); ?>" />
							<input name="key" type="hidden" value="<?php echo $key; ?>" />
				<input type="submit" value="Update" />
				<a href="/admin">Cancel</a>
			</td>
		<tr>	
		
		</table>
		
		
		</form>
		
		<file>
		
		
	</fieldset>
	
	
			
			
			
					
						
						
						
						
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