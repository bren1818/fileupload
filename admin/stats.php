<?php
	include "config.inc.php";
	$conn = getConnection();
	adminPage();
?>
<html>
<head>
	<title>Stats</title>
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
		
		fieldset{
			margin-bottom: 20px;
		}
		
		td{ padding: 5px; }
	</style>
</head>
<body>
	
	<div id="file-upload">
	<?php
	menubar();
	?>
	
	<h1>DB Stats</h1>
	
	<fieldset>
		<legend>Alias Details</legend>
		<?php
			$query ="SELECT `type`, COUNT(`id`) as `count` FROM `alias` GROUP BY `type`";
			$result = $conn->prepare($query);
			$result->execute();
			$result = $result->fetchAll(PDO::FETCH_ASSOC);
		?>
		<table>
		<tr>
			<td>Type</td><td>Count</td>
		</tr>
		<?php
			foreach( $result as $row){
				echo '<tr>';
					echo '<td>'.strtoupper($row['type']).'</td><td>'.$row['count'].'</td>';
				echo '</tr>';
			}
			
		?>
		</table>
	</fieldset>
	
	<fieldset>
		<legend>File Details</legend>
		<?php
		$query ="SELECT `uploader`, COUNT(`id`) as `numfiles`, sum(`size`) as `filesize` FROM `uploadedfile` GROUP BY `uploader`";
		$result = $conn->prepare($query);
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);
		
		?>
		<table>
		<tr>
			<td>Uploader</td><td>Number of Uploads</td><td>Total Size</td>
		</tr>
		<?php
			foreach( $result as $row){
				echo '<tr>';
					echo '<td>'.$row['uploader'].'</td><td>'.$row['numfiles'].'</td><td>'.formatBytes($row['filesize']).'</td>';
				echo '</tr>';
			}
		?>
		</table>
	</fieldset>	
	
	<fieldset>
		<legend>File Types</legend>
		<?php
		$query ="SELECT COUNT(`id`) as `numfiles`, sum(`size`) as `filesize`, `type`  FROM `uploadedfile` GROUP BY `type`";
		$result = $conn->prepare($query);
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);
		
		?>
		<table>
		<tr>
			<td>Type</td><td>Number of Uploads</td><td>Total Size</td>
		</tr>
		<?php
			foreach( $result as $row){
				echo '<tr>';
					echo '<td>'.$row['type'].'</td><td>'.$row['numfiles'].'</td><td>'.formatBytes($row['filesize']).'</td>';
				echo '</tr>';
			}
		?>
		</table>
	</fieldset>	
	
	<fieldset>
		<legend>Users</legend>
		<?php
		$query ="SELECT `username`, `enabled`, `lastlogin`  FROM `user` order by `username` ";
		$result = $conn->prepare($query);
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);
		
		?>
		<table>
		<tr>
			<td>Username</td><td>Enabled</td><td>Last Login</td>
		</tr>
		<?php
			foreach( $result as $row){
				echo '<tr>';
					echo '<td>'.$row['username'].'</td><td>'.$row['enabled'].'</td><td>'.$row['lastlogin'].'</td>';
				echo '</tr>';
			}
		?>
		</table>
	
	</fieldset>
	
	<fieldset>
		<legend>Other Info</legend>
		<?php
			$query ="SELECT `id`, `title` FROM `optiongroups` order by `title`";
			$result = $conn->prepare($query);
			$result->execute();
			$result = $result->fetchAll(PDO::FETCH_ASSOC);
		?>
		<table>
			<?php
				foreach( $result as $row){
					echo '<tr>';
						echo '<td>'.$row['title'].'</td><td>';
							$subQuery = "SELECT `option` FROM `options` WHERE `group_id` = ".$row['id']." order by `sort`";
							

							$subQuery = $conn->prepare($subQuery);
							$subQuery->execute();
							$subQueryResult = $subQuery->fetchAll(PDO::FETCH_ASSOC);
							foreach( $subQueryResult as $item){
								echo $item['option'].', ';
							}
						
						echo '</td>';
					echo '</tr>';
				}
			?>
		</table>
	
		
	</fieldset>
	
	
	<a href="/admin">Home</a>
	
	</div>
</body>
</html>	