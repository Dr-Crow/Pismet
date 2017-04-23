<?php
include_once('topWrapper.php');
?>

    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="navbar navbar-inverse">
                        <div class="container">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <a class="navbar-brand">Show:</a>
                            </div>
                            <div class="navbar-collapse collapse">
                                <ul class="nav navbar-nav">
                                    <li  id="all_phones"><a href="#">All Networks</a>
                                    </li>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Channel<b class="caret"></b></a>
                                        <ul class="dropdown-menu" id="list1">
                                            <?php
                                            #seletect unique row from phones
                                            # Connect to Local Server
                                            require( 'includes/connect_db_local.php' ) ;


                                            #Setting up Querry - All Phones
                                                $q = 'select DISTINCT(network_channels.channel) from networks inner join network_channels on networks.mac = network_channels.mac inner join network_freqs on networks.mac = network_freqs.mac inner join network_encryptions on networks.mac = network_encryptions.mac inner join network_snr on networks.mac = network_snr.mac order by channel ASC';
                                                $r = mysqli_query($dbc, $q);

                                                $test = array();
                                                while($row = mysqli_fetch_array($r, MYSQLI_NUM))
                                                {
                                                    echo '<li id="' . str_replace(" ","_",$row[0]) . '"><a href="#"> ' . $row[0] . '</a></li>';
                                                }

                                            ?>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Mac Address</th>
                                <th>SSID</th>
                                <th>Discovery Type</th>
                                <th>Info</th>
                                <th>Dev-Name</th>
                                <th>Network Type</th>
                                <th>Channel</th>
								<th>Frequency</th>
                                <th>Encryption</th>
                                <th>Packets</th>
								<th>Last Signal dbm</th>
                                <th>Max Signal dbm</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            # Connect to Local Server
                            require( 'includes/connect_db_local.php' ) ;


                            #Setting up Arrays
                            $macs = array();
                            $essid = array();
                            $discovery_type = array();
                            $info = array();
                            $dev_name = array();
                            $network_type = array();
							$channel = array();
                            $freq = array();
                            $encryption = array();
                            $packets = array();
                            $lastSig = array();
                            $maxSig = array();
							
                            #Setting up Querry - All Phones
                            $q = 'select networks.mac, networks.essid, networks.discovery_type, networks.info, networks.dev_name, networks.network_type, network_channels.channel, network_freqs.freq, network_encryptions.encryption, network_snr.packets, network_snr.last_signal_dbm, network_snr.max_signal_dbm from networks inner join network_channels on networks.mac = network_channels.mac inner join network_freqs on networks.mac = network_freqs.mac inner join network_encryptions on networks.mac = network_encryptions.mac inner join network_snr on networks.mac = network_snr.mac group by networks.mac;';
                            $r = mysqli_query($dbc, $q);

                            while($row = mysqli_fetch_array($r, MYSQLI_NUM))
                            {
                                array_push($macs, $row[0]);
                                array_push($essid, $row[1]);
                                array_push($discovery_type, $row[2]);
                                array_push($info, $row[3]);
                                array_push($dev_name, $row[4]);
                                array_push($network_type, $row[5]);
								array_push($channel, $row[6]);
                                array_push($freq, $row[7]);
                                array_push($encryption, $row[8]);
                                array_push($packets, $row[9]);
                                array_push($lastSig, $row[10]);
								array_push($maxSig, $row[11]);

                            }

                            for($counter = 0; $counter < count($macs); $counter++)
                            {
                                if(empty($essid[$counter]))
								{
									$essid[$counter] = "Hidden";
								}
								
                                echo '<tr><td>' . $macs[$counter] . '</td><td>' 
									 . $essid[$counter] . '</td><td>' 
									 . $discovery_type[$counter] . '</td><td>' 
									 . $info[$counter] . '</td><td>' 
									 . $dev_name[$counter] . '</td><td>' 
									 . $network_type[$counter] . '</td><td>' 
									 . $channel[$counter] . '</td><td>' 
									 . $freq[$counter] .  '</td><td>' 
									 . $encryption[$counter] . '</td><td>' 
									 . $packets[$counter] . '</td><td>' 
									 . $lastSig[$counter] . '</td><td>' 
									 . $maxSig[$counter] . '</td></tr>';
                                
                            }
                            ?>

                            </tbody>
                        </table>
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
