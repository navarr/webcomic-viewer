<?php
	if(!file_exists("comics.json")) { require_once("compile.php"); }
	$json = file_get_contents("comics.json");
	$data = json_decode($json,TRUE);
	$curcomic = $data["amt"];
	if (!isset($_GET["id"])) { $id = $curcomic; }
	else
	{
		$id = intval($_GET["id"]);
		if ($id < 1 || $id > $curcomic) { $id = $curcomic; }
	}
	if($id > 1) { $prevID = $id - 1; } else { $prevID = $id; }
	if($id < $data["amt"]) { $nextID = $id + 1; } else { $nextID = $id; }
	if(isset($_GET["random"]))
		{ $id = rand(1,$data["amt"]); }
	$comfile = $data["comics"][$id-1];
	print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n");

	list($width, $height, $type, $attr) = getimagesize("./comics/".$comfile); 
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
	<head>
		<title>Navarr's Comics</title>
		<link rel="image_src" href="http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v43/17/86674182605/app_3_86674182605_1426.gif" />
		<script type="text/javascript">
			document.comicData = <?= $json ?>;
			document.comic = document.comicData.amt;
			document.curComic = <?= $id ?>;
			document.loadImg = new Image;
			document.loadImg.src = "img/load.gif";
			function frstComic()
			{
				document.curComic = 1;
				return setComic();
			}
			function nextComic()
			{
				if (document.curComic >= document.comic) { return false; }
				else
				{
					document.curComic++;
					return setComic();
				}
			}
			function clickNext()
			{
				if (!nextComic())
				{
					document.curComic = 1;
					return setComic();
				}
			}
			function randComic()
			{
				var com = document.curComic;
				while(document.curComic == com)
				{
					document.curComic = Math.floor(Math.random()*(document.comic))+1;
				}
				return setComic();
			}
			function prevComic()
			{
				if (document.curComic <= 1) { return false; }
				else
				{
					document.curComic = document.curComic - 1;
					return setComic();
				}
			}
			function lastComic()
			{
				document.curComic = document.comic;
				return setComic();
			}
			function setComic()
			{
				document.getElementById('comicImg').src = document.loadImg.src;
				var img = new Image;
				document.comicStr = document.comicData.comics[document.curComic - 1];
				document.location.hash = "#c=" + document.curComic;
				document.getElementById('comicNum').innerHTML = document.curComic;

				img.onload = function()
				{
					document.getElementById('comicHolder').style.width = img.width;
					document.getElementById('comicHolder').style.height = img.height;
					document.getElementById('comicImg').src = img.src;
					var prevComic,nextComic,pixComic;
					if (document.curComic == document.comic)
					{
						nextComic = document.curComic;
						pixComic = 1;
					}
					else
						{ nextComic = document.curComic + 1;pixComic = nextComic; }
	
					if (document.curComic == 1)
					{
						prevComic = 1;
					}
					else
						{ prevComic = document.curComic - 1; }
	
					document.getElementById("nextText").href = "./?id=" + nextComic;
					document.getElementById("prevText").href = "./?id=" + prevComic;
					document.getElementById("pixLink").href = "./?id=" + pixComic;
				}
				img.src = '/comics/' + document.comicStr;
				return true;
			}
			function checkHash()
			{
				var hash = document.location.hash.split("#")[1];
				if(hash)
				{
					var newid = hash.split("c=")[1];
					if(newid > document.comicData.amt) { return false; }
					if(newid < 1) { return false; }
					
					if(newid != document.curComic)
					{
						document.curComic = newid;
						setComic();
					}
					
				}
			}
			function loadComic()
			{
				checkHash();
				setInterval(function(){ checkHash(); },100)
				setComic();
			}
		</script>
		<style type="text/css">
			a img { border: 0px; outline: 0px; }
			a.nav,a.noNav { color: #000000; padding: 5px 1em 5px 1em; text-decoration:none; background-color: #FFFFFF;}
			a.nav:hover { background-color: #DDDDDD; }
		</style>
	</head>
	<body style="text-align:center;" onload="loadComic();(new Image).src='./compile.php';">
		<h1 style="display:inline;padding:5px 0 5px 0;margin:0;">Navarr's Comics</h1>
		<br />
		<a href="./?id=1" onclick="frstComic();return false;" id="frstText" class="nav" title="First Comic">First</a>
		<a href="./?id=<?= $prevID ?>" onclick="prevComic();return false;" id="prevText" class="nav" title="Previous Comic">Previous</a>
		<a href="./?random" onclick="randComic();return false;" id="randText" class="nav" title="Random Comic">Random</a>
		<a href="./?id=<?= $nextID ?>" onclick="nextComic();return false;" id="nextText" class="nav" title="Next Comic">Next</a>
		<a href="./?id=<?= $curcomic ?>" onclick="lastComic();return false;" id="lastText" class="nav" title="Latest Comic">Last</a>
		<br />
		<div style="margin:5px auto;text-align:center;width:<?= $width ?>px;height:<?= $height ?>px;border:1px solid black;" id="comicHolder"><a href="<?php print($pixLink); ?>" onclick="clickNext();return false;" id="pixLink"><img id="comicImg" src="/comics/<?= $comfile ?>" /></a></div>
		<br />
		<span style="font-size:32pt;font-weight:bold;"><span id="comicNum"><?php print($id); ?></span> / <?php print($curcomic); ?></span>
	</body>
</html>
