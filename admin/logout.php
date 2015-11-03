<?php
	session_start();
?>
<html>
<head>
	<title>Stats</title>
	<style>
		#file-upload{
			margin: 0 auto;
			max-width: 800px;
		}
	</style>
</head>
<body>
	<div id="file-upload">
		<?php	
			if(session_destroy()){
				echo '<h1>You have logged out successfully</h1>';
			}else{
				echo '<h1>Unable to logout</h1>';
			}
		?>
	</div>
</body>
</html>