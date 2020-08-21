<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo MPG_APP_NAME . ' v' . MPG_APP_VERSION; ?></title>

    <link rel="stylesheet" href="<?php echo MPG_BASE_URL; ?>/static/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo MPG_BASE_URL; ?>/static/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo MPG_BASE_URL; ?>/static/css/mpg.css">

    <script src="<?php echo MPG_BASE_URL; ?>/static/js/vis-network.min.js"></script>

    <script>
        var MPG_BASE_URL = '<?php echo MPG_BASE_URL; ?>';
    </script>
    
    <script src="<?php echo MPG_BASE_URL; ?>/static/js/mpg.database.visualize.js"></script>

</head>

<body>

    <nav class="navbar sticky-top navbar-dark bg-mongodb">

        <a class="navbar-brand" href="<?php echo MPG_BASE_URL; ?>/index"><?php echo MPG_APP_NAME; ?></a>

        <div class="navbar-nav">
            <a class="nav-item nav-link" href="<?php echo MPG_BASE_URL; ?>/manageCollections">Manage collections</a>
            <a class="nav-item nav-link" href="<?php echo MPG_BASE_URL; ?>/importDocuments">Import documents</a>
            <a class="nav-item nav-link active" href="<?php echo MPG_BASE_URL; ?>/visualizeDatabase">Visualize database</a>
            <a class="nav-item nav-link" href="<?php echo MPG_BASE_URL; ?>/queryDatabase">Query database</a>
            <a class="nav-item nav-link" href="<?php echo MPG_BASE_URL; ?>/manageIndexes">Manage indexes</a>
            <a class="nav-item nav-link" href="<?php echo MPG_BASE_URL; ?>/logout">Logout</a>
        </div>

        <button id="menu-toggle-button"><i class="fa fa-bars" aria-hidden="true"></i></button>

    </nav>

    <div class="container-fluid">

        <div id="vis-network-container"></div>
        
    </div>

</body>

</html>