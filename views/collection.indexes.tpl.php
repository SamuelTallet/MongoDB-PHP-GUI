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
    <script src="static/js/mpg.collection.indexes.js"></script>

</head>

<body>

    <nav class="navbar navbar-expand sticky-top navbar-dark bg-mongodb">

        <a id="mpg-app-name" class="navbar-brand" href="/"><?php echo MPG_APP_NAME; ?></a>

        <div class="navbar-nav">
            <a class="nav-item nav-link" href="/createDatabase">Create database</a>
            <a class="nav-item nav-link" href="/queryDatabase">Query database</a>
            <a class="nav-item nav-link active" href="/manageIndexes">Manage indexes</a>
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

                    <div class="col-md-12">

                        <div class="row">

                            <div class="col-md-12">

                                <h2>Fields</h2>

                                <ul id="mpg-indexable-fields-list">
                                    <li><i>Please select a database and a collection.</i></li>
                                </ul>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-12">

                                <span class="align-middle">Order</span>

                                <select id="mpg-index-order-select" class="form-control d-inline-block align-middle">
                                    <option value="1" selected>ASC</option>
                                    <option value="-1">DESC</option>
                                </select>

                                <span class="align-middle">Unique?</span>
                                
                                <select id="mpg-unique-index-select" class="form-control d-inline-block align-middle">
                                    <option value="true">Yes</option>
                                    <option value="false" selected>No</option>
                                </select>

                                <button id="mpg-create-index-button" class="btn btn-primary">
                                    Create index
                                </button>

                            </div>

                        </div>

                    </div>

                </div>
                
                <div class="row">

                    <div id="mpg-indexes-column" class="col-md-12 d-none">

                        <h2>Indexes</h2>

                        <table id="mpg-indexes-table" class="table table-bordered table-hover">

                            <thead class="thead-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Keys (Order)</th>
                                    <th>Operation</th>
                                </tr>
                            </thead>

                            <tbody>
                            </tbody>

                        </table>

                    </div>
                    
                </div>
                
            </div>
            
        </div>
        
    </div>

</body>

</html>