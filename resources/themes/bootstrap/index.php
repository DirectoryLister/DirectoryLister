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
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo THEMEPATH; ?>/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo THEMEPATH; ?>/js/directorylister.js"></script>

        <!-- FONTS -->
        <link rel="stylesheet" type="text/css"  href="http://fonts.googleapis.com/css?family=Cutive+Mono">

        <!-- META -->
        <meta name="viewport" content="width=480, initial-scale=.8">
        <meta charset="utf-8">

        <?php file_exists('analytics.inc') ? include('analytics.inc') : false; ?>

    </head>

    <body>

        <div id="page-navbar" class="navbar navbar-default navbar-fixed-top">
            <div class="container">

                <?php $breadcrumbs = $lister->listBreadcrumbs(); ?>

                <p class="navbar-text">
                    <?php foreach($breadcrumbs as $breadcrumb): ?>
                        <?php if ($breadcrumb != end($breadcrumbs)): ?>
                                <a href="<?php echo $breadcrumb['link']; ?>"><?php echo $breadcrumb['text']; ?></a>
                                <span class="divider">/</span>
                        <?php else: ?>
                            <?php echo $breadcrumb['text']; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </p>

                <ul id="page-top-nav" class="nav navbar-nav navbar-right">
                    <li><a href="javascript:void(0)" id="page-top-link"><i class="icon-circle-arrow-up icon-large"></i></a></li>
                </ul>

            </div>
        </div>

        <div id="page-content" class="container">

            <?php if($lister->getSystemMessages()): ?>
                <?php foreach ($lister->getSystemMessages() as $message): ?>
                    <div class="alert alert-<?php echo $message['type']; ?>">
                        <?php echo $message['text']; ?>
                        <a class="close" data-dismiss="alert" href="#">&times;</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div id="directory-list-header">
                <div class="row">
                    <div class="col-md-8 col-sm-6 col-xs-5">File</div>
                    <div class="col-md-1 col-sm-2 col-xs-2 text-right">Size</div>
                    <div class="col-md-3 col-sm-4 col-xs-5 text-right">Last Modified</div>
                </div>
            </div>

            <ul id="directory-listing" class="nav nav-pills nav-stacked">

                <?php foreach($dirArray as $name => $fileInfo): ?>
                    <li data-name="<?php echo $name; ?>" data-href="<?php echo $fileInfo['file_path']; ?>">
                        <a href="<?php echo $fileInfo['file_path']; ?>" class="clearfix" data-name="<?php echo $name; ?>">


                            <div class="row">
                                <span class="file-name col-md-8 col-sm-6 col-xs-5">
                                    <span class="icon-wrapper">
                                        <i class="<?php echo $fileInfo['icon_class']; ?>"></i>
                                    </span>
                                    <?php echo $name; ?>
                                </span>

                                <span class="file-size col-md-1 col-sm-2 col-xs-2 text-right">
                                    <?php echo $fileInfo['file_size']; ?>
                                </span>

                                <span class="file-modified col-md-3 col-sm-4 col-xs-5 text-right">
                                    <?php echo $fileInfo['mod_time']; ?>
                                </span>
                            </div>

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
                Powered by, <a href="http://www.directorylister.com">Directory Lister</a>
            </div>

        </div>

        <div id="file-info-modal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">{{modal_header}}</h4>
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
            </div>
        </div>

    </body>

</html>
