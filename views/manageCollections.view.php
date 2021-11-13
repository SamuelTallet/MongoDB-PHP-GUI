<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo MPG\APP_NAME . ' v' . MPG\VERSION; ?></title>

    <link rel="icon" href="<?php echo MPG\BASE_URL; ?>/assets/images/mpg-icon.svg">
    <link rel="mask-icon" href="<?php echo MPG\BASE_URL; ?>/assets/images/mpg-safari-icon.svg" color="#6eb825">
    <link rel="apple-touch-icon" href="<?php echo MPG\BASE_URL; ?>/assets/images/mpg-ios-icon.png">

    <link rel="stylesheet" href="<?php echo MPG\BASE_URL; ?>/assets/css/ubuntu-font.css">
    <link rel="stylesheet" href="<?php echo MPG\BASE_URL; ?>/assets/css/fontawesome-custom.css">
    <link rel="stylesheet" href="<?php echo MPG\BASE_URL; ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo MPG\BASE_URL; ?>/source/css/inner.css">

    <script>
        var MPG_BASE_URL = '<?php echo MPG\BASE_URL; ?>';
    </script>

    <script src="<?php echo MPG\BASE_URL; ?>/source/js/_base.js"></script>
    <script src="<?php echo MPG\BASE_URL; ?>/source/js/manageCollections.js"></script>

</head>

<body>

    <?php require MPG\ABS_PATH . '/views/parts/menu.view.php'; ?>

    <div class="container-fluid">

        <div class="row">

            <div class="col-md-3">

                <div class="row">

                    <div class="col-md-12">

                        <?php require MPG\ABS_PATH . '/views/parts/databases.view.php'; ?>

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

                        <button id="mpg-rename-coll-button" class="btn btn-secondary">Rename coll.</button>

                        <button id="mpg-drop-coll-button" class="btn btn-danger">Drop coll.</button>

                    </div>

                </div>
                
            </div>
            
        </div>
        
    </div>

</body>

</html>