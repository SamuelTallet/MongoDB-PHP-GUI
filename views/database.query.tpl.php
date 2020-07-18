<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo MPG_APP_NAME . ' v' . MPG_APP_VERSION; ?></title>

    <link rel="stylesheet" href="static/css/font-awesome.min.css">
    <link rel="stylesheet" href="static/css/bootstrap.min.css">
    <link rel="stylesheet" href="static/css/codemirror.css">
    <link rel="stylesheet" href="static/css/codemirror-addon/show-hint.css">
    <link rel="stylesheet" href="static/css/jsonview.bundle.css">
    <link rel="stylesheet" href="static/css/mpg.css">

    <script src="static/js/codemirror.js"></script>
    <script src="static/js/codemirror-mode/javascript.js"></script>
    <script src="static/js/codemirror-addon/show-hint.js"></script>
    <script src="static/js/codemirror-addon/mpg-hint.js"></script>
    <script src="static/js/jsonview.bundle.mod.js"></script>
    <script src="static/js/mpg.database.query.js"></script>

</head>

<body>

    <nav class="navbar navbar-expand sticky-top navbar-dark bg-mongodb">

        <a class="navbar-brand" href="/"><?php echo MPG_APP_NAME; ?></a>

        <div class="navbar-nav">
            <a class="nav-item nav-link" href="/createDatabase">Create database</a>
            <a class="nav-item nav-link active" href="/queryDatabase">Query database</a>
            <a class="nav-item nav-link" href="/manageIndexes">Manage indexes</a>
        </div>

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

                    <div class="col-md-6">

                        <h2 class="float-left">Document</h2>

                        <h2 class="float-right">Filter</h2>

                        <textarea id="mpg-filter-or-doc-textarea"></textarea>

                        <button id="mpg-insert-one-button" class="btn btn-primary float-left">
                            Insert one
                        </button>

                        <button id="mpg-find-button" class="btn btn-success float-right">
                            Find 
                        </button>

                        <button id="mpg-delete-one-button" class="btn btn-danger float-right">
                            Delete one
                        </button>

                        <button id="mpg-count-button" class="btn btn-info float-right">
                            Count
                        </button>

                    </div>
                    
                    <div class="col-md-6">

                        <h2>Options</h2>
                        
                        <div class="form-group">

                            <div>
                                Limit <input id="mpg-limit-input" type="number" class="form-control" value="5" min="1">
                            </div>
                            
                            <div>
                                Sort
                                <select id="mpg-sort-select" class="form-control">
                                    <option value="">Please select a database and a collection.</option>
                                </select>
                            </div>
                            
                            <div>
                                Order
                                <select id="mpg-order-select" class="form-control">
                                    <option value="1">ASC</option>
                                    <option value="-1">DESC</option>
                                </select>
                            </div>

                        </div>

                    </div>
                    
                </div>
                
                <div class="row">

                    <div id="mpg-output-column" class="col-md-12">

                        <h2>Output</h2>
                        <code id="mpg-output-code"></code>

                    </div>
                    
                </div>
                
            </div>
            
        </div>
        
    </div>

</body>

</html>