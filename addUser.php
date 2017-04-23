<?php
include_once('topWrapper.php');
?>

    <div id="page-wrapper">

        <div class="container-fluid">

            <div class="container-fluid">
                <?php
                if(isset($_POST['submit']))
                {
                    if(empty($_POST['nameID']) || empty($_POST['emailID']))
                    {
                        echo '<div class="alert alert-warning fade in"">';
                        echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                        echo '<strong>Warning!</strong> You need to enter a name and email. You can not leave it blank.';
                        echo '</div>';
                    }
                    else
                    {
                        if(!filter_var($_POST['emailID'], FILTER_VALIDATE_EMAIL))
                        {
                            echo '<div class="alert alert-warning fade in"">';
                            echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                            echo '<strong>Warning!</strong> The email address is not valid!';
                            echo '</div>';
                        }

                        # Connect to Local Server
                        require( 'includes/connect_db_local.php' ) ;

                        $whichEmailID = trim($_POST['emailID']);
                        $whichEmailID = mysqli_real_escape_string($dbc, $whichEmailID);
                        $whichEmailID = strip_tags($whichEmailID);
                        $whichEmailID = strtolower($whichEmailID);

                        #Setting up Querry - All Phones
                        $q = 'SELECT * from users';
                        $r = mysqli_query($dbc, $q);

                        $currentEmails = array();

                        while($row = mysqli_fetch_array($r, MYSQLI_NUM))
                        {
                            array_push($currentEmails, $row[2]);
                        }

                        $isDuplicate = false;

                        foreach($currentEmails as $value)
                        {
                            if(strcmp($value, $whichEmailID) == 0)
                            {
                                $isDuplicate = true;
                            }
                        }

                        if($isDuplicate == true)
                        {
                            echo '<div class="alert alert-warning fade in"">';
                            echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                            echo '<strong>Warning!</strong>  The email you enter is already in the Database! Emails need to be unique to each user.';
                            echo '</div>';
                        }
                        else
                        {
                            #Sanitizing Input, strip HTML tags to stop SQL Injections
                            $whichNameID = trim($_POST['nameID']);
                            $whichNameID = mysqli_real_escape_string($dbc, $whichNameID);
                            $whichNameID = strip_tags($whichNameID);
                            $whichNameID = ucwords(strtolower($whichNameID));

                            echo '<div class="alert alert-success fade in"">';
                            echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                            echo '<strong>Success!</strong> You have successfully add ' . $whichNameID;
                            echo '</div>';
                        }

                    }

                }
                ?>

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Adding a User
                        </h1>
                        <p class="lead">
                            Enter the Name, Email and select the Access Level of the new User
                        </p>
                        <p class="lead" align="center">
                            Access Levels:
                            <br>Technician - Network employee, has full access expect access to Mange Users
                            <br>Admin - Head of Networking/Higher Up, has full access to the system expect code
                            <br>Super User - Network employee, Full Access to all systems including coding
                        </p>
                    </div>
                </div>
                <!-- /.row -->

                <div class="row">
                    <div class="col-lg-6">

                        <form role="form" method="post" onkeypress="return event.keyCode != 13;">


                            <div class="form-group">
                                <label>Name</label>
                                <input class="form-control" placeholder="Enter User's Name" name="nameID" id="nameID">
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input class="form-control" placeholder="Enter User's Email" name="emailID" id="emailID">
                            </div>


                            <div class="form-group">
                                <label>Select Access Level</label>
								<?php 
								if (in_array($_SESSION['accessLevel'],array("Technician")))
								{
								?>
								<select class="form-control" name="accessLevelID" id="accessLevelID">
                                    <option value="Technician">Technician</option>
                                </select>
								<?php
								}
								else if (in_array($_SESSION['accessLevel'],array("Admin")))
								{
								?>
								<select class="form-control" name="accessLevelID" id="accessLevelID">
                                    <option value="Technician">Technician</option>
                                    <option value="Admin">Admin</option>
                                </select>
								<?php
								}	
								else
								{
								?>
								<select class="form-control" name="accessLevelID" id="accessLevelID">
                                    <option value="Technician">Technician</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Super User">Super User</option>
                                </select>
								<?php	
								}
								?>
                            </div>

                            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal" onclick="alertMessage()">Submit Button</button>

                            <!-- Modal -->
                            <div id="myModal" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title"><b><font color="red"> WARNING: </font></b></h4>
                                        </div>
                                        <div class="modal-body">
                                            <p id="testing">Are you sure you want to continue?</p>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="submit" name="submit" class="btn btn-default">Okay</button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </form>

                    </div>
                </div>
                <!-- /.row -->

                <?php
                if(isset($_POST['submit']))
                {

                    # Connect to Local Server
                    require( 'includes/connect_db_local.php' ) ;

                    #Checking input
                    if(!empty($_POST['nameID']) || !empty($_POST['emailID']))
                    {

                        if(filter_var($_POST['emailID'], FILTER_VALIDATE_EMAIL))
                        {
                            # Connect to Local Server
                            require( 'includes/connect_db_local.php' ) ;

                            $whichEmailID = trim($_POST['emailID']);
                            $whichEmailID = mysqli_real_escape_string($dbc, $whichEmailID);
                            $whichEmailID = strip_tags($whichEmailID);
                            $whichEmailID = strtolower($whichEmailID);

                            #Setting up Querry - All Phones
                            $q = 'SELECT * from users';
                            $r = mysqli_query($dbc, $q);

                            $currentEmails = array();

                            while($row = mysqli_fetch_array($r, MYSQLI_NUM))
                            {
                                array_push($currentEmails, $row[2]);
                            }

                            $isDuplicate = false;

                            foreach($currentEmails as $value)
                            {
                                if(strcmp($value, $whichEmailID) == 0)
                                {
                                    $isDuplicate = true;
                                }
                            }

                            if($isDuplicate == false)
                            {
                                #Sanitizing Input, strip HTML tags to stop SQL Injections
                                $whichNameID = trim($_POST['nameID']);
                                $whichNameID = mysqli_real_escape_string($dbc, $whichNameID);
                                $whichNameID = strip_tags($whichNameID);
                                $whichNameID = ucwords(strtolower($whichNameID));

                                $whichAccessLevelID = $_POST['accessLevelID'];

                                #Setting up Querry
                                $q = "INSERT INTO users(name, email, accessLevel)VALUES('" . $whichNameID . "', '" . $whichEmailID . "', '" . $whichAccessLevelID . "')";
                                $r = mysqli_query($dbc, $q);
                            }

                        }
                    }

                }
                ?>
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
    <script>
        function alertMessage()
        {
            var nameID = document.getElementById("nameID").value;
            if(nameID == "")
            {
                nameID = "NULL";
            }
            var emailID = document.getElementById("emailID").value;
            if(emailID == "")
            {
                emailID = "NULL";
            }
            var three = document.getElementById("accessLevelID");
            var accessLevelID = three.options[three.selectedIndex].text;

            document.getElementById("testing").innerHTML = "<p>Are you sure you want to add <strong>" + nameID + "</strong> to the <strong>" + accessLevelID + "'s</strong>, at <strong>"+ emailID + "</strong>?</p>";
        }
    </script>

</body>

</html>
