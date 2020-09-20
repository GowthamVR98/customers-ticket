<?php
include_once 'auth.php';
include 'includes/admin-config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <?php include (ALUM_TEMPLATES.'metatag.php');?>
</head>

<body class="theme-red">
    <?php
        // <!-- Page Loader -->
        include ALUM_TEMPLATES.'loader.php';
        // <!-- #END# Page Loader -->
        // <!-- Top Bar -->
            include ALUM_TEMPLATES.'top-navigation.php';
        // <!-- #Top Bar -->
        // <!-- Left Sidebar -->
            include ALUM_TEMPLATES.'left-links.php';
        // <!-- #Left Sidebar -->
    ?>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>DASHBOARD</h2>
            </div>

            <!-- Widgets -->
            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-pink hover-expand-effect">
                        <a href="manage-ticket">
                            <div class="icon">
                                <i class="material-icons">build</i>
                            </div>
                        </a>
                        <div class="content">
                            <div class="text">MANAGE TICKETS</div>
                            <div class="number count-to" data-from="0" data-to="125" data-speed="15" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-cyan hover-expand-effect">
                        <a href="add-ticket">
                            <div class="icon">
                                <i class="material-icons">add</i>
                            </div>
                        </a>
                        <div class="content">
                            <div class="text">ADD TICKET</div>
                            <div class="number count-to" data-from="0" data-to="257" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </section>
<?php include ALUM_TEMPLATES.'footer.php';?>
<!-- <script src="js/pages/index.js"></script> -->

</body>

</html>
