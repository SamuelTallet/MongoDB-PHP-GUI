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

    <script src="<?php echo MPG_BASE_URL; ?>/static/js/mpg.js"></script>
    <script src="<?php echo MPG_BASE_URL; ?>/static/js/mpg.collection.import.js"></script>

</head>

<body>

    <?php require MPG_ABS_PATH . '/views/parts/menu.tpl.php'; ?>

    <div class="container-fluid">

        <div class="row">

            <div class="col-md-3">

                <div class="row">

                    <div class="col-md-12">

                        <?php require MPG_ABS_PATH . '/views/parts/databases.tpl.php'; ?>

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
                        
                        <div id="mpg-import-notice" class="alert alert-info" role="alert">Use form below to import documents. Max file size: <?php echo $maxFileSize; ?></div>
                        
                        <?php
                        if ( !empty($successMessage) ) :
                        ?>
                            <h2>Result</h2>
                            <div class="alert alert-success" role="alert">Success: <?php echo $successMessage; ?></div>
                        <?php
                        endif;
                        ?>

                        <?php
                        if ( !empty($errorMessage) ) :
                        ?>
                            <h2>Result</h2>
                            <div class="alert alert-danger" role="alert">Error: <?php echo $errorMessage; ?></div>
                        <?php
                        endif;
                        ?>

                        <form id="mpg-import-form" method="POST" enctype="multipart/form-data">

                            <input id="mpg-import-file" type="file" accept=".json" name="import" class="form-control-file d-inline align-middle">

                            <input type="hidden" name="database_name" value="">
                            <input type="hidden" name="collection_name" value="">

                            <button id="mpg-import-button" type="submit" class="btn btn-warning d-inline">
                                Import
                            </button>

                        </form>

                    </div>

                </div>
                
            </div>
            
        </div>
        
    </div>

</body>

</html>