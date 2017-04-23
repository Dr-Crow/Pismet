<?php
//Required for CAS authentication.
include_once('CAS.php');

//Required for CAS authentication.
phpCAS::client(SAML_VERSION_1_1,'login.marist.edu',443,'cas'); //SAML required for phpCAS::getAttribute();
phpCAS::setNoCasServerValidation(); //Don't bother checking the SSL cert on the server. DO NOT USE IN PRODUCTION.
phpCAS::forceAuthentication(); //Make CAS go.
$user = phpCAS::getAttribute('maristmailpref');



//Required for CAS logout to work correctly.
if(isset($_GET['logout']))
{
    phpCAS::logout();
}
if (isset($_REQUEST['logout']))
{
    phpCAS::logout();
}
# Connect to Local Server
require( 'includes/connect_db_local.php' ) ;


#Setting up Querry - All Phones
$q = 'SELECT * from users where email = "' . $user . '"';
$r = mysqli_query($dbc, $q);

#Setting up variables
$_SESSION['name'] = "";
$_SESSION['accessLevel'] = "";
$_SESSION['email'] = "";

while($row = mysqli_fetch_array($r, MYSQLI_NUM))
{
    $_SESSION['name'] = $row[1];
    $_SESSION['accessLevel'] = $row[3];
    $_SESSION['email'] = $row[2];
}


if($_SESSION['name'] == null)
{
    header("Location: logout.php");
    die();
}
else
{
    userPageAccess($_SESSION['accessLevel'], basename($_SERVER['PHP_SELF']));
}

function userPageAccess($accessLevel, $currentPage)
{
    if(in_array($currentPage,fetchNotAllowedPages($accessLevel)))
    {
        header("Location: index.php");
        die();
    }

}

function fetchNotAllowedPages($accessLevel)
{
    $noOnePages = array("CAS.php", "permission.php", "userAuthication.php", "logout.php", "logoutcode.php");

    $technicianPages = array("addUser.php", "deleteUser.php", "modifyUser.php", "userTable.php");
    $technicianPages = array_unique(array_merge($technicianPages,$noOnePages));

    $userPages = array("addPhone.php", "addRow.php", "deletePhone.php", "movePhone.php", "removeRow.php",);
    $userPages = array_unique(array_merge($userPages,$technicianPages));

    $returnArray = array("all");
    switch($accessLevel)
    {
        case "Technician":
            $returnArray = $technicianPages;
            break;
        case "User":
            $returnArray = $userPages;
            break;
    }

    return $returnArray;
}


?>