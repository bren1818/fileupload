<?php
/*  Class Generated by Brendon Irwin's Class Generator

	Class: Uploadedfile

	aliasID, i
	path, v
	size, i
	contentType, v
	extension, v
	uploader, v
	uploadDate, dt
	active, i
	campus, v
	category, v
	faculty, v
	description, mt
	expiry, dt
	tags, v
	type, v

	
	CREATE TABLE IF NOT EXISTS `uploadedfile` (
	`id` INT NULL DEFAULT NULL AUTO_INCREMENT PRIMARY KEY,
	`aliasID` INTEGER,
	`path` VARCHAR( 55 ),
	`size` INTEGER,
	`contentType` VARCHAR( 55 ),
	`extension` VARCHAR( 55 ),
	`uploader` VARCHAR( 55 ),
	`uploadDate` DATETIME,
	`active` INTEGER,
	`campus` VARCHAR( 55 ),
	`category` VARCHAR( 55 ),
	`faculty` VARCHAR( 55 ),
	`description` MEDIUMTEXT,
	`expiry` DATETIME,
	`tags` VARCHAR( 55 ),
	`type` VARCHAR( 55 )
	);
	
	
	
*/

	class Uploadedfile{
		private $id;
		private $connection;
		private $errors;
		private $errorCount;
		private $aliasID;
		private $path;
		private $size;
		private $contentType;
		private $extension;
		private $uploader;
		private $uploadDate;
		private $active;
		private $campus;
		private $category;
		private $faculty;
		private $description;
		private $expiry;
		private $tags;
		private $type;


		/*Constructor*/
		function __construct($databaseConnection=null){
			$this->connection = $databaseConnection;
		}

		/*Getters and Setters*/
		function getId(){
			return $this->id;
		}

		function setId($id){
			$this->id = $id;
		}

		function getConnection(){
			return $this->connection;
		}

		function setConnection($connection){
			$this->connection = $connection;
		}

		function getErrors(){
			return $this->errors;
		}

		function setErrors($errors){
			$this->errors = $errors;
		}

		function getErrorCount(){
			return $this->errorCount;
		}

		function setErrorCount($errorCount){
			$this->errorCount = $errorCount;
		}

		function getAliasID(){
			return $this->aliasID;
		}

		function setAliasID($aliasID){
			$this->aliasID = $aliasID;
		}

		function getPath(){
			return $this->path;
		}

		function setPath($path){
			$this->path = $path;
		}

		function getSize(){
			return $this->size;
		}

		function setSize($size){
			$this->size = $size;
		}

		function getContentType(){
			return $this->contentType;
		}

		function setContentType($contentType){
			$this->contentType = $contentType;
		}

		function getExtension(){
			return $this->extension;
		}

		function setExtension($extension){
			$this->extension = $extension;
		}

		function getUploader(){
			return $this->uploader;
		}

		function setUploader($uploader){
			$this->uploader = $uploader;
		}

		function getUploadDate(){
			return $this->uploadDate;
		}

		function setUploadDate($uploadDate){
			$this->uploadDate = $uploadDate;
		}

		function getActive(){
			return $this->active;
		}

		function setActive($active){
			$this->active = $active;
		}

		function getCampus(){
			return $this->campus;
		}

		function setCampus($campus){
			$this->campus = $campus;
		}

		function getCategory(){
			return $this->category;
		}

		function setCategory($category){
			$this->category = $category;
		}

		function getFaculty(){
			return $this->faculty;
		}

		function setFaculty($faculty){
			$this->faculty = $faculty;
		}

		function getDescription(){
			return $this->description;
		}

		function setDescription($description){
			$this->description = $description;
		}

		function getExpiry(){
			return $this->expiry;
		}

		function setExpiry($expiry){
			$this->expiry = $expiry;
		}

		function getTags(){
			return $this->tags;
		}

		function setTags($tags){
			$this->tags = $tags;
		}

		function getType(){
			return $this->type;
		}

		function setType($type){
			$this->type = $type;
		}

		/*Special Functions*/
		function load($id = null){
			if( $this->connection ){
				if( $id == null && $this->getId() != ""){
					$id = $this->getId();
				}

				/*Perform Query*/
				if( $id != "" ){
					$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `id` = :id");
					$query->bindParam(':id', $id);
					if( $query->execute() ){
						$uploadedfile = $query->fetchObject("uploadedfile");
					}
					if( is_object( $uploadedfile ) ){
						$uploadedfile->setConnection( $this->connection );
					}
					return $uploadedfile;
				}
			}
		}

		function getFromPost(){
			$this->setAliasID( (isset($_POST["aliasID"])) ? $_POST["aliasID"] : $this->getAliasID() );
			$this->setPath( (isset($_POST["path"])) ? $_POST["path"] : $this->getPath() );
			$this->setSize( (isset($_POST["size"])) ? $_POST["size"] : $this->getSize() );
			$this->setContentType( (isset($_POST["contentType"])) ? $_POST["contentType"] : $this->getContentType() );
			$this->setExtension( (isset($_POST["extension"])) ? $_POST["extension"] : $this->getExtension() );
			$this->setUploader( (isset($_POST["uploader"])) ? $_POST["uploader"] : $this->getUploader() );
			$this->setUploadDate( (isset($_POST["uploadDate"])) ? $_POST["uploadDate"] : $this->getUploadDate() );
			$this->setActive( (isset($_POST["active"])) ? $_POST["active"] : $this->getActive() );
			$this->setCampus( (isset($_POST["campus"])) ? $_POST["campus"] : $this->getCampus() );
			$this->setCategory( (isset($_POST["category"])) ? $_POST["category"] : $this->getCategory() );
			$this->setFaculty( (isset($_POST["faculty"])) ? $_POST["faculty"] : $this->getFaculty() );
			$this->setDescription( (isset($_POST["description"])) ? $_POST["description"] : $this->getDescription() );
			$this->setExpiry( (isset($_POST["expiry"])) ? $_POST["expiry"] : $this->getExpiry() );
			$this->setTags( (isset($_POST["tags"])) ? $_POST["tags"] : $this->getTags() );
			$this->setType( (isset($_POST["type"])) ? $_POST["type"] : $this->getType() );
		}

		function getFromRequest(){
			$this->setAliasID( (isset($_REQUEST["aliasID"])) ? $_REQUEST["aliasID"] : $this->getAliasID() );
			$this->setPath( (isset($_REQUEST["path"])) ? $_REQUEST["path"] : $this->getPath() );
			$this->setSize( (isset($_REQUEST["size"])) ? $_REQUEST["size"] : $this->getSize() );
			$this->setContentType( (isset($_REQUEST["contentType"])) ? $_REQUEST["contentType"] : $this->getContentType() );
			$this->setExtension( (isset($_REQUEST["extension"])) ? $_REQUEST["extension"] : $this->getExtension() );
			$this->setUploader( (isset($_REQUEST["uploader"])) ? $_REQUEST["uploader"] : $this->getUploader() );
			$this->setUploadDate( (isset($_REQUEST["uploadDate"])) ? $_REQUEST["uploadDate"] : $this->getUploadDate() );
			$this->setActive( (isset($_REQUEST["active"])) ? $_REQUEST["active"] : $this->getActive() );
			$this->setCampus( (isset($_REQUEST["campus"])) ? $_REQUEST["campus"] : $this->getCampus() );
			$this->setCategory( (isset($_REQUEST["category"])) ? $_REQUEST["category"] : $this->getCategory() );
			$this->setFaculty( (isset($_REQUEST["faculty"])) ? $_REQUEST["faculty"] : $this->getFaculty() );
			$this->setDescription( (isset($_REQUEST["description"])) ? $_REQUEST["description"] : $this->getDescription() );
			$this->setExpiry( (isset($_REQUEST["expiry"])) ? $_REQUEST["expiry"] : $this->getExpiry() );
			$this->setTags( (isset($_REQUEST["tags"])) ? $_REQUEST["tags"] : $this->getTags() );
			$this->setType( (isset($_REQUEST["type"])) ? $_REQUEST["type"] : $this->getType() );
		}

		function getFromArray($arr){
			$this->setAliasID( (isset($arr["aliasID"])) ? $arr["aliasID"] : $this->getAliasID() );
			$this->setPath( (isset($arr["path"])) ? $arr["path"] : $this->getPath() );
			$this->setSize( (isset($arr["size"])) ? $arr["size"] : $this->getSize() );
			$this->setContentType( (isset($arr["contentType"])) ? $arr["contentType"] : $this->getContentType() );
			$this->setExtension( (isset($arr["extension"])) ? $arr["extension"] : $this->getExtension() );
			$this->setUploader( (isset($arr["uploader"])) ? $arr["uploader"] : $this->getUploader() );
			$this->setUploadDate( (isset($arr["uploadDate"])) ? $arr["uploadDate"] : $this->getUploadDate() );
			$this->setActive( (isset($arr["active"])) ? $arr["active"] : $this->getActive() );
			$this->setCampus( (isset($arr["campus"])) ? $arr["campus"] : $this->getCampus() );
			$this->setCategory( (isset($arr["category"])) ? $arr["category"] : $this->getCategory() );
			$this->setFaculty( (isset($arr["faculty"])) ? $arr["faculty"] : $this->getFaculty() );
			$this->setDescription( (isset($arr["description"])) ? $arr["description"] : $this->getDescription() );
			$this->setExpiry( (isset($arr["expiry"])) ? $arr["expiry"] : $this->getExpiry() );
			$this->setTags( (isset($arr["tags"])) ? $arr["tags"] : $this->getTags() );
			$this->setType( (isset($arr["type"])) ? $arr["type"] : $this->getType() );
		}

		function compareTo($uploadedfile){
			$log = array();
			if($this->getId() != $uploadedfile->getId() ){
				$log["Id"] = "modified";
			}else{
				$log["Id"] = "un-modified";
			}
			if($this->getConnection() != $uploadedfile->getConnection() ){
				$log["Connection"] = "modified";
			}else{
				$log["Connection"] = "un-modified";
			}
			if($this->getErrors() != $uploadedfile->getErrors() ){
				$log["Errors"] = "modified";
			}else{
				$log["Errors"] = "un-modified";
			}
			if($this->getErrorCount() != $uploadedfile->getErrorCount() ){
				$log["ErrorCount"] = "modified";
			}else{
				$log["ErrorCount"] = "un-modified";
			}
			if($this->getAliasID() != $uploadedfile->getAliasID() ){
				$log["AliasID"] = "modified";
			}else{
				$log["AliasID"] = "un-modified";
			}
			if($this->getPath() != $uploadedfile->getPath() ){
				$log["Path"] = "modified";
			}else{
				$log["Path"] = "un-modified";
			}
			if($this->getSize() != $uploadedfile->getSize() ){
				$log["Size"] = "modified";
			}else{
				$log["Size"] = "un-modified";
			}
			if($this->getContentType() != $uploadedfile->getContentType() ){
				$log["ContentType"] = "modified";
			}else{
				$log["ContentType"] = "un-modified";
			}
			if($this->getExtension() != $uploadedfile->getExtension() ){
				$log["Extension"] = "modified";
			}else{
				$log["Extension"] = "un-modified";
			}
			if($this->getUploader() != $uploadedfile->getUploader() ){
				$log["Uploader"] = "modified";
			}else{
				$log["Uploader"] = "un-modified";
			}
			if($this->getUploadDate() != $uploadedfile->getUploadDate() ){
				$log["UploadDate"] = "modified";
			}else{
				$log["UploadDate"] = "un-modified";
			}
			if($this->getActive() != $uploadedfile->getActive() ){
				$log["Active"] = "modified";
			}else{
				$log["Active"] = "un-modified";
			}
			if($this->getCampus() != $uploadedfile->getCampus() ){
				$log["Campus"] = "modified";
			}else{
				$log["Campus"] = "un-modified";
			}
			if($this->getCategory() != $uploadedfile->getCategory() ){
				$log["Category"] = "modified";
			}else{
				$log["Category"] = "un-modified";
			}
			if($this->getFaculty() != $uploadedfile->getFaculty() ){
				$log["Faculty"] = "modified";
			}else{
				$log["Faculty"] = "un-modified";
			}
			if($this->getDescription() != $uploadedfile->getDescription() ){
				$log["Description"] = "modified";
			}else{
				$log["Description"] = "un-modified";
			}
			if($this->getExpiry() != $uploadedfile->getExpiry() ){
				$log["Expiry"] = "modified";
			}else{
				$log["Expiry"] = "un-modified";
			}
			if($this->getTags() != $uploadedfile->getTags() ){
				$log["Tags"] = "modified";
			}else{
				$log["Tags"] = "un-modified";
			}
			if($this->getType() != $uploadedfile->getType() ){
				$log["Type"] = "modified";
			}else{
				$log["Type"] = "un-modified";
			}
		return $log;
		}

		function save(){
			$id = $this->getId();
			$aliasID = $this->getAliasID();
			$path = $this->getPath();
			$size = $this->getSize();
			$contentType = $this->getContentType();
			$extension = $this->getExtension();
			$uploader = $this->getUploader();
			$uploadDate = $this->getUploadDate();
			$active = $this->getActive();
			$campus = $this->getCampus();
			$category = $this->getCategory();
			$faculty = $this->getFaculty();
			$description = $this->getDescription();
			$expiry = $this->getExpiry();
			$tags = $this->getTags();
			$type = $this->getType();
			if( $this->connection ){
				if( $id != "" ){
					/*Perform Update Operation*/
					$query = $this->connection->prepare("UPDATE  `uploadedfile` SET `aliasID` = :aliasID ,`path` = :path ,`size` = :size ,`contentType` = :contentType ,`extension` = :extension ,`uploader` = :uploader ,`uploadDate` = :uploadDate ,`active` = :active ,`campus` = :campus ,`category` = :category ,`faculty` = :faculty ,`description` = :description ,`expiry` = :expiry ,`tags` = :tags ,`type` = :type WHERE `id` = :id");
					$query->bindParam('aliasID', $aliasID);
					$query->bindParam('path', $path);
					$query->bindParam('size', $size);
					$query->bindParam('contentType', $contentType);
					$query->bindParam('extension', $extension);
					$query->bindParam('uploader', $uploader);
					$query->bindParam('uploadDate', $uploadDate);
					$query->bindParam('active', $active);
					$query->bindParam('campus', $campus);
					$query->bindParam('category', $category);
					$query->bindParam('faculty', $faculty);
					$query->bindParam('description', $description);
					$query->bindParam('expiry', $expiry);
					$query->bindParam('tags', $tags);
					$query->bindParam('type', $type);
					$query->bindParam('id', $id);
					if( $query->execute() ){
						return $id;
					}else{
						return -1;
					}

				}else{
					/*Perform Insert Operation*/
					$query = $this->connection->prepare("INSERT INTO `uploadedfile` (`id`,`aliasID`,`path`,`size`,`contentType`,`extension`,`uploader`,`uploadDate`,`active`,`campus`,`category`,`faculty`,`description`,`expiry`,`tags`,`type`) VALUES (NULL,:aliasID,:path,:size,:contentType,:extension,:uploader,:uploadDate,:active,:campus,:category,:faculty,:description,:expiry,:tags,:type);");
					$query->bindParam(':aliasID', $aliasID);
					$query->bindParam(':path', $path);
					$query->bindParam(':size', $size);
					$query->bindParam(':contentType', $contentType);
					$query->bindParam(':extension', $extension);
					$query->bindParam(':uploader', $uploader);
					$query->bindParam(':uploadDate', $uploadDate);
					$query->bindParam(':active', $active);
					$query->bindParam(':campus', $campus);
					$query->bindParam(':category', $category);
					$query->bindParam(':faculty', $faculty);
					$query->bindParam(':description', $description);
					$query->bindParam(':expiry', $expiry);
					$query->bindParam(':tags', $tags);
					$query->bindParam(':type', $type);

					if( $query->execute() ){
						$this->setId( $this->connection->lastInsertId() );
						return $this->getId();
					}else{
						return -1;
					}	
				}
			}
		}


		function delete($id = null){
			if( $this->connection ){
				if( $id == null && $this->getId() != ""){
					$id = $this->getId();
				}

				/*Perform Query*/
				if( $id != "" ){
					$query = $this->connection->prepare("DELETE FROM `uploadedfile` WHERE `id` = :id");
					$query->bindParam(':id', $id);
					if( $query->execute() ){
						return 1;
					}else{
						return 0;
					}
				}
			}
		}

		function getById($id){
			if( $this->connection ){
				if( $id == null && $this->getId() != ""){
					$id = $this->getId();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `id` = :id LIMIT 1");
				$query->bindParam(':id', $id);
				$object = null;

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$object = $result;
					}

				}
				if( is_object( $object ) ){
					return $object;
				}
			}
		}

		function getByAliasID($aliasID){
			if( $this->connection ){
				if( $aliasID == null && $this->getAliasID() != ""){
					$aliasID = $this->getAliasID();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `aliasID` = :aliasID LIMIT 1");
				$query->bindParam(':aliasID', $aliasID);
				$object = null;

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$object = $result;
					}

				}
				if( is_object( $object ) ){
					return $object;
				}
			}
		}

		function getByPath($path){
			if( $this->connection ){
				if( $path == null && $this->getPath() != ""){
					$path = $this->getPath();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `path` = :path LIMIT 1");
				$query->bindParam(':path', $path);
				$object = null;

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$object = $result;
					}

				}
				if( is_object( $object ) ){
					return $object;
				}
			}
		}

		function getBySize($size){
			if( $this->connection ){
				if( $size == null && $this->getSize() != ""){
					$size = $this->getSize();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `size` = :size LIMIT 1");
				$query->bindParam(':size', $size);
				$object = null;

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$object = $result;
					}

				}
				if( is_object( $object ) ){
					return $object;
				}
			}
		}

		function getByContentType($contentType){
			if( $this->connection ){
				if( $contentType == null && $this->getContentType() != ""){
					$contentType = $this->getContentType();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `contentType` = :contentType LIMIT 1");
				$query->bindParam(':contentType', $contentType);
				$object = null;

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$object = $result;
					}

				}
				if( is_object( $object ) ){
					return $object;
				}
			}
		}

		function getByExtension($extension){
			if( $this->connection ){
				if( $extension == null && $this->getExtension() != ""){
					$extension = $this->getExtension();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `extension` = :extension LIMIT 1");
				$query->bindParam(':extension', $extension);
				$object = null;

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$object = $result;
					}

				}
				if( is_object( $object ) ){
					return $object;
				}
			}
		}

		function getByUploader($uploader){
			if( $this->connection ){
				if( $uploader == null && $this->getUploader() != ""){
					$uploader = $this->getUploader();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `uploader` = :uploader LIMIT 1");
				$query->bindParam(':uploader', $uploader);
				$object = null;

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$object = $result;
					}

				}
				if( is_object( $object ) ){
					return $object;
				}
			}
		}

		function getByUploadDate($uploadDate){
			if( $this->connection ){
				if( $uploadDate == null && $this->getUploadDate() != ""){
					$uploadDate = $this->getUploadDate();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `uploadDate` = :uploadDate LIMIT 1");
				$query->bindParam(':uploadDate', $uploadDate);
				$object = null;

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$object = $result;
					}

				}
				if( is_object( $object ) ){
					return $object;
				}
			}
		}

		function getByActive($active){
			if( $this->connection ){
				if( $active == null && $this->getActive() != ""){
					$active = $this->getActive();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `active` = :active LIMIT 1");
				$query->bindParam(':active', $active);
				$object = null;

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$object = $result;
					}

				}
				if( is_object( $object ) ){
					return $object;
				}
			}
		}

		function getByCampus($campus){
			if( $this->connection ){
				if( $campus == null && $this->getCampus() != ""){
					$campus = $this->getCampus();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `campus` = :campus LIMIT 1");
				$query->bindParam(':campus', $campus);
				$object = null;

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$object = $result;
					}

				}
				if( is_object( $object ) ){
					return $object;
				}
			}
		}

		function getByCategory($category){
			if( $this->connection ){
				if( $category == null && $this->getCategory() != ""){
					$category = $this->getCategory();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `category` = :category LIMIT 1");
				$query->bindParam(':category', $category);
				$object = null;

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$object = $result;
					}

				}
				if( is_object( $object ) ){
					return $object;
				}
			}
		}

		function getByFaculty($faculty){
			if( $this->connection ){
				if( $faculty == null && $this->getFaculty() != ""){
					$faculty = $this->getFaculty();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `faculty` = :faculty LIMIT 1");
				$query->bindParam(':faculty', $faculty);
				$object = null;

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$object = $result;
					}

				}
				if( is_object( $object ) ){
					return $object;
				}
			}
		}

		function getByDescription($description){
			if( $this->connection ){
				if( $description == null && $this->getDescription() != ""){
					$description = $this->getDescription();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `description` = :description LIMIT 1");
				$query->bindParam(':description', $description);
				$object = null;

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$object = $result;
					}

				}
				if( is_object( $object ) ){
					return $object;
				}
			}
		}

		function getByExpiry($expiry){
			if( $this->connection ){
				if( $expiry == null && $this->getExpiry() != ""){
					$expiry = $this->getExpiry();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `expiry` = :expiry LIMIT 1");
				$query->bindParam(':expiry', $expiry);
				$object = null;

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$object = $result;
					}

				}
				if( is_object( $object ) ){
					return $object;
				}
			}
		}

		function getByTags($tags){
			if( $this->connection ){
				if( $tags == null && $this->getTags() != ""){
					$tags = $this->getTags();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `tags` = :tags LIMIT 1");
				$query->bindParam(':tags', $tags);
				$object = null;

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$object = $result;
					}

				}
				if( is_object( $object ) ){
					return $object;
				}
			}
		}

		function getByType($type){
			if( $this->connection ){
				if( $type == null && $this->getType() != ""){
					$type = $this->getType();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `type` = :type LIMIT 1");
				$query->bindParam(':type', $type);
				$object = null;

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$object = $result;
					}

				}
				if( is_object( $object ) ){
					return $object;
				}
			}
		}


		function getListById($id=null){
			if( $this->connection ){
				if( $id == null && $this->getId() != ""){
					$id = $this->getId();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `id` = :id");
				$query->bindParam(':id', $id);

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$uploadedfiles[] = $result;
					}
					if( is_array( $uploadedfiles ) ){
						return $uploadedfiles;
					}else{
						return array();
					}

				}
			}
		}

		function getListByAliasID($aliasID=null){
			if( $this->connection ){
				if( $aliasID == null && $this->getAliasID() != ""){
					$aliasID = $this->getAliasID();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `aliasID` = :aliasID");
				$query->bindParam(':aliasID', $aliasID);

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$uploadedfiles[] = $result;
					}
					if( is_array( $uploadedfiles ) ){
						return $uploadedfiles;
					}else{
						return array();
					}

				}
			}
		}

		function getListByPath($path=null){
			if( $this->connection ){
				if( $path == null && $this->getPath() != ""){
					$path = $this->getPath();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `path` = :path");
				$query->bindParam(':path', $path);

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$uploadedfiles[] = $result;
					}
					if( is_array( $uploadedfiles ) ){
						return $uploadedfiles;
					}else{
						return array();
					}

				}
			}
		}

		function getListBySize($size=null){
			if( $this->connection ){
				if( $size == null && $this->getSize() != ""){
					$size = $this->getSize();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `size` = :size");
				$query->bindParam(':size', $size);

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$uploadedfiles[] = $result;
					}
					if( is_array( $uploadedfiles ) ){
						return $uploadedfiles;
					}else{
						return array();
					}

				}
			}
		}

		function getListByContentType($contentType=null){
			if( $this->connection ){
				if( $contentType == null && $this->getContentType() != ""){
					$contentType = $this->getContentType();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `contentType` = :contentType");
				$query->bindParam(':contentType', $contentType);

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$uploadedfiles[] = $result;
					}
					if( is_array( $uploadedfiles ) ){
						return $uploadedfiles;
					}else{
						return array();
					}

				}
			}
		}

		function getListByExtension($extension=null){
			if( $this->connection ){
				if( $extension == null && $this->getExtension() != ""){
					$extension = $this->getExtension();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `extension` = :extension");
				$query->bindParam(':extension', $extension);

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$uploadedfiles[] = $result;
					}
					if( is_array( $uploadedfiles ) ){
						return $uploadedfiles;
					}else{
						return array();
					}

				}
			}
		}

		function getListByUploader($uploader=null){
			if( $this->connection ){
				if( $uploader == null && $this->getUploader() != ""){
					$uploader = $this->getUploader();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `uploader` = :uploader");
				$query->bindParam(':uploader', $uploader);

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$uploadedfiles[] = $result;
					}
					if( is_array( $uploadedfiles ) ){
						return $uploadedfiles;
					}else{
						return array();
					}

				}
			}
		}

		function getListByUploadDate($uploadDate=null){
			if( $this->connection ){
				if( $uploadDate == null && $this->getUploadDate() != ""){
					$uploadDate = $this->getUploadDate();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `uploadDate` = :uploadDate");
				$query->bindParam(':uploadDate', $uploadDate);

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$uploadedfiles[] = $result;
					}
					if( is_array( $uploadedfiles ) ){
						return $uploadedfiles;
					}else{
						return array();
					}

				}
			}
		}

		function getListByActive($active=null){
			if( $this->connection ){
				if( $active == null && $this->getActive() != ""){
					$active = $this->getActive();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `active` = :active");
				$query->bindParam(':active', $active);

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$uploadedfiles[] = $result;
					}
					if( is_array( $uploadedfiles ) ){
						return $uploadedfiles;
					}else{
						return array();
					}

				}
			}
		}

		function getListByCampus($campus=null){
			if( $this->connection ){
				if( $campus == null && $this->getCampus() != ""){
					$campus = $this->getCampus();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `campus` = :campus");
				$query->bindParam(':campus', $campus);

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$uploadedfiles[] = $result;
					}
					if( is_array( $uploadedfiles ) ){
						return $uploadedfiles;
					}else{
						return array();
					}

				}
			}
		}

		function getListByCategory($category=null){
			if( $this->connection ){
				if( $category == null && $this->getCategory() != ""){
					$category = $this->getCategory();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `category` = :category");
				$query->bindParam(':category', $category);

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$uploadedfiles[] = $result;
					}
					if( is_array( $uploadedfiles ) ){
						return $uploadedfiles;
					}else{
						return array();
					}

				}
			}
		}

		function getListByFaculty($faculty=null){
			if( $this->connection ){
				if( $faculty == null && $this->getFaculty() != ""){
					$faculty = $this->getFaculty();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `faculty` = :faculty");
				$query->bindParam(':faculty', $faculty);

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$uploadedfiles[] = $result;
					}
					if( is_array( $uploadedfiles ) ){
						return $uploadedfiles;
					}else{
						return array();
					}

				}
			}
		}

		function getListByDescription($description=null){
			if( $this->connection ){
				if( $description == null && $this->getDescription() != ""){
					$description = $this->getDescription();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `description` = :description");
				$query->bindParam(':description', $description);

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$uploadedfiles[] = $result;
					}
					if( is_array( $uploadedfiles ) ){
						return $uploadedfiles;
					}else{
						return array();
					}

				}
			}
		}

		function getListByExpiry($expiry=null){
			if( $this->connection ){
				if( $expiry == null && $this->getExpiry() != ""){
					$expiry = $this->getExpiry();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `expiry` = :expiry");
				$query->bindParam(':expiry', $expiry);

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$uploadedfiles[] = $result;
					}
					if( is_array( $uploadedfiles ) ){
						return $uploadedfiles;
					}else{
						return array();
					}

				}
			}
		}

		function getListByTags($tags=null){
			if( $this->connection ){
				if( $tags == null && $this->getTags() != ""){
					$tags = $this->getTags();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `tags` = :tags");
				$query->bindParam(':tags', $tags);

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$uploadedfiles[] = $result;
					}
					if( is_array( $uploadedfiles ) ){
						return $uploadedfiles;
					}else{
						return array();
					}

				}
			}
		}

		function getListByType($type=null){
			if( $this->connection ){
				if( $type == null && $this->getType() != ""){
					$type = $this->getType();
				}

				/*Perform Query*/
				$query = $this->connection->prepare("SELECT * FROM `uploadedfile` WHERE `type` = :type");
				$query->bindParam(':type', $type);

				if( $query->execute() ){
					while( $result = $query->fetchObject("uploadedfile") ){
						$uploadedfiles[] = $result;
					}
					if( is_array( $uploadedfiles ) ){
						return $uploadedfiles;
					}else{
						return array();
					}

				}
			}
		}

		/*Return parameter (object) as Array*/
		function toArray ($obj=null) {
			if (is_object($obj)) $obj = (array)$obj;
			if (is_array($obj)) {
				$new = array();
				foreach ($obj as $key => $val) {
					$class = get_class($this);
					$k = $key;
					$fkey = trim( str_replace( $class,"",$k));
					if( $fkey == "connection" || $fkey == "errors" || $fkey == "errorCount" ){
						//dont add
					}else{
						$new[$fkey] = $this->toArray($val);
					}
				}
			} else {
				$new = $obj;
			}
			return $new;
		}

		/*Return object as Array*/
		function asArray(){
			$array = $this->toArray( $this );
			return $array;
		}

		/*Return object as JSON String*/
		function asJSON(){
			return json_encode($this->asArray());
		}

		/*Return clone of Object*/
		function getClone(){
			return clone($this);
		}


		/*Echo array as CSV file*/
		function arrayToCSVFile($array, $filename="uploadedfile.csv", $delimiter=",", $showHeader=true){
			ob_clean();
			if( !is_array($array) ){
				$array = $this->asArray();
			}
			if( !is_array($showHeader) && $showHeader == true){
				$header=array();
				foreach( $array[0] as $key => $value){
					$header[] = strtoupper($key);
				}
				array_unshift($array, $header);
			}
			if( is_array($showHeader) ){
				array_unshift($array, $showHeader);
			}
			header('Content-Type: application/csv; charset=UTF-8');
			header('Content-Disposition: attachement; filename="'.$filename.'";');
			$f = fopen('php://output', 'w');
			foreach ($array as $line) {
				fputcsv($f, $line, $delimiter);
			}
			exit;
		}


		/*getObjectsLikeThis - returns array*/
		function getObjectsLikeThis($asArray=true){
			if( $this->connection ){
				$buildQuery="SELECT * FROM `uploadedfile` WHERE ";
				$numParams = 0;
				$values = array();
				foreach ($this as $key => $value) {
					if( $value != "" && $key != "id" && $key != "connection" && $key != "error" && $key != "errorCount"){
						$buildQuery.="`".$key."` = :value_".$numParams." AND ";
						$numParams++;
						$values[] = $value;
					}
				}
				if( $numParams > 0 ){
					//remove last AND
					$buildQuery = substr( $buildQuery , 0, (strlen($buildQuery) -4) );
					$query = $this->connection->PREPARE($buildQuery);
					for($i=0; $i < $numParams; $i++){
						$query->bindParam(":value_".$i, $values[$i]);
					}
					if( $query->execute() ){
						if( $asArray == true ){
							return $query->fetchAll(PDO::FETCH_ASSOC);
						}else{
							$objArray = array();
							while( $result = $query->fetchObject("uploadedfile") ){
								$object = $result;
								$objArray[] = $object;
							}
							return $objArray;
						}
					}
				}
			}
		}

		/*get properties*/
		function getObjectsProperties(){
			$properties = array();
			foreach ($this as $key => $value) {
				if( $key != "id" && $key != "connection" && $key != "error" && $key != "errorCount"){
					$properties[] = $key;
				}
			}
			return $properties;
		}
		/*Human readable print out of object*/
		function printFormatted($return=false){
			if($return){
				return '<pre>'.print_r( $this->asArray(), true ).'</pre>';
			}else{
				echo '<pre>'.print_r( $this->asArray(), true ).'</pre>';
			}
		}

	}
?>