<?php
require ('dbconnect.php');

$maxdownloads = "2";
$maxtime = "7200";

function readfile_chunked ($fname) {
	$chunksize = 1*(1024*1024);
	$buffer = '';
	$handle = fopen($fname, 'rb');
	if ($handle === false) {
		return false;
	}
	while (!feof($handle)) {
		$buffer = fread($handle, $chunksize);
		print $buffer;
	}
	return fclose($handle);
}

if(get_magic_quotes_gpc()) {
	$id = stripslashes($_GET['id']);
}else{
	$id = $_GET['id'];
}

//  the filename, key, timestamp, and number of downloads from the database

$query = sprintf("SELECT * FROM downloadkey WHERE uniqueid= '%s'",
mysql_real_escape_string($id, $link));
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);

if (!$row) {
	echo "The download key you are using is invalid.";
}else{
	$fname = $row['filename'];
	$timecheck = date('U') - $row['timestamp'];
		if ($timecheck >= $maxtime) {
			echo "This key has expired (exceeded time allotted).<br/>";
		}else{
			$downloads = $row['downloads'];
			$downloads += 1;
			if ($downloads > $maxdownloads) {
			echo "This key has expired (exceeded allowed downloads).<br />";
				}else{
					$sql = sprintf("UPDATE downloadkey SET downloads = '".$downloads."' WHERE uniqueid= 
'%s'",
					mysql_real_escape_string($id, $link));
					$incrementdownloads = mysql_query($sql) or die(mysql_error());
					$path = getcwd();
					//Check for various invalid files, and loop holes like ../ and ./
						if($fname == '.' || $fname == './' || $fname == "download.php" || $fname == 
"index.php" || !file_exists($fname) || empty($fname) || preg_match('/\..\|\.\|\.|resources/',$fname))
						{
							echo "Invalid File or File Not Specified";
							exit(0);
				}

			// Declare arrays for filename and count
			$name = array();
			$count = array();
			// Create log file if it does not exist
			touch("$path/resources/log");
			// Open log file in read mode
			$file = fopen("$path/resources/log","r");
			// Read the entire contents of the file into the arrays
			while ($data = fscanf($file,"%[ -~]\t%d\n"))
			{
				list ($temp1, $temp2) = $data;
				array_push($name,$temp1);
				array_push($count,$temp2);
			}
			fclose($file);
			// If the file entry exists in the log, increment its count
			if(in_array($fname,$name))
			{
				$key=array_search($fname,$name);
				$count[$key]+=1;
			}
			// Otherwise, create a new array entry for the file
			else
			{
				array_push($name,$fname);
				array_push($count,1);
			}

			// Combine the two arrays into new array with filename as key, and count as value
			$list=array_combine($name,$count);
			ksort($list);
			// Open the file in write mode to clear all its contents
			$file=fopen("$path/resources/log","w");

			// For each key and value in the list, print to file
			while (list($key, $val) = each($list))
			{
				fprintf($file,"%s\t%d\n",$key,$val);
			}
			fclose($file);

			// Initiate force file download
			// fix for IE catching or PHP bug issue
			@header("Pragma: public");
			@header("Expires: 0"); // set expiration time
			@header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			// browser must download file from server instead of cache
			// force download dialog
			@header("Content-Type: application/force-download");
			@header("Content-Type: application/octet-stream");
			@header("Content-Type: application/download");

			// use the Content-Disposition header to supply a recommended filename and
			// force the browser to display the save dialog.

			@header("Content-Disposition: attachment; filename=\"".basename($fname)."\"" );

			/*
			The Content-transfer-encoding header should be binary, since the file will be read
			directly from the disk and the raw bytes passed to the downloading computer.
			The Content-length header is useful to set for downloads. The browser will be able to
			show a progress meter as a file downloads. The content-lenght can be determines by
			Filesize function returns the size of a file.
			*/
			@header("Content-Transfer-Encoding: binary");
			@header("Content-Length: ".filesize($fname));
			@readfile_chunked($fname);
			exit(0);
		}
	}
}
?>
