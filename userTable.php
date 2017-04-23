<?php
include_once('topWrapper.php');
?>

<div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        Users
                    </h1>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Access Level</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php

                                    # Connect to Local Server
                                    require( 'includes/connect_db_local.php' ) ;


                                    #Setting up Arrays
                                    $ids = array();
                                    $names = array();
                                    $emails = array();
                                    $accessLevels = array();

                                    #Setting up Query - All Phones
									$q = 'SELECT * FROM users';
									$r = mysqli_query($dbc, $q);

                                    while($row = mysqli_fetch_array($r, MYSQLI_NUM))
                                    {
                                        array_push($ids, $row[0]);
                                        array_push($names, $row[1]);
                                        array_push($emails, $row[2]);
                                        array_push($accessLevels, $row[3]);
                                    }

                                    for($counter = 0; $counter < count($ids); $counter++)
                                    {
                                        echo '<tr><td>' . $ids[$counter] . '</td><td>' . $names[$counter] . '</td><td>' . $emails[$counter] .'</td><td>' . $accessLevels[$counter] .'</td></tr>';
                                    }

                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

        <!-- jQuery -->
        <script src="js/jquery.js"></script>
        <script src="js/clicked.js"></script>
        <script src="js/dynamic.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>

</body>

</html>
