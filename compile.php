<?php
	$dir = "./comics/";
	if($d = opendir($dir))
	{
		while(FALSE !== ($f = readdir($d)))
		{
			if(filetype($dir.$f) == "file")
			{
				$array[] = $f;
			}
		}
	}
	sort($array,SORT_NUMERIC);
	$json = array();
	$json["amt"] = count($array);
	$json["comics"] = $array;
	file_put_contents("./comics.json",json_encode($json));