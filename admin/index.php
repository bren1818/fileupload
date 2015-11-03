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
	
	</script>
	<style>
		#file-upload{
			margin: 0 auto;
			max-width: 1300px;
		}
	
		fieldset,
		.dataTable{ width: 100%; }
		
		fieldset{margin: 10px 0; }
	</style>
</head>
<body>
	<div id="file-upload">
		<?php
			menubar();
			echo '<h1>Welcome Back: '.$_SESSION['name'].'</h1>';
		//jquery Data tables..
		?>
		<fieldset>
			<legend>Files</legend>
			<table id="files" class="dataTable display" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>
							ID
						</th>
						<th>
							FileName
						</th>
						<th>
							Alias
						</th>
						<th>
							Active
						</th>
						<th>
							Campus
						</th>
						<th>
							Category
						</th>
						<th>
							Faculty
						</th>
						<th>
							Tags
						</th>
						<th>
							Type
						</th>
						<th>
							owner
						</th>
						<th>
							expiry
						</th>
						<th>
							controls
						</th>
					</tr>
				</thead>
				<tbody>
				<?php
				
					$conn = getConnection();
					
					$query ="SELECT 
						uf.`id`, uf.`path`, a.`alias` , uf.`active`, uf.`campus`, uf.`category`, uf.`faculty`, uf.`tags`, uf.`type`, uf.`uploader`, uf.`expiry` 
					FROM
						`uploadedfile` uf
					LEFT JOIN
						`alias` a 
					ON
						a.`id` = uf.`aliasID`
					
					ORDER BY uf.`id` ASC
					LIMIT 10
					";
					$result = $conn->prepare($query);
					$result->execute();
					
					foreach( $result->fetchAll(PDO::FETCH_ASSOC) as $row){
						echo '<tr>';
						//echo '<td>'.implode('</td><td>',$row).'</td>'; //so easy :D
						echo '<td>'.$row['id'].'</td>';
						//echo '<td>'.end(explode($row['path'], DIRECTORY_SEPARATOR)).'</td>';
						echo '<td>'.'<a target="_blank" href="'.WEB_ROOT.UPLOAD_FOLDER_NAME.$row['path'].'">'.substr( $row['path'], (strrpos($row['path'], DIRECTORY_SEPARATOR) + 1) ).'</a>'.'</td>';
						echo '<td>'.$row['alias'].'</td>';
						echo '<td>'.($row['active']==1?'Yes':'No').'</td>';
						echo '<td>'.$row['campus'].'</td>';
						echo '<td>'.$row['category'].'</td>';
						echo '<td>'.$row['faculty'].'</td>';
						echo '<td>'.$row['tags'].'</td>';
						echo '<td>'.$row['type'].'</td>';
						echo '<td>'.$row['uploader'].'</td>';
						echo '<td>'.$row['expiry'].'</td>';
						?>
						<td>
							<a href="">Delete</a> <a href="">Modify</a>
						</td>
						<?php
						echo '</tr>';
					}
					
					
				?>
				</tbody>
				<tfoot>
					<tr>
						<th>
							ID
						</th>
						<th>
							FileName
						</th>
						<th>
							Alias
						</th>
						<th>
							Active
						</th>
						<th>
							Campus
						</th>
						<th>
							Category
						</th>
						<th>
							Faculty
						</th>
						<th>
							Tags
						</th>
						<th>
							Type
						</th>
						<th>
							owner
						</th>
						<th>
							expiry
						</th>
					</tr>
				</tfoot>
			</table>
		</fieldset>
		
		<fieldset>
			<legend>Aliases</legend>
			<table id="aliases" class="dataTable display" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>
							ID
						</th>
						<th>
							Alias
						</th>
						<th>
							Type
						</th>
						<th>
							Pointer
						</th>
						<th>
							controls
						</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$query = "SELECT * FROM `alias` limit 10";
					$result = $conn->prepare($query);
					$result->execute();
					
					foreach( $result->fetchAll(PDO::FETCH_ASSOC) as $row){
						echo '<tr>';
						echo '<td>'.$row['id'].'</td>';
						echo '<td>'.$row['type'].'</td>';
						echo '<td>'.$row['alias'].'</td>';
						echo '<td>'.($row['type']=='file'?'File ID: '.$row['pointer']:'<a href="'.$row['pointer'].'">'.$row['pointer'].'</a>').'</td>';
						?>
						<td>
							<a href="deleteAlias.php?aliasID=<?php echo $row['id'].'&key='.hash('md5',KEY.$row['alias']); ?>">Delete</a> <a href="">Modify</a>
						</td>
						<?php
					}	
				?>
				</tbody>
				<tfoot>
					<tr>
						<th>
							ID
						</th>
						<th>
							Alias
						</th>
						<th>
							Type
						</th>
						<th>
							Pointer
						</th>
						<th>
							controls
						</th>
					</tr>
				</tfoot>
		</fieldset>
			<script>
		$(function(){
			$('#files').DataTable({
				"processing": true,
        		"serverSide": true,
        		"ajax": "/admin/ajaxTable.php?type=files",
				"columns" :  [ {"name": "0", "orderable": "true"}, {"name": "1", "orderable": "true"}, {"name": "2", "orderable": "true"}, {"name": "3", "orderable": "true"} , {"name": "4", "orderable": "true" }, {"name": "5", "orderable": "true"}, {"name": "6", "orderable": "true"}, {"name": "7", "orderable": "true"} , {"name": "8", "orderable": "true" }, {"name": "9", "orderable": "true"} , {"name": "10", "orderable": "true" }, {"name": "11", "orderable": false } ] 
			
			});
			
			$('#aliases').DataTable({
				"processing": true,
        		"serverSide": true,
        		"ajax": "/admin/ajaxTable.php?type=aliases",
				"columns" :  [ {"name": "0", "orderable": "true"}, {"name": "1", "orderable": "true"}, {"name": "2", "orderable": "true"}, {"name": "3", "orderable": "true"} , {"name": "4", "orderable": false } ] 
			});
			
		});
		</script>
		
		
	</div>
</body>
</html>