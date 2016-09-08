<!DOCTYPE html>

<html>

    <head>

        <title>Directory listing of <?php echo $lister->getListedPath(); ?></title>
        <link rel="shortcut icon" href="<?php echo THEMEPATH; ?>/img/folder.png">

        <!-- STYLES -->
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo THEMEPATH; ?>/css/style.css">
        <link rel="stylesheet" type="text/css" href="<?php echo THEMEPATH; ?>/css/prism.css">

        <!-- SCRIPTS -->
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo THEMEPATH; ?>/js/directorylister.js"></script>

        <!-- FONTS -->
        <link rel="stylesheet" type="text/css"  href="//fonts.googleapis.com/css?family=Cutive+Mono">

        <!-- META -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">

        <?php file_exists('analytics.inc') ? include('analytics.inc') : false; ?>

    </head>

    <body>

        <div id="page-navbar" class="navbar navbar-default navbar-fixed-top">
            <div class="container">

                <?php $breadcrumbs = $lister->listBreadcrumbs($fileArray['dir']); ?>

                <p class="navbar-text">
                    <?php foreach($breadcrumbs as $breadcrumb): ?>
                        <?php if ($breadcrumb != end($breadcrumbs)): ?>
                                <a href="<?php echo $breadcrumb['link']; ?>"><?php echo $breadcrumb['text']; ?></a>
                                <span class="divider">/</span>
                        <?php else: ?>
                            <?php echo $breadcrumb['text']; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php echo $fileArray['name'] ?>
                </p>

                <div class="navbar-right">

                    <ul id="page-top-nav" class="nav navbar-nav">
                        <li>
                            <a href="javascript:void(0)" id="page-top-link">
                                <i class="fa fa-arrow-circle-up fa-lg"></i>
                            </a>
                        </li>
                    </ul>

                </div>

            </div>
        </div>

        <div id="page-content" class="container">

            <?php file_exists('header.php') ? include('header.php') : include($lister->getThemePath(true) . "/default_header.php"); ?>

            <?php if($lister->getSystemMessages()): ?>
                <?php foreach ($lister->getSystemMessages() as $message): ?>
                    <div class="alert alert-<?php echo $message['type']; ?>">
                        <?php echo $message['text']; ?>
                        <a class="close" data-dismiss="alert" href="#">&times;</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>

            <h1><?php echo $fileArray['name'] ?></h1>
            <ul class="lists">
                <li class="list-item">Path : <?php echo $fileArray['path'] ?></li>
                <li class="list-item">Size : <?php echo $fileArray['size'] ?></li>
                <li class="list-item">Extention : <?php echo $fileArray['ext'] ?></li>
                <li class="list-item">Time : <?php echo $fileArray['time'] ?></li>
            </ul>
            <hr/>
            <pre><code class="language-<?php echo $fileArray['lang']?>"><?php echo $fileArray['source']?></code></pre>

          <?php endif; ?>
        </div>

        <?php file_exists('footer.php') ? include('footer.php') : include($lister->getThemePath(true) . "/default_footer.php"); ?>
        <script type="text/javascript" src="<?php echo THEMEPATH; ?>/js/prism.js"></script>
    </body>

</html>
