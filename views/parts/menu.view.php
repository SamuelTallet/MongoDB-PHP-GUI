<nav class="navbar sticky-top">

    <a class="navbar-brand" href="<?php echo MPG_BASE_URL; ?>/">
        <img class="navbar-brand-icon" src="<?php echo MPG_BASE_URL; ?>/assets/images/mpg-icon.svg" width="24" height="24" />
        <?php echo MPG\APP_NAME; ?>
    </a>

    <div class="navbar-nav">
        <a class="nav-item nav-link<?php echo ('manageCollections' === $viewName) ? ' active' : ''; ?>" data-canonical-url="<?php echo MPG_BASE_URL; ?>/manageCollections" href="<?php echo MPG_BASE_URL; ?>/manageCollections">Manage collections</a>
        <a class="nav-item nav-link<?php echo ('importDocuments' === $viewName) ? ' active' : ''; ?>" data-canonical-url="<?php echo MPG_BASE_URL; ?>/importDocuments" href="<?php echo MPG_BASE_URL; ?>/importDocuments">Import documents</a>
        <a class="nav-item nav-link<?php echo ('visualizeDatabase' === $viewName) ? ' active' : ''; ?>" data-canonical-url="<?php echo MPG_BASE_URL; ?>/visualizeDatabase" href="<?php echo MPG_BASE_URL; ?>/visualizeDatabase">Visualize database</a>
        <a class="nav-item nav-link<?php echo ('queryDatabase' === $viewName) ? ' active' : ''; ?>" data-canonical-url="<?php echo MPG_BASE_URL; ?>/queryDatabase" href="<?php echo MPG_BASE_URL; ?>/queryDatabase">Query database</a>
        <a class="nav-item nav-link<?php echo ('manageIndexes' === $viewName) ? ' active' : ''; ?>" data-canonical-url="<?php echo MPG_BASE_URL; ?>/manageIndexes" href="<?php echo MPG_BASE_URL; ?>/manageIndexes">Manage indexes</a>
        <a class="nav-item nav-link<?php echo ('manageUsers' === $viewName) ? ' active' : ''; ?>" data-canonical-url="<?php echo MPG_BASE_URL; ?>/manageUsers" href="<?php echo MPG_BASE_URL; ?>/manageUsers">Manage users</a>
        <a class="nav-item nav-link" href="<?php echo MPG_BASE_URL; ?>/logout">Logout</a>
    </div>

    <button id="menu-toggle-button"><i class="fa fa-bars" aria-hidden="true"></i></button>

</nav>