<?php
include("../config/config.php");

error_reporting(E_PARSE | E_ERROR);

$query = $_POST['query'];
$userLoggedIn = $_POST['userLoggedIn'];
$table = $_POST['table'];
$box = $_POST['box'];


$usersReturnedQuery = mysqli_query($con, "SELECT * FROM $table WHERE name LIKE '%$query%' LIMIT 4");


if($query != ""){
			
	while($row = mysqli_fetch_array($usersReturnedQuery)) {
		$name = $row['name'];
		$name = preg_replace( "/\r|\n/", "", $name );
		$name = trim($name," ");
		$id = $row['id'];


	echo "<div class='popUpBoxResults' id='popUpBoxResults$id$userLoggedIn$table' onClick='javascript:changeValue$id$userLoggedIn$table()' 																							style='cursor:pointer'>

			".$name."
			
		</div>
		<hr class='noMargin'>
		";
		?>
		<script>
			function changeValue<?php echo $id; ?><?php echo $userLoggedIn; ?><?php echo $table; ?>() {
				var val = "<?php echo $name; ?>"
				document.getElementById("<?php echo $box; ?>").value = val;
			}
		</script>
		<?php
					
	}

}

?>