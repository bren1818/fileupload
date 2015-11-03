<?php
	session_start();
	include "config.inc.php";
	include "UserClass.php";
?>
<html>
<head>
	<title>User Login</title>
</head>
<body>
	<?php
	
	if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST'){
		
		
		//check if the user is even a viable user.
		$conn = getConnection();
		
		$username = trim($_POST['username']);
		
		if( $username != ""){
		
			$query = "SELECT Count(`id`) as `count`, `id`, `username`, `enabled`, `type`, `lastlogin`  FROM `user` where `username` = :username";
			$result = $conn->prepare($query);
			$result->bindParam(':username', $username);
			$result->execute();
			
			$user = $result->fetchAll(PDO::FETCH_ASSOC);
			
			if( $user[0]['count'] == 0){
				echo '<h3>No User `'.$username.'` was found</h3>';
				
				renderLoginForm();
			}else{
				//pa( $user );
				$login = new User($conn);
				$login = $login->load($user[0]['id']);
				if( $login->getEnabled()  == 1){
					//check authentication
					
					//session_start();
					$_SESSION = array();
					//session_destroy();
					
					//echo "Valid user, setting session";
					
					$_SESSION['username']= $username;
					$userCategory = "1"; //staff & Faculty category
					$password = trim($_POST['password']);
					
					$fields = array("username"=>$username,"password"=> $password,"type"=> $userCategory);
				
					
					//error_reporting(E_ALL);
				
					//error_log("Bren Debug - POST");
					
				
					$ch = curl_init();
					curl_setopt($ch,CURLOPT_URL, 'http://web.wlu.ca/its/birwin/super_login_script.php?username='.$username."&password=".$password."&type=1");
					curl_setopt($ch,CURLOPT_POST, true);
					//curl_setopt($ch,CURLOPT_POSTFIELDS, $fields );
					curl_setopt($ch, CURLOPT_TIMEOUT, '3');
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
					curl_setopt($ch,CURLOPT_HEADER,  false);  // don't return headers
					curl_setopt($ch,CURLOPT_FOLLOWLOCATION, true);   // follow redirects
					curl_setopt($ch,CURLOPT_MAXREDIRS, 10);     // stop after 10 redirects
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
						
					$result = curl_exec($ch);
					curl_close($ch);
					
					//error_log("Bren Debug - Fin Post");
					//error_log("Bren Debug - Result: ".$result);
					
					//Array ( [status] => 200 [data] => Array ( [user_password_combo_correct] => 1 [user_first_name] => Brendon [user_full_name] => Irwin [user_email] => birwin@wlu.ca [user_email2] => [user_title] => CMS Administrator [user_extension] => 4786 [user_username] => birwin [user_workforceid] => birwin2 [dn] => [id] => [sn] => [thirdpartyid] => [user_id] => 139010899 ) )
					
					//echo $json;
					
					if( $result != "" ){
					
					$decode = json_decode($result); 
					$decode = (array)($decode);
					
					//print_r( $decode );
					$status = $decode['status'];
					$data = (array)$decode['data'];
					
					//error_log( "Bren Debug Status: ".$status." with Data: ".print_r($data, true)." from result: ".$result );
					
					
					//stdClass Object ( [status] => 200 [data] => stdClass Object ( [user_password_combo_correct] => 1 [user_first_name] => Brendon [user_full_name] => Irwin [user_email] => birwin@wlu.ca [user_email2] => [user_title] => CMS Administrator [user_extension] => 4786 [user_username] => birwin [user_workforceid] => birwin2 [dn] => [id] => [sn] => [thirdpartyid] => [user_id] => 139010899 ) )
					
	
					if( $status == 200){
						//winner!
						$_SESSION['usertitle'] = $data["user_title"];
						$_SESSION['name'] = $data["user_first_name"].' '.$data["user_full_name"];
						$_SESSION['username'] = $username;
						
						$login->setLastlogin( date("Y-m-d H:i:s") );
						$login->save();
						ob_clean();
						header("location: /admin/index.php");
					}else{
						//failure
						ob_clean();
						header("location: /admin/login.php");
					}
					
					}else{
					//
					$_SESSION['usertitle'] = 'TEST'; //$data["user_title"];
					$_SESSION['name'] = 'TEST'; //$data["user_first_name"].' '.$data["user_full_name"];
					$_SESSION['username'] = $username;
					header("location: /admin/index.php");
				}
					
				}else{
					echo '<h3>User `'.$username.'` has been disabled</h3>';
				}
				
				
				
				
			}
			
		
		
		
		}else{
			echo '<h3>Please enter a username and password</h3>';
			
			renderLoginForm();
		}
	
	}else{
		
		renderLoginForm();
	}
	?>
</body>
</html>
<?php	

function renderLoginForm(){
	?>
		<fieldset>
			<legend>User Login</legend>
			<form method="post" action="login.php">
			<table>
				<tr>
					<td>Username: </td><td><input type="text" name="username" value="" placeholder="eg birwin" /></td>
				</tr>
				<tr>
					<td>Password: </td><td><input type="password" name="password" value="" /></td>
				</tr>
				<tr>
					<td>
						<input type="submit" name="submit" />
					</td>	
				</tr>
			</table>
			</form>
		</fieldset>
		<?php
	
}




