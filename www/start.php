<?php
include ("./include/dbconn.php");

if ($_SESSION["admin_id"]=="") {?>	
	<script>
			location.href="/";
	</script>

<?}else{?>

		<?if ($_SESSION["admin_name"]!=""){?>
			<script>
				location.href="/main/main/index.html";
			</script>
		<?}?>

<?}?>