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
    <script src="static/js/jsonview.bundle.js"></script>
    <script src="static/js/mpg.js"></script>

</head>

<body>

<div class="container-fluid">

	<div class="row">

		<div class="col-md-3">

			<div class="row">

				<div class="col-md-12">

                    <h2>Databases</h2>

                    <ul id="mpg-databases-list">
                        <?php foreach ($databasesNames as $databaseName) : ?>
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

                    <button id="mpg-create-button" class="btn btn-primary">Create</button>

                </div>

            </div>
            
        </div>
        
		<div class="col-md-9">

			<div class="row">

				<div class="col-md-8 text-left">

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
                
				<div class="col-md-4">

                    <h2>Options</h2>
                    Limit <input id="mpg-limit-input" type="number" value="5" min="1">

                </div>
                
            </div>
            
			<div class="row">

				<div class="col-md-12 output-column">

                    <h2>Output</h2>
                    <code id="mpg-output-code"></code>

                </div>
                
            </div>
            
        </div>
        
    </div>
    
</div>

</body>
</html>