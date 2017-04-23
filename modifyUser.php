<?php
include_once('topWrapper.php');
?>

<div id="page-wrapper">

        <div class="container-fluid">
            <?php
            if(isset($_POST['submit']))
            {
                if(empty($_POST['searchID']))
                {
                    echo '<div class="alert alert-warning fade in"">';
                    echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                    echo '<strong>Error!</strong> You can not leave the search box empty. You need to input a valid name.';
                    echo '</div>';
                }
                else
                {
                    #Connecting to Local Server
                    require( 'includes/connect_db_local.php');

                    #Sanitizing Input, strip HTML tags to stop SQL Injections
                    $whichSearchID = trim($_POST['searchID']);
                    $whichSearchID = mysqli_real_escape_string($dbc, $whichSearchID);
                    $whichSearchID = strip_tags($whichSearchID);
                    $whichSearchID = ucwords(strtolower($whichSearchID));

                    #Setting up Querry - All Phones
					$q = "";
					if(in_array($_SESSION['accessLevel'],array("Technician")))
					{
						$q = 'SELECT * FROM users WHERE name = "' . $whichSearchID . '" AND (accessLevel="User" OR accessLevel="MIPO Admin")';
					}
					else if(in_array($_SESSION['accessLevel'],array("Admin")))
					{
						$q = 'SELECT * FROM users WHERE name = "' . $whichSearchID . '" AND (accessLevel="User" OR accessLevel="MIPO Admin" OR accessLevel="Technician")';
					}
					else
					{
						$q = 'SELECT * FROM users WHERE name = "' . $whichSearchID . '"';
					}
                    $r = mysqli_query($dbc, $q);

                    $userID = array();
                    $userName = array();
                    $userEmail = array();
                    $userAccessLevel = array();

                    while($row = mysqli_fetch_array($r, MYSQLI_NUM))
                    {
                        $userID = $row[0];
                        $userName = $row[1];
                        $userEmail = $row[2];
                        $userAccessLevel = $row[3];
                    }

                    if(empty($userName))
                    {
                        echo '<div class="alert alert-warning fade in"">';
                        echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                        echo '<strong>Error!</strong> You need to enter a valid name to search, please look at the table of users for the names of users.';
                        echo '</div>';
                    }

                }
            }

            if(isset($_POST['submit2']))
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
                    echo '<div class="alert alert-success fade in"">';
                    echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                    echo '<strong>Success!</strong>';
                    echo '</div>';
                }

            }
            ?>
            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        Modifying a User
                    </h1>
                    <p class="lead">
                        Search for the User you want to modify by there name. Name's of users can be found on the table of users. After you have searched
                        you will be presented with a form. The form will have all the data filled out of the current state of the user. Change whatever you want
                        and the changes will be updated.
                    </p>
                </div>
            </div>
            <!-- /.row -->

            <div class="row">
                <div class="col-lg-6">

                    <form role="form" method="post" onkeypress="return event.keyCode != 13;">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search for..." id="searchID" name="searchID">
                             <span class="input-group-btn">
                                 <button type="submit" name="submit" class="btn btn-default">Go</button>
                             </span>
                        </div>
                    </form>

                        <?php

                        if(isset($_POST['submit']))
                        {
                            if(!empty($_POST['searchID']))
                            {
                                #Connecting to Local Server
                                require( 'includes/connect_db_local.php');

                                #Sanitizing Input, strip HTML tags to stop SQL Injections
                                $whichSearchID = trim($_POST['searchID']);
                                $whichSearchID = mysqli_real_escape_string($dbc, $whichSearchID);
                                $whichSearchID = strip_tags($whichSearchID);
                                $whichSearchID = ucwords(strtolower($whichSearchID));

                                #Setting up Querry - All Phones
								$q = "";
                                if(in_array($_SESSION['accessLevel'],array("Technician")))
								{
									$q = 'SELECT * FROM users WHERE name = "' . $whichSearchID . '" AND (accessLevel="User" OR accessLevel="MIPO Admin")';
								}
								else if(in_array($_SESSION['accessLevel'],array("Admin")))
								{
									$q = 'SELECT * FROM users WHERE name = "' . $whichSearchID . '" AND (accessLevel="User" OR accessLevel="MIPO Admin" OR accessLevel="Technician")';
								}
								else
								{
									$q = 'SELECT * FROM users WHERE name = "' . $whichSearchID . '"';
								}
                                $r = mysqli_query($dbc, $q);

                                $userID = array();
                                $userName = array();
                                $userEmail = array();
                                $userAccessLevel = array();

                                while($row = mysqli_fetch_array($r, MYSQLI_NUM))
                                {
                                    $userID = $row[0];
                                    $userName = $row[1];
                                    $userEmail = $row[2];
                                    $userAccessLevel = $row[3];
                                }

                                if(!empty($userName))
                                {
                                    echo '<form role="form" method="post" onkeypress="return event.keyCode != 13;">';
                                    echo '<div class="form-group">';
                                    echo '<label>Name</label>';
                                    echo '<input class="form-control" placeholder="Enter User\'s Name" name="nameID"  id="nameID" value="' . $userName .'">';
                                    echo '</div>';

                                    echo '<input type="hidden" name="usersID" value="' . $userID . '">';

                                    echo '<div class="form-group">';
                                    echo '<label>Email</label>';
                                    echo '<input class="form-control" placeholder="Enter User\'s Email" name="emailID" id="emailID" value="' . $userEmail .  '">';
                                    echo '</div>';
									
									
									$accessLevels = array("Technician", "Admin", "Super User");
									
																		
                                    echo '<div class="form-group">';
                                    echo '<label>Select Access Level</label>';
                                    echo '<select class="form-control" name="accessLevelID" id="accessLevelID">';

                                    foreach($accessLevels as $value)
                                    {
                                        if($value == $userAccessLevel)
                                        {
                                            echo '<option value="' . $value . '" selected>' . $value . '</option>';
                                        }
                                        else
                                        {
                                            echo '<option value="' . $value . '">' . $value . '</option>';
                                        }
                                    }
                                    echo '</select>';
                                    echo '</div>';

                                    echo '<button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal" onclick="alertMessage()">Submit Button</button>';

                                    echo '<!-- Modal -->';
                                    echo '<div id="myModal" class="modal fade" role="dialog">';
                                    echo '<div class="modal-dialog">';

                                    echo '<!-- Modal content-->';
                                    echo '<div class="modal-content">';
                                    echo '<div class="modal-header">';
                                    echo '<h4 class="modal-title"><b><font color="red"> WARNING: </font></b></h4>';
                                    echo '</div>';
                                    echo '<div class="modal-body">';
                                    echo '<p id="testing">Are you sure you want to continue?</p>';

                                    echo '</div>';
                                    echo '<div class="modal-footer">';
                                    echo '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
                                    echo '<button type="submit" name="submit2" class="btn btn-default">Okay</button>';
                                    echo '</div>';
                                    echo '</div>';

                                    echo '</div>';
                                    echo '</div>';

                                    echo '</form>';
                                }
                            }

                        }

                        if(isset($_POST['submit2']))
                        {

                            # Connect to Local Server
                            require( 'includes/connect_db_local.php' ) ;

                            #Sanitizing Input, strip HTML tags to stop SQL Injections
                            $whichNameID = trim($_POST['nameID']);
                            $whichNameID = mysqli_real_escape_string($dbc, $whichNameID);
                            $whichNameID = strip_tags($whichNameID);



                            $whichEmailID = trim($_POST['emailID']);
                            $whichEmailID = mysqli_real_escape_string($dbc, $whichEmailID);
                            $whichEmailID = strip_tags($whichEmailID);

                            if(!empty($whichNameID) && !empty($whichEmailID))
                            {
                                $whichAccessLevelID = $_POST['accessLevelID'];


                                #Setting up Querry
                                $q = "Update users set name='" . $whichNameID . "', email ='" . $whichEmailID . "', accessLevel ='" . $whichAccessLevelID . "' where num = '" . $_POST['usersID'] . "'";
                                $r = mysqli_query($dbc, $q);
                            }

                        }

                        ?>

                </div><!-- /.col-lg-6 -->
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

        document.getElementById("testing").innerHTML = "<p>Are you sure you want to modify <strong>" + nameID + "</strong> to the <strong>" + accessLevelID + "'s</strong>, at <strong>"+ emailID + "</strong>?</p>";
    }
</script>

</body>

</html>
