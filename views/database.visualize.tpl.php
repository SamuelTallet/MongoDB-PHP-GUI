<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo MPG_APP_NAME . ' v' . MPG_APP_VERSION; ?></title>

    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo MPG_BASE_URL; ?>/static/images/favicon-32x32.png">

    <link rel="stylesheet" href="<?php echo MPG_BASE_URL; ?>/static/css/ubuntu-font.css">
    <link rel="stylesheet" href="<?php echo MPG_BASE_URL; ?>/static/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo MPG_BASE_URL; ?>/static/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo MPG_BASE_URL; ?>/static/css/mpg.css">

    <script src="<?php echo MPG_BASE_URL; ?>/static/js/vis-network.min.js"></script>

    <script>
        var MPG_BASE_URL = '<?php echo MPG_BASE_URL; ?>';
    </script>
    
    <script src="<?php echo MPG_BASE_URL; ?>/static/js/mpg.js"></script>
    <script src="<?php echo MPG_BASE_URL; ?>/static/js/mpg.database.visualize.js"></script>

</head>

<body>

    <?php require MPG_ABS_PATH . '/views/parts/menu.tpl.php'; ?>

    <div class="container-fluid">

        <div id="vis-network-container"></div>
        
    </div>

</body>

</html>