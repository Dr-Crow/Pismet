<?php
include_once('topWrapper.php');
?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 align="center" class="page-header">
                            Pismet: A Raspberry Pi Network Analyzer
                        </h1>
                        <p>
                            Pismet is a netwrok analyzer that can run on a raspberry pi. Onced powerd up it will start collecting info about the wifi networks near by
							and the devices assocatied with them. While collecting data, Pismet will also parse the logs and insert them into a database where futher analysis
							can be done. Pismet is meant to be a robust soultion that can issue easily be deployed in seconds.
                        </p>
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

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="js/plugins/morris/raphael.min.js"></script>
    <script src="js/plugins/morris/morris.min.js"></script>
    <script src="js/plugins/morris/morris-data.js"></script>

</body>

</html>
