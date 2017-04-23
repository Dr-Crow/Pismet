<?php
include_once('topWrapper.php');
?>

<div id="page-wrapper">

        <div class="container-fluid">

            <?php
            if(isset($_POST['submit']))
            {
                if(empty($_POST['nameID']))
                {
                    echo '<div class="alert alert-warning fade in"">';
                    echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                    echo '<strong>Warning!</strong> You need to enter a User\'s name, you can not leave it blank';
                    echo '</div>';
                }
                else
                {
                    #Connecting to Local Server
                    require( 'includes/connect_db_local.php');

                    #Sanitizing Input, strip HTML tags to stop SQL Injections
                    $whichNameID = trim($_POST['nameID']);
                    $whichNameID = mysqli_real_escape_string($dbc, $whichNameID);
                    $whichNameID = strip_tags($whichNameID);
                    $whichNameID = ucwords(strtolower($whichNameID));

                    #Setting up Querry - All Phones
                    $q = "";
					if(in_array($_SESSION['accessLevel'],array("MIPO Admin", "MIPO Super User")))
					{
						$q = 'SELECT * FROM users WHERE name = "' . $whichNameID . '" AND accessLevel="User"';
					}
					else if(in_array($_SESSION['accessLevel'],array("Technician")))
					{
						$q = 'SELECT * FROM users WHERE name = "' . $whichSearchID . '" AND (accessLevel="User" OR accessLevel="MIPO Admin")';
					}
					else if(in_array($_SESSION['accessLevel'],array("Admin")))
					{
						$q = 'SELECT * FROM users WHERE name = "' . $whichSearchID . '" AND (accessLevel="User" OR accessLevel="MIPO Admin" OR accessLevel="Technician")';
					}
					else
					{
						$q = 'SELECT * FROM users WHERE name = "' . $whichNameID . '"';
					}
                    $r = mysqli_query($dbc, $q);

                    $userName = array();

                    while($row = mysqli_fetch_array($r, MYSQLI_NUM))
                    {
                        $userName = $row[1];;
                    }

                    if(empty($userName))
                    {
                        echo '<div class="alert alert-warning fade in"">';
                        echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                        echo '<strong>Warning!</strong> They name you enter is a not a valid name. Pleae check the table of users';
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




            }
            ?>
            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        Deleting a User
                    </h1>
                    <p class="lead">
                        Enter a User's Name and they will be removed from the system. You can check the users table to see the list of all users.
                    </p>
                </div>
            </div>
            <!-- /.row -->

            <div class="row">
                <div class="col-lg-12">

                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">

                    <form role="form" method="post" onkeypress="return event.keyCode != 13;">


                        <div class="form-group">
                            <label>User Name</label>
                            <input class="form-control" placeholder="Enter User's Name" name="nameID" id="nameID">
                        </div>

                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal"  onclick="alertMessage()">Submit Button</button>

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

                if(!empty($_POST['nameID']))
                {

                    #Sanitizing Input, strip HTML tags to stop SQL Injections
                    $whichNameID = trim($_POST['nameID']);
                    $whichNameID = mysqli_real_escape_string($dbc, $whichNameID);
                    $whichNameID = strip_tags($whichNameID);
                    $whichNameID = ucwords(strtolower($whichNameID));

                    #Setting up Querry - All Phones
                    $q = "";
                    if(in_array($_SESSION['accessLevel'],array("MIPO Admin", "MIPO Super User")))
					{
						$q = 'SELECT * FROM users WHERE name = "' . $whichNameID . '" AND accessLevel="User"';
					}
					else if(in_array($_SESSION['accessLevel'],array("Technician")))
					{
						$q = 'SELECT * FROM users WHERE name = "' . $whichSearchID . '" AND (accessLevel="User" OR accessLevel="MIPO Admin")';
					}
					else if(in_array($_SESSION['accessLevel'],array("Admin")))
					{
						$q = 'SELECT * FROM users WHERE name = "' . $whichSearchID . '" AND (accessLevel="User" OR accessLevel="MIPO Admin" OR accessLevel="Technician")';
					}
					else
					{
						$q = 'SELECT * FROM users WHERE name = "' . $whichNameID . '"';
					}
                    $r = mysqli_query($dbc, $q);

                    $userName = array();

                    while($row = mysqli_fetch_array($r, MYSQLI_NUM))
                    {
                        $userName = $row[1];;
                    }

                    if(!empty($userName))
                    {
                        #Setting up Querry
                        $q = "DELETE FROM users WHERE name = '" . $whichNameID . "'";
                        $r = mysqli_query($dbc, $q);
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
        var name = document.getElementById("nameID").value;
        if(name == "")
        {
            name = "NULL";
        }
        document.getElementById("testing").innerHTML = "<p>Are you sure you want to remove <strong>" + name + "</strong>?</p>";
    }
</script>

</body>

</html>
