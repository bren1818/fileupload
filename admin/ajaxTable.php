<?php
error_reporting(E_ALL);

session_start();
include "config.inc.php";

if( !isset($_SESSION['username']) ){
	exit;
}

$type = $_GET['type'];
$draw = $_GET['draw']; // current ID?
$start = $_GET['start'];
$length =  (int)($_GET['length']);

$orderBy = (int)$_GET['order'][0]['column'];
$orderDir = $_GET['order'][0]['dir'];


$orderDir = ($orderDir == "asc" ? "ASC" : "DESC");

//order[0][column]:2
//order[0][dir]:desc


$search = issetOrBlank($_GET['search']['value']);

if( $search != ""){
	$search = "%".$search."%";
}

if( $start < 0 || !is_int($start) ){
	$start = 0;
}


//https://datatables.net/manual/server-side

if( $type == "files" ){
	$conn = getConnection();
	
	$query = "SELECT COUNT(`id`) as `cnt` FROM `uploadedfile`";
	$result = $conn->prepare($query);
	$result->execute();		
	$result = $result->fetchAll(PDO::FETCH_ASSOC);
	$recordsTotal = $result[0]['cnt'];
	

	
	if( $orderBy != "" && is_int($orderBy) ){
		switch($orderBy){
			case 0:
				$orderBy = "uf.`id`";
			break;
			case 1:
				$orderBy = "`file`"; 
			break;	
			case 2:
				$orderBy = "a.`alias`";
			break;	
			case 3:
				$orderBy = "uf.`active`";
			break;				
			case 4:
				$orderBy = "uf.`campus`";
			break;	
			case 5:
				$orderBy = "uf.`category`";
			break;	
			case 6:
				$orderBy = "uf.`faculty`";
			break;	
			case 7:
				$orderBy = "uf.`tags`";
			break;
			case 8:
				$orderBy = "uf.`type`";
			break;
			case 9:
				$orderBy = "uf.`uploader`";
			break;
			case 10:
				$orderBy = "uf.`expiry`";
			break;
			default:
				$orderBy = "uf.`id`";
			break;
		}
	}else{
		$orderBy = "uf.`id`";
	}

	
	
	$query = "SELECT 
				uf.`id`, SUBSTRING_INDEX(uf.`path`,'".DIRECTORY_SEPARATOR."',-1) as `file`, a.`alias` , uf.`active`, uf.`campus`, uf.`category`, uf.`faculty`, uf.`tags`, uf.`type`, uf.`uploader`, uf.`expiry`, uf.`path` 
			FROM
				`uploadedfile` uf
			LEFT JOIN
				`alias` a 
			ON
				a.`id` = uf.`aliasID`
			WHERE
				".( $search != "" ? "(uf.`path` ".($search != "" ? " LIKE :SEARCH": "").") OR (uf.`tags` ".($search != "" ? " LIKE :SEARCH": "").") OR (a.`alias` ".($search != "" ? " LIKE :SEARCH": "").") OR (uf.`uploader` ".($search != "" ? " LIKE :SEARCH": "").")" : " 1 ") ."
		
			ORDER BY 
				".$orderBy." ".$orderDir."
			LIMIT :START,:LENGTH";
					
	$result = $conn->prepare($query);
	$result->bindParam(':LENGTH', $length, PDO::PARAM_INT);
	$result->bindParam(':START', $start, PDO::PARAM_INT);
	if( $search != ""){
		$result->bindParam(':SEARCH', $search, PDO::PARAM_STR);
	}
	
	$result->execute();		
	
	
	$results = $result->fetchAll(PDO::FETCH_ASSOC);
	
	
	$data = "";
	foreach( $results as $row){
		$data[] =  array(
			$row['id'], '<a target="_blank" href="'.WEB_ROOT.UPLOAD_FOLDER_NAME.$row['path'].'">'.$row['file'].'</a>', ($row['alias'] == "" ? "-" : $row['alias']) , ($row['active']==1?'Yes':'No'), $row['campus'], $row['category'], $row['faculty'], $row['tags'], $row['type'], $row['uploader'], ($row['expiry'] == "0000-00-00 00:00:00" ? "" : substr($row['expiry'],0, 10) ) , '<a href="deleteFile.php?fileID='.$row['id'].'&key='.hash('md5',KEY.$row['path']).'">Delete</a> <a href="modifyFile.php?fileID='.$row['id'].'&key='.hash('md5',KEY.$row['path']).'">Modify</a>'
		);
	}
	
	$recordsFiltered =  sizeof( $data ) ;
	ob_clean();
	echo json_encode( array( "draw" => (int)($draw), "recordsTotal" => $recordsTotal, "recordsFiltered" => $recordsFiltered, "data" => $data, "error" => "") ); 
	exit;
	
	
}else{
	$conn = getConnection();
	
	$query = "SELECT COUNT(`id`) as `cnt` FROM `alias`";
	$result = $conn->prepare($query);
	$result->execute();		
	$result = $result->fetchAll(PDO::FETCH_ASSOC);
	$recordsTotal = $result[0]['cnt'];
	
	
	$query = "SELECT 
				`id`, `type`, `alias` , `pointer` 
			FROM
				`alias` 
			WHERE
				".( $search != "" ? "(`pointer` ".($search != "" ? " LIKE :SEARCH": "").") OR (`alias` ".($search != "" ? " LIKE :SEARCH": "").") OR (`type` ".($search != "" ? " LIKE :SEARCH": "").")" : " 1 ") ."
		
			ORDER BY 
				`id` ".$orderDir."
			LIMIT :START,:LENGTH";
					
	$result = $conn->prepare($query);
	$result->bindParam(':LENGTH', $length, PDO::PARAM_INT);
	$result->bindParam(':START', $start, PDO::PARAM_INT);

	if( $search != ""){
		$result->bindParam(':SEARCH', $search, PDO::PARAM_STR);
	}
	
	$result->execute();		
	
	
	$results = $result->fetchAll(PDO::FETCH_ASSOC);
	
	
	$data = "";
	foreach( $results as $row){
		$data[] =  array(
			$row['id'], $row['alias'], $row['type'], ($row['type'] == "url" ? $row['pointer'] : 'File ID: '.$row['pointer']), '<a href="deleteAlias.php?aliasID='.$row['id'].'&key='.hash('md5',KEY.$row['alias']).'">Delete</a> <a href="modifyAlias.php?aliasID='.$row['id'].'&key='.hash('md5',KEY.$row['alias']).'">Modify</a>'
		);
	}
	
	$recordsFiltered =  sizeof( $data ) ;
	ob_clean();
	echo json_encode( array( "draw" => (int)($draw), "recordsTotal" => $recordsTotal, "recordsFiltered" => $recordsFiltered, "data" => $data, "error" => "") ); 
	exit;
	
	
}
?>