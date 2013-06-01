<!DOCTYPE html>

<html>

    <head>

        <title>Directory listing of <?php echo $lister->getListedPath(); ?></title>
        <link rel="shortcut icon" href="<?php echo THEMEPATH; ?>/img/folder.png">

        <!-- STYLES -->
        <link rel="stylesheet" type="text/css" href="<?php echo THEMEPATH; ?>/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo THEMEPATH; ?>/css/bootstrap-responsive.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo THEMEPATH; ?>/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo THEMEPATH; ?>/css/style.css">

        <!-- SCRIPTS -->
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo THEMEPATH; ?>/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo THEMEPATH; ?>/js/directorylister.js"></script>

        <!-- FONTS -->
        <link rel="stylesheet" type="text/css"  href="http://fonts.googleapis.com/css?family=Cutive+Mono">

        <!-- META -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <?php file_exists('analytics.inc') ? include('analytics.inc') : false; ?>

    </head>

    <body>

        <div class="container">

            <?php $breadcrumbs = $lister->listBreadcrumbs(); ?>

            <div class="breadcrumb-wrapper">
                <ul class="breadcrumb">
                    <?php foreach($breadcrumbs as $breadcrumb): ?>
                        <?php if ($breadcrumb != end($breadcrumbs)): ?>
                            <li>
                                <a href="<?php echo $breadcrumb['link']; ?>"><?php echo $breadcrumb['text']; ?></a>
                                <span class="divider">/</span>
                            </li>
                        <?php else: ?>
                            <li class="active"><?php echo $breadcrumb['text']; ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <li id="page-top" class="pull-right" style="display: hidden;">
                        <a href="javascript:void(0)"><i class="icon-circle-arrow-up"></i></a>
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

            <ul class="directory-listing nav nav-pills nav-stacked">

                <li class="nav-header">
                    <span class="file-name">File</span>
                    <span class="pull-right">
                        <span class="file-size">Size</span>
                        <span class="file-modified">Last Modified</span>
                    </span>
                    <span class="file-info">
                </li>

                <?php foreach($dirArray as $name => $fileInfo): ?>
                    <li class="clearfix" data-name="<?php echo $name; ?>" data-href="<?php echo $fileInfo['file_path']; ?>">
                        <a href="<?php echo $fileInfo['file_path']; ?>" class="clearfix" data-name="<?php echo $name; ?>">

                            <span class="file-name">
                                <span class="icon-wrapper">
                                    <i class="<?php echo $fileInfo['icon_class']; ?>"></i>
                                </span>
                                <?php echo $name; ?>
                            </span>

                            <span class="pull-right">

                                <span class="file-size">
                                    <?php echo $fileInfo['file_size']; ?>
                                </span>

                                <span class="file-modified">
                                    <?php echo $fileInfo['mod_time']; ?>
                                </span>

                            </span>

                        </a>

                        <?php if (is_file($fileInfo['file_path'])): ?>
                            <a href="javascript:void(0)" class="file-info-button">
                                <i class="icon-info-sign"></i>
                            </a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>

            </ul>

            <hr>

            <div class="footer">
                <p>Powered by, <a href="http://www.directorylister.com">Directory Lister</a></p>
            </div>

        </div>

        <div id="file-info-modal" class="modal hide fade">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3>{{modal_header}}</h3>
            </div>

            <div class="modal-body">

                <dl id="file-info" >
                    <dt>MD5</dt>
                        <dd class="md5-hash">{{md5_sum}}</dd>

                    <dt>SHA1</dt>
                        <dd class="sha1-hash">{{sha1_sum}}</dd>

                    <dt>sha256</dt>
                        <dd class="sha256-hash">{{sha256_sum}}</dd>
                </dl>

            </div>
        </div>

    </body>

</html>
