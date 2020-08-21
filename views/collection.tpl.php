<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo MPG_APP_NAME . ' v' . MPG_APP_VERSION; ?></title>

    <link rel="stylesheet" href="<?php echo MPG_BASE_URL; ?>/static/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo MPG_BASE_URL; ?>/static/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo MPG_BASE_URL; ?>/static/css/mpg.css">

    <script>
        var MPG_BASE_URL = '<?php echo MPG_BASE_URL; ?>';
    </script>

    <script src="<?php echo MPG_BASE_URL; ?>/static/js/mpg.collection.js"></script>

</head>

<body>

    <nav class="navbar sticky-top navbar-dark bg-mongodb">

        <a class="navbar-brand" href="<?php echo MPG_BASE_URL; ?>/index"><?php echo MPG_APP_NAME; ?></a>

        <div class="navbar-nav">
            <a class="nav-item nav-link active" href="<?php echo MPG_BASE_URL; ?>/manageCollections">Manage collections</a>
            <a class="nav-item nav-link" href="<?php echo MPG_BASE_URL; ?>/importDocuments">Import documents</a>
            <a class="nav-item nav-link" href="<?php echo MPG_BASE_URL; ?>/visualizeDatabase">Visualize database</a>
            <a class="nav-item nav-link" href="<?php echo MPG_BASE_URL; ?>/queryDatabase">Query database</a>
            <a class="nav-item nav-link" href="<?php echo MPG_BASE_URL; ?>/manageIndexes">Manage indexes</a>
            <a class="nav-item nav-link" href="<?php echo MPG_BASE_URL; ?>/logout">Logout</a>
        </div>

        <button id="menu-toggle-button"><i class="fa fa-bars" aria-hidden="true"></i></button>

    </nav>

    <div class="container-fluid">

        <div class="row">

            <div class="col-md-3">

                <div class="row">

                    <div class="col-md-12">

                        <h2>Databases</h2>

                        <ul id="mpg-databases-list">
                            <?php foreach ($databaseNames as $databaseName) : ?>
                            <li>
                                <i class="fa fa-database" aria-hidden="true"></i>
                                <a class="mpg-database-link" data-database-name="<?php echo $databaseName; ?>" href="#<?php echo $databaseName; ?>">
                                    <?php echo $databaseName; ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>

                    </div>
                    
                </div>
                
                <div class="row">

                    <div class="col-md-12">

                        <h2>Collections</h2>

                        <ul id="mpg-collections-list">
                            <li><i>Please select a database.</i></li>
                        </ul>

                    </div>

                </div>
                
            </div>
            
            <div class="col-md-9">

                <div class="row">

                    <div class="col-md-12">
                    
                        <h2>Operations</h2>

                        <div class="alert alert-danger" role="alert">Be aware that drop a collection is irreversible.</div>

                        <button id="mpg-create-coll-button" class="btn btn-primary">Create coll.</button>

                        <button id="mpg-rename-coll-button" class="btn btn-warning">Rename coll.</button>

                        <button id="mpg-drop-coll-button" class="btn btn-danger">Drop coll.</button>

                    </div>

                </div>
                
            </div>
            
        </div>
        
    </div>

</body>

</html>