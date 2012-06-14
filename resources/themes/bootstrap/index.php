<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Directory listing of <?php echo $lister->getListedPath(); ?></title>
    <link rel="shortcut icon" href="<?php echo THEMEPATH; ?>/images/folder.png" />
    
    <link rel="stylesheet" type="text/css" href="<?php echo THEMEPATH; ?>/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo THEMEPATH; ?>/style.css" />
    
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo THEMEPATH; ?>/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo THEMEPATH; ?>/directorylister.js"></script>
    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>

<body>

<div class="container">
    
    <div class="breadcrumb-wrapper">
        <ul class="breadcrumb">
            <?php $divider = FALSE; foreach($lister->listBreadcrumbs() as $breadcrumb): ?>
            <li>
                <?php if ($divider): ?>
                    <span class="divider">/</span>
                <?php endif; ?>
                <a href="<?php echo $breadcrumb['link']; ?>"><?php echo $breadcrumb['text']; ?></a>
            </li>
            <?php $divider = TRUE; endforeach; ?>
            <li class="floatRight" style="display: hidden;">
                <a href="#" id="pageTopLink">Top</a>
            </li>
        </ul>
    </div>
    
    <?php if($lister->getSystemMessages()): ?>
        <?php foreach ($lister->getSystemMessages() as $message): ?>
            <div class="alert alert-<?php echo $message['type']; ?>">
                <?php echo $message['text']; ?>
                <a class="close" data-dismiss="alert" href="#">&times;</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?> 
    
    <div id="header" class="clearfix">
        <span class="fileName">File</span>
        <span class="fileSize">Size</span>
        <span class="fileModTime">Last Modified</span>
    </div>
    
    <ul id="directoryListing">
    <?php $x = 1; foreach($lister->listDirectory() as $name => $fileInfo): ?>
        <li class="<?php echo $x %2 == 0 ? 'even' : 'odd'; ?>">
            <a href="<?php echo $fileInfo['file_path']; ?>" class="clearfix">
                <span class="fileName">
                    <i class="<?php echo $fileInfo['icon_class']; ?>">&nbsp;</i>
                    <?php echo $name; ?>
                </span>
                <span class="fileSize"><?php echo $fileInfo['file_size']; ?></span>
                <span class="fileModTime"><?php echo $fileInfo['mod_time']; ?></span>
            </a>
        </li>
    <?php $x++; endforeach; ?>
    </ul>

    <div class="footer">
        <p>Powered by, <a href="http://www.directorylister.com">Directory Lister</a></p>
    </div>
 
</div>


</body>
</html>
