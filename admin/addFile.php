<?php
include "config.inc.php";
include "uploadedFile.php";
include "alias.php";
adminPage();


$conn = getConnection();
$file = new Uploadedfile($conn);

if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST'){
	
	//echo '<pre>'.print_r($_POST,true).'</pre>';
	?>
	<html>
		<head>
			<title>Upload File</title>
		</head>
		<body>
	<?php	
	
	
	
	
	menubar();
	
	//$file = new Uploadedfile($conn);
	
	$file->setCampus( issetOrBlank($_POST['file-campus']) );
	$file->setCategory( issetOrBlank($_POST['file-category']) );
	$file->setFaculty( issetOrBlank($_POST['file-faculty']) );
	$file->setExpiry( (trim($_POST['file-expiry']) == "" ? "0000-00-00 00:00:00" : (  date('Y-m-d H:i:s', strtotime( ($_POST['file-expiry']) ) ) ) ) ); 
	$file->setDescription( ($_POST['file-description']) );
	
	
	
	//sort the tags
	$tags = array_filter(array_map('trim', explode(',', issetOrBlank($_POST['file-tags']) )));
    asort($tags);
    $tags = implode(', ', $tags);
	
	$file->setTags(  $tags );
	
	//add tags to Global Tag list...
	//options - group_type = 4
	$query = "SELECT `option` FROM `options` WHERE `group_id` = 4 order by `sort`";
	$tags =	$conn->query($query);
	$tags->execute();
	$tags = $tags->fetchAll(PDO::FETCH_COLUMN);
	
	$newTags = explode("," , $file->getTags() );
	
	$addTags = array_diff ($newTags, $tags);
	
	$pos = sizeof($tags);
	foreach($addTags as $tag){
		$pos++;
		$newTag = $conn->prepare("INSERT INTO `fileupload`.`options` (`group_id`, `option`, `sort`) VALUES ('4', :tag, :pos)");
		$newTag->bindParam(':tag', trim($tag) );
		$newTag->bindParam(':pos', $pos);
		$newTag->execute();
	}
	
	
	
	
	$file->setType( issetOrBlank($_POST['file-type']) );
	
	$file->setUploadDate( date('Y-m-d H:i:s') );
	$file->setActive(1);
	$file->setAliasID(0);
	
	//echo UPLOADS_DIR;
	$DATEPATH = date('Y').DIRECTORY_SEPARATOR.date('m').DIRECTORY_SEPARATOR;
	
	
	$uploadTo =  $DATEPATH.$file->getCampus().DIRECTORY_SEPARATOR.$file->getFaculty().DIRECTORY_SEPARATOR.$file->getCategory().DIRECTORY_SEPARATOR;
	
	$uploadTo = issetOrBlank( $uploadTo );
	
	//$uploadTo = "";
	
	//echo $uploadTo;
	
	//Ensure that the directory path exists
	//echo UPLOADS_DIR;
	//exit;
	
	mkpath( UPLOADS_DIR.$uploadTo );
	
	//echo UPLOADS_DIR.$uploadTo;
	$uploaded = 0;
	
	if( ! file_exists( UPLOADS_DIR.$uploadTo) ){
		//could not create directory
		echo '<h1 class="error">Could not create directory for upload</h1>';
	}else{

	
		if(isset($_FILES["file-file"]) ) {
			//file uploaded
			$targetFile =  UPLOADS_DIR.$uploadTo. $_FILES["file-file"]['name'] ;
			
			if( ! file_exists($targetFile) ){
				if (move_uploaded_file($_FILES["file-file"]["tmp_name"], issetOrBlank($targetFile)) ) {
					$uploaded = 1;
				}	
			}else{
				echo '<h1 class="error">File already Exists!</h1>';
				
				$fileUploadPath = WEB_ROOT.DIRECTORY_SEPARATOR.UPLOAD_FOLDER_NAME.DIRECTORY_SEPARATOR.$uploadTo.issetOrBlank($_FILES["file-file"]['name']) ;
				//$fileUploadPath = str_replace('//','/',$fileUploadPath);
				
				
				echo '<p>Link: <a href="'.$fileUploadPath.'">'.$_FILES["file-file"]['name'].'</a></p>';
			}
			
			$file->setPath( $uploadTo. issetOrBlank($_FILES["file-file"]['name']) ) ;
		
		
			$file->setSize($_FILES["file-file"]['size']);
			$file->setContentType( issetOrBlank($_FILES["file-file"]['type']) );
			$file->setExtension( pathinfo($_FILES["file-file"]['name'], PATHINFO_EXTENSION) );
			
		
		}
	
	}
	if( $uploaded == 1){
		$file->setUploader( ($_SESSION['username'] != "" ? $_SESSION['username'] : 'birwin' ) ); //from session?
		
		if( $file->getExpiry() == ""){
			$file->setExpiry("0000-00-00 00:00:00");
		}
		
		if( $file->save() > 0 ){
			echo '<h1>File Uploaded OK!</h1>';
		}
	
		if( issetOrBlank($_POST['file-alias']) != "" ){
				$alias = new Alias($conn);
				$fileAlias = issetOrBlank($_POST['file-alias']);
				if( ! startsWith($fileAlias, "/" ) ){
					$fileAlias = "/".$fileAlias;
				}
				
				$alias->setAlias( $fileAlias ); //must start with '/'
				
				//check if alias already exists first
				//pa( $alias->getObjectsLikeThis() );
				//echo sizeof( $alias->getObjectsLikeThis() );
				$numAliases = sizeof( $alias->getObjectsLikeThis() );
				
				$alias->setPointer( $file->getId() );
				$alias->setType("file");
				if( $alias->getAlias() != "/" && $numAliases == 0 && $alias->save() > 0 ){
					echo '<h1>Alias created OK!</h1>';
					$file->setAliasID( $alias->getId() );
					if( $file->save() > 0 ){
						echo '<p>File Updated</p>';
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
	
		$fileUploadPath = path2url( str_replace('//','/',DIRECTORY_SEPARATOR.UPLOAD_FOLDER_NAME.DIRECTORY_SEPARATOR.$uploadTo.issetOrBlank($_FILES["file-file"]['name'])) );
		//$fileUploadPath = str_replace('//','/',$fileUploadPath);
		
	
		echo '<p>File Link: <a href="'.$fileUploadPath. '" target="_blank">'.issetOrBlank($_FILES["file-file"]['name']).'</a></p>';
		
		echo '<textarea style="width: 100%;"><a href="'.$fileUploadPath. '" target="_blank">'.issetOrBlank($_FILES["file-file"]['name']).'</a></textarea>';
		
	
	}
	
	
	
	
	exit;
	?>
	</body>
	</html>
	<?php
}


?>
<html>
<head>
	<title>File Upload</title>
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
		<legend>File Details</legend>
		
		<form method="post" action="addFile.php" enctype="multipart/form-data">
		
		<table>
		
		
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
					if( $file->getExpiry() != "0000-00-00 00:00:00" && $file->getExpiry() != "" ){
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
				<input type="file" name="file-file" required/>
			</td>
		</tr>
		
		<tr>
			<td>Alias</td>
			<td><input name="file-alias" type="text" value="<?php echo issetOrBlank($_POST['file-alias']); ?>" placeholder="/alias" /></td>
		</tr>
		
		<tr>
			<td colspan="2">
				<input type="submit" value="submit" />
			</td>
		<tr>	
		
		</table>
		
		
		</form>
		
		<file>
		
		
	</fieldset>
	
	</div>
</body>
</html>