<?php 
$path = "./uploads/*";
array_map('unlink', array_filter((array) glob($path)));
?>