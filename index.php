<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Directory listing of &lt;DIRECTORY&gt;</title>
    <link rel="shortcut icon" href="resources/images/icons/folder.png" />
    
    <link rel="stylesheet" type="text/css" href="resources/css/rebase.css" />
    <link rel="stylesheet" type="text/css" href="resources/css/directorylister.css" />
    <link rel="stylesheet" type="text/css" href="resources/css/colorbox.css" />
<body>

<?php include('resources/DirectoryLister.php'); $lister = new DirectoryLister(); ?>

<div id="contentWraper">
    
    <div id="header" class="clearfix">
        <span class="fileName">File</span>
        <span class="fileSize">Size</span>
        <span class="fileModTime">Last Modified</span>
    </div>
    
    <ul id="directoryListing">
    <?php $x = 1; foreach($lister->listDirectory() as $name => $fileInfo): ?>
        <li class="<?php echo $x %2 == 0 ? 'even' : 'odd'; ?>">
            <a href="<?php if($fileInfo['file_type'] == 'directory' || 'back') { echo '?dir=' . $fileInfo['file_path']; } else { echo $fileInfo['file_path']; } ?>" class="clearfix <?php echo $fileInfo['file_type']; ?>">
                <span class="fileName"><?php echo $name; ?></span>
                <span class="fileSize"><?php echo $fileInfo['file_size']; ?></span>
                <span class="fileModTime"><?php echo $fileInfo['mod_time']; ?></span>
            </a>
        </li>
    <?php $x++; endforeach; ?>
    </ul>

</div>

<div id="footer" class="clearfix">
    <div class="left"><a href="home">Home</a> &raquo; dir &raquo; another_dir</div>
    <div id="right"></div>
</div>

<pre>
    <?php print_r($lister->listDirectory()); ?>
</pre>

</body>
</html>
