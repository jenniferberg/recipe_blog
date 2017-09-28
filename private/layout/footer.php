<br />
<br />
<div>Copyright <?php echo date('Y'); ?></div>
</body>
</html>
<?php
//If DB connection exists, close the connection
if(isset($connection)){
	mysqli_close($connection);
}
?>