<?php

include_once('userAuthication.php');


function isActive($currentLi)
{
    $currentPage = basename($_SERVER['PHP_SELF']);
    $currentPage = dropDownPages($currentPage);

    if($currentPage == $currentLi)
    {
        echo '<li class = "active">';
    }
    else
    {
        echo '<li>';
    }
}

function dropDownPages($currentPage)
{
    $Page = "";
    switch($currentPage)
    {
        case "changingCSS.php":
            $Page = "Forms";
            break;
        case "index.php":
            $Page = "index.php";
            break;
        case "tables.php":
            $Page = "tables.php";
            break;
        case "addUser.php":
        case "modifyUser.php":
        case "deleteUser.php":
        case "userTable.php":
            $Page = "Manage Users";
            break;
    }
    return $Page;
}

if(in_array($_SESSION['accessLevel'],array("Admin", "Technician", "Super User")))
    {
        isActive("index.php");
        echo '<a href="index.php"><font color = #de2121><i class="fa fa-fw fa-dashboard"></i> Dashboard</a></font>';
        echo '</li>';

        echo '<li>';
        isActive("Forms");
        echo '<a href="javascript:;" data-toggle="collapse" data-target="#demo"><font color = #de2121><i class="fa fa-fw fa-edit"></i>Forms<i class="fa fa-fw fa-caret-down"></i></font></a>';
        echo '<ul id="demo" class="collapse">';
        echo '<li><a href="index.php"><font color = #de2121>Graphing Interference</font></a></li>';
    }

    #Closing Forms Tags
    echo '</ul></li>';

    isActive("tables.php");
    echo '<a href="tables.php"><font color = #de2121><i class="fa fa-fw fa-table"></i>Table of Networks</font></a></li>';

	isActive("tables2.php");
    echo '<a href="tables2.php"><font color = #de2121><i class="fa fa-fw fa-table"></i>Table of Clients</font></a></li>';
	
    if(in_array($_SESSION['accessLevel'],array("Technician", "Admin", "Super User")))
    {
        isActive("Manage Users");
        echo '<a href="javascript:;" data-toggle="collapse" data-target="#user"><font color = #de2121><i class="fa fa-fw fa-edit"></i>Manage Users<i class="fa fa-fw fa-caret-down"></i></font></a>';
        echo '<ul id="user" class="collapse">';
            echo '<li><a href="addUser.php"><font color = #de2121>Add User</font></a></li>';
            echo '<li><a href="modifyUser.php"><font color = #de2121>Modify User</font></a></li>';
            echo '<li><a href="deleteUser.php"><font color = #de2121>Delete User</font></a></li>';
        echo '<li><a href="userTable.php"><font color = #de2121>User Table</font></a></li>';
        #Closing Users Tags
        echo '</ul></li>';
    }

    echo '</ul></li>';
?>


