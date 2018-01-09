<?php require('loginregister/includes/config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="author" content="Wink Hosting (www.winkhosting.com)" />
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link rel="stylesheet" href="../images/style.css" type="text/css" />
    <link rel="stylesheet" href="../css/style.css" type="text/css" />
    <link rel="stylesheet" href="../css/jquery.dataTables.min.css" type="text/css" />
    <script src="../javascript/jquery-3.2.1.min.js"></script>
    <script src="../javascript/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.4.0/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
    <script type="text/javascript" language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/buttons/1.4.0/js/buttons.html5.min.js"></script>
    <script src="../javascript/custom.js"></script>
    <script src="http://maps.google.com/maps/api/js?key=AIzaSyCfZEA8H1O8FK_R48Y71a_7pXrPwXRqwRg" type="text/javascript"></script>
    <title>IsoMemo.com</title>
</head>
<body>
<?php include("getTable.php"); ?>
<header>
    <?php if(!$user->is_logged_in()): ?>
        <a href="loginregister/register.php">Register</a>
    <?php endif; ?>
    <?php if(!$user->is_logged_in()): ?>
        <a href="loginregister/login.php">Login</a>
    <?php endif; ?>
    <?php if($user->is_logged_in()): ?>
        <a href="loginregister/logout.php">Logout</a>
    <?php endif; ?>
    <?php if($user->is_logged_in()): ?>
        <a href="loginregister/edit.php">Edit</a>
    <?php endif; ?>
</header>
<div id="content">
    <div class="left">
        <div id="map">
        </div>
        <script type="text/javascript">
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 10,
                center: new google.maps.LatLng(38.736946, -9.142685),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
        </script>
        <div class="min-range">
            <input type="button" name="range" id="range" value="Filter">
            <span>UL</span>
            <label for="min-lat">Lat</label>
            <input type="text" name="min-lat" id="min-lat">
            <label for="min-long">Long</label>
            <input type="text" name="min-long" id="min-long">
        </div>
        <div class="max-range">
            <input type="button" name="coordinate" id="coordinate" value="Get">
            <span>LR</span>
            <label for="max-lat">Lat</label>
            <input type="text" name="max-lat" id="max-lat">
            <label for="max-long">Long</label>
            <input type="text" name="max-long" id="max-long">
        </div>
    </div>
    <div class="right">
        <div class="user-query grid">
            <h4>User:</h4>
            <div class="second-row">
                <div class="query-list"></div>
                <div class="load-new">
                    <button type="submit" id="load">Load</button>
                    <button type="submit" id="new">New</button>
                </div>
            </div>
        </div>
        <div class="table-info grid">
            <h4>Tables</h4>
            <div class="second-row">
                <?php foreach ($tableName as $Name): ?>
                    <div class="table-list">
                        <span class="table-name"><?php echo $Name; ?></span>
                        <input type="checkbox" name="<?php echo $Name; ?>" value="<?php echo $Name; ?>">
                        <span class="sprite-description sprite" title="description"></span>
                        <span class="sprite-down sprite" title="down" data-name="<?php echo $Name; ?>"></span>
                        <span class="sprite-up sprite" title="up" data-name="<?php echo $Name; ?>"></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="subtable-info grid">
            <div class="first-row">
                <h4>Categories for:</h4>
                <div class="all-none">
                    <span class="all">All</span>
                    <span class="none">None</span>
                </div>
            </div>
            <div class="second-row"></div>
        </div>
        <div class="field-info grid">
            <h4>Fields for:</h4>
            <div class="all-none">
                <span class="all">All</span>
                <span class="none">None</span>
            </div>
        </div>
    </div>
</div>
<div id="content-info" class="content-info">
    <div class="top-nav">
        <a id="list-search" class="list-search">Search</a>
        <a id="list-Comment" class="list-Comment">Comment</a>
        <?php if($user->is_logged_in()): ?>
        <a id="list-Edit" class="list-Edit">Edit</a>
        <?php endif; ?>
        <?php if($user->is_logged_in()): ?>
        <a id="list-download" class="list-download">Download</a>
        <?php endif; ?>
    </div>
    <div class="content-list">
        <table id="search-results">
            <tfoot></tfoot>
            <thead>
                <tr class="list-title">
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
</body>
</html>