<?php
	require_once('session.php');
	session_start();

	$conn = new Database();

        $me = $_SESSION['user']->getName();
        if( !$_SESSION['user']->isLoggedIn() ){
                header('location: index.php');
        }


	$result = '';
	$game = '';
	$console = '';
	if( isset($_GET['game']) && trim($_GET['game']) && isset($_GET['console']) && trim($_GET['console']) ){
		$game = pg_escape_string($_GET['game']);	
		$console = pg_escape_string($_GET['console']);
		$result = $conn->queryTable("select * from getQuestion('$game','$console');");
	}

	$i = 1;
	while( isset($_POST["qid$i"]) && trim($_POST["qid$i"]) && isset($_POST["ansSelf$i"]) && trim($_POST["ansSelf$i"]) ){
		$qid = pg_escape_string($_POST["qid$i"]);
		$ansSelf = pg_escape_string($_POST["ansSelf$i"]);
		$imp = pg_escape_string($_POST["imp$i"]);

		$ansOthTmp = "";
		
		for( $j=1; $j <=5; $j++ ){
			if( isset($_POST["ans$j"."Oth$i"]) ){
				$ansOthTmp .= "1";
			}
			else{
				$ansOthTmp .= "0";
			}
		}

		$ansOth = pg_escape_string($ansOthTmp);
		if( !$conn->queryTrueFalse( "select answerQuestion('$me',$qid,$ansSelf,'$ansOth',$imp );" )){
			echo "Error posting answer to one of your questions, please contact Benson";
		}
		$i++;
	}
?>

<!DOCTYPE>
<html>
<head>
	<meta charset="utf-8"> 
    <title>eHarbinger</title>
    <link rel="stylesheet" type="text/css" href="css/meg.css">
</head>
<body>
<section id = 'banner' style='max-height: 250;'>
	<section>
		<h2>Profile Information</h2>
		<br>
	</section>
	<div class = 'inner split'>
		<section>
			<?php
			if( $game == 'General' && $console == 'General' ){
				echo '<p font = "Arial" font-size = "7" style="color:#ffd9b3;">You are answering general gaming questions</p>';
			} else{
				echo '<p font = "Arial" font-size = "7" style="color:#ffd9b3;">You are answering questions About '.$game.' for '.$console.'</p>';
			}
			echo '<p font = "Arial" font-size = "7"style="color:#ffd9b3;">Please answer honestly for accurate matches</p>';
			?>
		</section>
		<section>
			<p font = "Arial" font-size = "7" style="color:#ffd9b3;"> Choose the response</p>
<form action="userauth.php" method="post">
                                        <button> Log Out </button>
                                        <input type="hidden" name="type" value="logout">
                                </form>
		</section>
	</div>
</section>

<?php
	if( $result ){
	?>
		<form method='post' action='question.php'>
	<?php
		$label = 0;
		$i = 1;
		foreach( $result as $row ){
			$id = $row['questionid'];
			$text = $row['questiontext'];
			$ans1 = $row['answer1'];
			$ans2 = $row['answer2'];
			$ans3 = $row['answer3'];
			$ans4 = $row['answer4'];
			$ans5 = $row['answer5'];
			?>
			<section class = 'wrapper'>
				<div class = 'inner split'>
					<section>
					<h2 style="color:#cc0052;">Question <?php echo $i; ?></h2>
					<h2 style="color:#3333cc;"><?php echo $text; ?></h2>
					</section>
					<section>
					<?php
						echo "<input type='hidden' name='qid$i' value='$id'>";
						if( $ans1 ){ echo "<input type='radio' name='ansSelf$i' value='1' id='".++$label."' checked><label for='$label'>$ans1</label><br/>"; }
						if( $ans2 ){ echo "<input type='radio' name='ansSelf$i' value='2' id='".++$label."'><label for='$label'>$ans2</label><br/>"; }
						if( $ans3 ){ echo "<input type='radio' name='ansSelf$i' value='3' id='".++$label."'><label for='$label'>$ans3</label><br/>"; }
						if( $ans4 ){ echo "<input type='radio' name='ansSelf$i' value='4' id='".++$label."'><label for='$label'>$ans4</label><br/>"; }
						if( $ans5 ){ echo "<input type='radio' name='ansSelf$i' value='5' id='".++$label."'><label for='$label'>$ans5</label><br/>"; }
					
					?>
					</section>
				</div>
				<div class = 'inner split'>
					<section>
						<h2 style="color:#3333cc;">What response would you accept from others?</h2>
					</section>
					<section>
					<?php
						if( $ans1 ){ echo "<input type='checkbox' name='ans1Oth$i' value='$ans1' id='".++$label."'><label for='$label'>$ans1</label><br/>"; }
						if( $ans2 ){ echo "<input type='checkbox' name='ans2Oth$i' value='$ans2' id='".++$label."'><label for='$label'>$ans2</label><br/>"; }
						if( $ans3 ){ echo "<input type='checkbox' name='ans3Oth$i' value='$ans3' id='".++$label."'><label for='$label'>$ans3</label><br/>"; }
						if( $ans4 ){ echo "<input type='checkbox' name='ans4Oth$i' value='$ans4' id='".++$label."'><label for='$label'>$ans4</label><br/>"; }
						if( $ans5 ){ echo "<input type='checkbox' name='ans5Oth$i' value='$ans5' id='".++$label."'><label for='$label'>$ans5</label><br/>"; }

					?>
					</section>
				</div>
				<div class = 'inner split'>
					<section>
						<h2 style="color:#3333cc;"> How important would you rate this in a potential match with the following scale? </h2>
						<p padding-left = "10px;"> 1: Can't go without it </p>
						<p padding-left = "10px;"> 2: Somewhat important </p>
						<p padding = "0px;"> 3: Neutral </p>
						<p padding = "0px;"> 4: Barely Important </p>
						<p padding = "0px;"> 5: Not Important </p>			
					</section>
					<section>
						<?php echo "<select name='imp$i'><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option></select>"; ?>
					</section>
				</div>
			</section>
		<?php
			$i++;
			}
		?>
		<input type = 'submit' style="font-face: 'Comic Sans MS'; margin-left: 45%; font-size: larger; color: teal; background-color: #FFFFC0; border: 3pt ridge lightgrey" value = 'Finish!'>
		</form>
<?php
	} else{
		echo "<h3>Answer <a href='question.php?game=General&console=General'>General Questions</a></h3>";
		$games = $conn->queryTable("select * from getGameQuestions('$me');");
		foreach( $games as $row ){
			$game = $row['gamename'];
			$console = $row['gameconsole'];
			echo "<h3>Answer questions about <a href='question.php?game=$game&console=$console'>$game For $console</a></h3>";
		}
		echo "<h3><a href='players.php'>Go to matches</a></h3>";

	}
?>
</body>
<footer><?php include('footer.html');?></footer>
</html>

