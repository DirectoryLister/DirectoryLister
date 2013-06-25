<?php
// A script to generate unique download keys for the purpose of protecting downloadable goods

require ('dbconnect.php');

	// Get the filename given by directory linker
	$fileget = $_GET["file"];
	
	// Prevent downloading outside of directory listing bounds
	if (substr($fileget, 0, 1) == '/') {
		$file = substr($fileget, 1);
	} else {
		$file = $fileget;
	}

	if(empty($_SERVER['REQUEST_URI'])) {
    	$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
	}

	// Strip off query string so dirname() doesn't get confused
	$url = preg_replace('/\?.*$/', '', $_SERVER['REQUEST_URI']);
	$folderpath = 'http://'.$_SERVER['HTTP_HOST'].'/'.ltrim(dirname($url), '/');

	// Add some salt
        $s1 = md5('GetFilesToday134364529193sad5He%#ll##@@!oSa#ltmy12!@$@');
        $s2 = rand();
        $s3 = $s1.md5($s2.rand().$s1).$s1;
	// Generate the unique download key
	$key = $s1.$s3.uniqid(md5(rand())).$s2;

	// Get the activation time
	$time = date('U');

// Write the key and activation time to the database as a new row.
	$registerid = mysql_query("INSERT INTO downloadkey (uniqueid,timestamp,filename) 
                                   VALUES(\"$key\",\"$time\",\"$file\")") or die(mysql_error());

// Create the filename
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

?>

<DOCUTYPE html>
<head>
<title> Sick Files </title>
<script type="text/javascript">
                    window.setTimeout(function() {
                        location.href = 'index.php';
                    }, 10000);
</script>
</head>
<body>
<p>
<center>
<?php
$filename = basename($file);
$filemd5 = $file . ".md5";
$ext = pathinfo($filename, PATHINFO_EXTENSION);
echo "<a href=\"$data\">$filename</a>";
echo "<br><br>";
$query = sprintf("SELECT * FROM md5sums WHERE filename= '%s'",
mysql_real_escape_string($file));
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);
        if (!$row) {
                $md5 = md5_file($file);
                $sqlread = mysql_query("INSERT INTO md5sums (filename,md5) VALUES(\"$file\",\"$md5\")") or die(mysql_error());
                echo "MD5: " . $md5;
        }else{
                echo "MD5: " . $row['md5'];
}
echo "<br><br>";
echo "Redirecting in 10 seconds"; ?> </p>

<p>Click here if you are not redirected automatically in 10 seconds<br/>
            <a href="index.php">Get More Files</a>.
</p>
<?php
// Redirect to the download
echo '<META HTTP-EQUIV="Refresh" Content="2; URL=download.php?id=' . $key . '">';
//show HTML below for 5 seconds
?>
</body>
</html>
