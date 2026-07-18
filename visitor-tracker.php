<?php

$ip = $_SERVER['REMOTE_ADDR'];

mysqli_query($conn,"
INSERT INTO site_visits(ip_address)
VALUES('$ip')
");
?>