<html>
<head>
</head>
<body>
<form id='login' action='userauth.php' method='post'>
	<label for='user' >UserName*:</label>
	<input type='text' name='user' id='user'  maxlength="50" />
	 
	<label for='pxwd' >Password*:</label>
	<input type='password' name='pxwd' id='pxwd' maxlength="50" />
	 
	<input type='submit' name='Submit' value='Submit' />
</form>

<?php
//exec("/bin/ls",$output);
//var_dump($output);
//exec("whoami",$output);
exec("./matchusers.exe",$output,$exit);
//foreach( $output as $out ){
//	echo "$out <br/>";
//}
// $hashed = crypt('abc');
// echo $hashed;
 
 // include_once("classes.php");
 // $db = new Database();
 // $result = $db->queryArray("select * from users;");
 // var_dump($result);
  // $result = $db->queryTable("select * from users;");
 // var_dump($result);
 
// $me = new User( 'bm1549', 'babe' );
// $you = new User( 'ben7293', 'baby' );
// //echo "getinfo";
// //$me->getInfo();
// echo "sendmessage";
// $me->sendMessage( $you,'hello' );
// $me->sendMessage( $you,'how are you?' );
// $you->sendMessage($me, 'im good');
// echo 'getmessages';
// $me->getMessages( $you );

?>

</body>
</html>
