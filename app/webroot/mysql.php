<?php 
	if(!mysql_connect('localhost', 'vishal', 'p@ssw0rd')) {
		die('MySQL Server Error' . mysql_error());
	} else {
		echo "MySQL Server Connected!!!" . "<br />";
		if(!mysql_select_db('omni_app')) {
			die('Database Error' . mysql_error());
		} else {
			echo "Database Selected!!!" . "<br />";
		}
	}
?>