function userAuthentication($user_category,$user_name,$user_password){
	#get date
	$date = date("F j, Y, g:i:s a");
	
	#get URL
	$url = $_SERVER['REQUEST_URI'];
	
	#get IP and HOST information
	$ip = $_SERVER['REMOTE_ADDR'];
	$_SERVER["SERVER_NAME"] =  exec('hostname -f');
	$host = $_SERVER["SERVER_NAME"];
	
	#exit if username and password has a string length of 0 
	if(strlen($user_name)==0) { exit; }
	if(strlen($user_password)==0) { exit; }
	
	if($user_category=="1") { $BASE_DN="OU=Employees,OU=UsersGroups,DC=ad,DC=wlu,DC=ca"; } #Staff
	if($user_category=="2") { $BASE_DN="OU=Students,OU=UsersGroups,DC=ad,DC=wlu,DC=ca"; } #Students
	
	$proxyUserDN='CN=svc_webldap,OU=SVCAccounts,OU=PrivilegedAccounts,DC=ad,DC=wlu,DC=ca';
	$proxyUserPW='TceQ2M5Nr9';

	#initialize return array 
	#user_password_combo_correct set to 0 (ie. username and password has not been validated yet, and thus set this to 0)
	$return = array("user_password_combo_correct"=>0,"user_first_name"=>"","user_full_name"=>"","user_email" => "","user_email2" => "","user_title" => "","user_extension" => "","user_username" => "","user_workforceid"=>"","dn" => "", "id" => "", "sn" => "", "thirdpartyid" => "");
	
	#Active Directory fields to retrieve
	$AD_FIELDS = array("employeenumber", "sn", "cn", "givenname", "mail", "workforceid","displayname","title","telephonenumber","displayName"); 
	
	# constants
	$LDAP_SERVER='auth.wlu.ca'; //Ldaps://auth.wlu.ca:636 
	$LDAP_HOST=gethostbyname($LDAP_SERVER);
	$LDAP_URL='ldaps://'.$LDAP_HOST;
	
	$ds=ldap_connect($LDAP_URL);  // assuming the LDAP server is on this host
	ldap_set_option($ds, LDAP_OPT_DEREF, LDAP_DEREF_NEVER); #aliases are never dereferenced 
	ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
	
	
	
	if ($ds)  
		{
		$dr=ldap_bind($ds, $proxyUserDN, $proxyUserPW);
	
		if($dr=="1") #if bind is successful
		{
			$sr = ldap_search($ds,$BASE_DN,"cn=$user_name",$AD_FIELDS);

			#log all failed binds
			if(ldap_count_entries($ds,$sr)==0) {
				//---START: write to text file
				$file_name = "username_error_results.txt"; 
				$input = array ($date,$user_name,$url,$ip,$host); 
				$output_line = implode ($input, "|")."\n"; 
				$output_stream = fopen($file_name, "a+");
				$result = fputs ($output_stream, $output_line);
				fclose($output_stream);
				//---END: write to text file
				}

			if (($sr)&&(ldap_count_entries($ds,$sr)==1)) 
				{
				$info=ldap_get_entries($ds, $sr);
				$dn=$info[0]['dn'];

				$countEm = ldap_count_entries($ds,$sr);

				define(LDAP_OPT_DIAGNOSTIC_MESSAGE, 0x0032);
				
				$user_password_combo_correct = ldap_bind($ds,$dn,$user_password);

				if($user_password_combo_correct) {
					if (ldap_get_option($ds, LDAP_OPT_DIAGNOSTIC_MESSAGE, $extended_error)) { $extended_error = $extended_error; } else { $extended_error = "NADA"; }
        				//echo "Error Binding to LDAP: $extended_error";
					
					
        		}


				if($user_password_combo_correct=="1")
					{
					#user_password_combo_correct set to 1 (ie. username and password has not been validated and if 1 from the LDAP bind above, set it to 1 indicating correct combo)
					$return['user_password_combo_correct']=1;
					
					#log users who have successfully signed in into the application
					//---START: write to text file
					$file_name = "successful_login_results.txt"; 
					$input = array ($date,$dn,$user_password_combo_correct,$url,$ip,$host,$extended_error); 
					$output_line = implode ($input, "|")."\n"; 
					$output_stream = fopen($file_name, "a+");
					$result = fputs ($output_stream, $output_line);
					fclose($output_stream);
					//---END: write to text file
					
					$info = ldap_get_entries($ds, $sr);
					for ($i=0; $i<$info["count"]; $i++) 
						{
						$return['user_first_name']=$info[0]['givenname'][0];
						$return['user_full_name']=$info[0]['sn'][0];
						$return['user_email']=$info[0]['mail'][0];
						$return['user_title']=$info[0]['title'][0];
						$return['user_extension']=$info[0]['telephonenumber'][0];
						$return['user_username']=$info[0]['cn'][0];
						$return['user_workforceid']=$info[0]['workforceid'][0];
						$return['user_id']=$info[0]['employeenumber'][0];
						#$return['thirdpartyid']=$thirdpartyid;
						#$return['samAccountName']=$samAccountName;
						#$return['user_email2']=$displayname;
						return $return;
						}
					}
				else	
					{
					#log all incorrect username and password combinations (invalid authentication credentials entered)
					//---START: write to text file
					$file_name = "bind_error_results.txt"; 
					$input = array ($date,$dn,$user_password_combo_correct,$url,$ip,$host,$extended_error); 
					$output_line = implode ($input, "|")."\n"; 
					$output_stream = fopen($file_name, "a+");
					$result = fputs ($output_stream, $output_line);
					fclose($output_stream);
					//---END: write to text file
					}
				}
				
		}else{
			echo "Unable to bind to LDAP server.";
		}          
		}
	
	ldap_close($ds);

} #end of function

?>