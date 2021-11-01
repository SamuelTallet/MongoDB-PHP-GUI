<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo MPG_APP_NAME . ' v' . MPG_APP_VERSION; ?></title>

    <link rel="icon" href="<?php echo MPG_BASE_URL; ?>/assets/images/mpg-icon.svg">
    <link rel="mask-icon" href="<?php echo MPG_BASE_URL; ?>/assets/images/mpg-safari-icon.svg" color="#6eb825">
    <link rel="apple-touch-icon" href="<?php echo MPG_BASE_URL; ?>/assets/images/mpg-ios-icon.png">

    <link rel="stylesheet" href="<?php echo MPG_BASE_URL; ?>/assets/css/ubuntu-font.css">
    <link rel="stylesheet" href="<?php echo MPG_BASE_URL; ?>/assets/css/fontawesome-custom.css">
    <link rel="stylesheet" href="<?php echo MPG_BASE_URL; ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo MPG_BASE_URL; ?>/source/css/inner.css">

    <script src="<?php echo MPG_BASE_URL; ?>/assets/js/vis-network.min.js"></script>

    <script>
        var MPG_BASE_URL = '<?php echo MPG_BASE_URL; ?>';
    </script>
    
    <script src="<?php echo MPG_BASE_URL; ?>/source/js/_base.js"></script>
    <script src="<?php echo MPG_BASE_URL; ?>/source/js/visualizeDatabase.js"></script>

</head>

<body>

    <?php require MPG_ABS_PATH . '/views/parts/menu.view.php'; ?>

    <div class="container-fluid">

        <div id="vis-network-container"></div>
        
    </div>

</body>

</html>