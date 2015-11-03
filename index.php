<?php
	include "admin/config.inc.php";	
	$request = $_SERVER['REQUEST_URI'];
	
	if( trim($request) == "" || strpos($request,'/') === false  ){
		//no request.
		exit;
	}
	
	//check for alias
	$conn = getConnection();
	$query = $conn->prepare(
	"SELECT 
		uf.`path`,
		a.`type`,
		uf.`active`,
		uf.`expiry`
	FROM
		`uploadedfile` uf	
	INNER JOIN `alias` a ON
		uf.`aliasID` = a.`id` 
	WHERE 
		a.`alias` = :alias AND a.`type` = 'file'
	"
	);
	$query->bindParam(':alias', $request);
	
	$query->execute();
	
	$results = $query->fetchAll(PDO::FETCH_ASSOC);
	
	if( sizeof($results) > 0){
		if( $results[0]['type'] == "file" ){
			$path = $results[0]['path'];
			$path = UPLOADS_DIR . $path;
			
			$file = realpath($path);
			
			if( file_exists($file) ){
				ob_clean();
				
				
				if( $results[0]['active'] == 0  ){
					header("HTTP/1.0 404 Not Found");
					render404($request);
					exit;
					
				}
				
				if( $results[0]['expiry'] != '0000-00-00 00:00:00' ){
					$expiry = date('Y-m-d H:i:s',strtotime($results[0]['expiry']));
					$today = date('Y-m-d H:i:s');
					if( $today > $expiry){
						header("HTTP/1.0 410 Gone");
						renderExpired($request);
						exit;
					}
				}
			
				//header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="'.basename($file).'"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($file));
				readfile($file);
				exit;
			}else{
				header("HTTP/1.0 404 Not Found");
				render404($request);
				exit;
			}
			
		}
		
	}else{
		
		$query = $conn->prepare(
		"SELECT 
			a.`pointer`
		FROM
			`alias` a
		WHERE 
			a.`alias` = :alias AND a.`type` != 'file'
		"
		);
		$query->bindParam(':alias', $request);
		
		$query->execute();
		
		$results = $query->fetchAll(PDO::FETCH_ASSOC);
		
		if( sizeof($results) > 0){
			ob_clean();
			header('Location: '.$results[0]['pointer'] );
		}else{
			if( $request != "/" ){
				header("HTTP/1.0 404 Not Found");
				render404($request);
			}
			exit;
		}
	}
?>