<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo MPG_APP_NAME . ' v' . MPG_APP_VERSION; ?></title>

    <link rel="stylesheet" href="<?php echo MPG_BASE_URL; ?>/static/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo MPG_BASE_URL; ?>/static/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo MPG_BASE_URL; ?>/static/css/mpg.login.css">

</head>

<body>

    <div class="container h-100">

        <?php 
        if ( isset($errors) ) :
        ?>

        <div class="alert alert-danger text-center" role="alert">
            Please fill these fields: <?php echo join(', ', $errors); ?>
        </div>

        <?php
        endif;
        ?>

        <div class="d-flex justify-content-center h-100">

            <div class="card">

                <div class="card-header">
                    <h3 class="text-center"><?php echo MPG_APP_NAME; ?></h3>
                </div>
                
                <div class="card-body">

                    <form method="POST">

                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="User" name="user">
                        </div>

                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-key"></i></span>
                            </div>
                            <input type="password" class="form-control" placeholder="Password" name="password">
                        </div>

                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-server"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Host" name="host" required>
                        </div>

                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-filter"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Port" name="port" required>
                        </div>

                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-database"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Database" name="database">
                        </div>

                        <div class="form-group">
                            <input type="submit" name="login" value="Login" class="btn btn-primary float-right">
                        </div>

                    </form>
                    
                </div>
                
                <div class="card-footer d-none">
                </div>
                
            </div>
            
        </div>
        
    </div>

</body>
</html>