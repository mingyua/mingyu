<?php

	$sqldata = file_get_contents('./install/sql/sql.sql');
   // $img = '/*!3213213213131313*/212131';  
	$newImg =preg_replace('/\/\*.*?\*\/;/', '', $sqldata);
	$newImg =preg_replace('/DROP TABLE IF EXISTS `.*?`;/', '', $newImg);

	
	
    //$newImg = preg_replace("/\/\**/",' ',$img);
    print_r ($newImg);  
	
	//print_r($sql);die();
    
	
			?>