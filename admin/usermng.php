
<?php
error_reporting(0);
session_start();
include_once '../oesdb.php';
if (!isset($_SESSION['admname'])) {
    $_GLOBALS['message'] = "Session Timeout.Click here to <a href=\"index.php\">Re-LogIn</a>";
} else if (isset($_REQUEST['logout'])) {
    unset($_SESSION['admname']);
    header('Location: index.php');
} else if (isset($_REQUEST['dashboard'])) {

    header('Location: admwelcome.php');
} else if (isset($_REQUEST['tcmng'])) {

    header('Location: tcmng.php');
} else if (isset($_REQUEST['delete'])) {
    unset($_REQUEST['delete']);
    $hasvar = false;
    foreach ($_REQUEST as $variable) {
        if (is_numeric($variable)) { 
            $hasvar = true;

            if (!@executeQuery("delete from student where stdid=$variable")) {
                if (mysql_errno () == 1451)
                    $_GLOBALS['message'] = "Too Prevent accidental deletions, system will not allow propagated deletions.<br/><b>Help:</b> If you still want to delete this user, then first manually delete all the records that are associated with this user.";
                else
                    $_GLOBALS['message'] = mysql_errno();
            }
        }
    }
    if (!isset($_GLOBALS['message']) && $hasvar == true)
        $_GLOBALS['message'] = "Selected User/s are successfully Deleted";
    else if (!$hasvar) {
        $_GLOBALS['message'] = "First Select the users to be Deleted.";
    }
} else if (isset($_REQUEST['savem'])) {
    
    if (empty($_REQUEST['cname']) || empty($_REQUEST['password']) || empty($_REQUEST['email'])) {
        $_GLOBALS['message'] = "Some of the required Fields are Empty.Therefore Nothing is Updated";
    } else {
        

        $query = "update student set stdname='" . $_REQUEST['cname']."',stdpassword=ENCODE('".$_REQUEST['password']."','oespass'),emailid='".$_REQUEST['email']."',contactno='".$_REQUEST['contactno']."',address='".$_REQUEST['address']."',city='".$_REQUEST['city']."',pincode='".$_REQUEST['pin']."' where stdid='".$_REQUEST['student']. "';";
    
        

        if (!@executeQuery($query))
            $_GLOBALS['message'] = mysql_error();
        else
            $_GLOBALS['message'] = "User Information is Successfully Updated.";
    }
    closedb();
}
else if (isset($_REQUEST['savea'])) {
    $result = executeQuery("select max(stdid) as std from student");
    $r = mysql_fetch_array($result);
    if (is_null($r['std']))
        $newstd = 1;
    else
        $newstd=$r['std'] + 1;

    $result = executeQuery("select stdname as std from student where stdname='" .$_REQUEST['cname'] . "';");


    if (empty($_REQUEST['cname']) || empty($_REQUEST['password']) || empty($_REQUEST['email'])) {
        $_GLOBALS['message'] = "Some of the required Fields are Empty";
    } else if (mysql_num_rows($result) > 0) {
        $_GLOBALS['message'] = "Sorry User Already Exists.";
    } else {
        

        $query = "insert into student values($newstd,'" .$_REQUEST['cname']."',ENCODE('" .$_REQUEST['password'] . "','oespass'),'".$_REQUEST['email']."','".$_REQUEST['contactno']."','".$_REQUEST['address']."','".$_REQUEST['city']. "','".$_REQUEST['pin']. "')";
        

        if (!@executeQuery($query)) {
            if (mysql_errno () == 1062) 
                $_GLOBALS['message'] = "Given User Name voilates some constraints, please try with some other name.";
            else
                $_GLOBALS['message'] = mysql_error();
        }
        else
            $_GLOBALS['message'] = "Successfully New User is Created.";
    }
    closedb();
}
?>
<html>
    <head>
        <title>OES-Manage Users</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="stylesheet" type="text/css" href="../oes.css"/>
        <script type="text/javascript" src="../validate.js" ></script>
    </head>
    <body>
<?php
if (isset($_GLOBALS['message'])) {
    echo "<div class=\"message\">" . $_GLOBALS['message'] . "</div>";
}
?>
        <div id="container">
            <div class="header">
                <h3 class="headtext"> &nbsp;Exam Material Management System </h3><h4 style="color:#ffffff;text-align:center;margin:0 0 5px 5px;"><i>...because Examination Matters</i></h4>
            </div>
            <form name="usermng" action="usermng.php" method="post">
                <div class="menubar">


                    <ul id="menu">
<?php
if (isset($_SESSION['admname'])) {
// Navigations
?>
                        <li><input type="submit" value="LogOut" name="logout" class="subbtn" title="Log Out"/></li>
                        <li><input type="submit" value="Home" name="dashboard" class="subbtn" title="Dash Board"/></li>
                        
<?php
    if (isset($_REQUEST['add'])) {
?>
                        <li><input type="submit" value="Cancel" name="cancel" class="subbtn" title="Cancel"/></li>
                        <li><input type="submit" value="Save" name="savea" class="subbtn" onclick="validateform('usermng')" title="Save the Changes"/></li>

<?php
    } else if (isset($_REQUEST['edit'])) { //navigation for Edit option
?>
                        <li><input type="submit" value="Cancel" name="cancel" class="subbtn" title="Cancel"/></li>
                        <li><input type="submit" value="Save" name="savem" class="subbtn" onclick="validateform('usermng')" title="Save the changes"/></li>

<?php
    } else {  
?>
                        <li><input type="submit" value="Delete" name="delete" class="subbtn" title="Delete"/></li>
                        <li><input type="submit" value="Add" name="add" class="subbtn" title="Add"/></li>
<?php }
} ?>
                    </ul>

                </div>
                <div class="page">
<?php
if (isset($_SESSION['admname'])) {
    echo "<div class=\"pmsg\" style=\"text-align:center;\">Students Management </div>";
    if (isset($_REQUEST['add'])) {
       ?>
                    <table cellpadding="20" cellspacing="20" style="text-align:left;margin-left:15em" >
                        <tr>
                            <td>User Name</td>
                            <td><input type="text" name="cname" value="" size="16" onkeyup="isalphanum(this)"/></td>

                        </tr>

                        <tr>
                            <td>Password</td>
                            <td><input type="password" name="password" value="" size="16" onkeyup="isalphanum(this)" /></td>

                        </tr>
                        <tr>
                            <td>Re-type Password</td>
                            <td><input type="password" name="repass" value="" size="16" onkeyup="isalphanum(this)" /></td>

                        </tr>
                        <tr>
                            <td>E-mail ID</td>
                            <td><input type="text" name="email" value="" size="16" /></td>
                        </tr>
                        <tr>
                            <td>Contact No</td>
                            <td><input type="text" name="contactno" value="" size="16" onkeyup="isnum(this)"/></td>
                        </tr>

                        <tr>
                            <td>Address</td>
                            <td><textarea name="address" cols="20" rows="3"></textarea></td>
                        </tr>
                        <tr>
                            <td>City</td>
                            <td><input type="text" name="city" value="" size="16" onkeyup="isalpha(this)"/></td>
                        </tr>
                        <tr>
                            <td>PIN Code</td>
                            <td><input type="text" name="pin" value="" size="16" onkeyup="isnum(this)" /></td>
                        </tr>

                    </table>

<?php
    } else if (isset($_REQUEST['edit'])) {
       
        $result = executeQuery("select stdid,stdname,DECODE(stdpassword,'oespass') as stdpass ,emailid,contactno,address,city,pincode from student where stdname='" .$_REQUEST['edit']. "';");
        if (mysql_num_rows($result) == 0) {
            header('Location: usermng.php');
        } else if ($r = mysql_fetch_array($result)) {

       ?>
                    <table cellpadding="20" cellspacing="20" style="text-align:left;margin-left:15em" >
                        <tr>
                            <td>User Name</td>
                            <td><input type="text" name="cname" value="<?php echo  $r['stdname'] ; ?>" size="16" onkeyup="isalphanum(this)"/></td>

                        </tr>

                        <tr>
                            <td>Password</td>
                            <td><input type="text" name="password" value="<?php echo  $r['stdpass'] ; ?>" size="16" onkeyup="isalphanum(this)" /></td>

                        </tr>

                        <tr>
                            <td>E-mail ID</td>
                            <td><input type="text" name="email" value="<?php echo  $r['emailid'] ; ?>" size="16" /></td>
                        </tr>
                        <tr>
                            <td>Contact No</td>
                            <td><input type="text" name="contactno" value="<?php echo  $r['contactno'] ; ?>" size="16" onkeyup="isnum(this)"/></td>
                        </tr>

                        <tr>
                            <td>Address</td>
                            <td><textarea name="address" cols="20" rows="3"><?php echo  $r['address'] ; ?></textarea></td>
                        </tr>
                        <tr>
                            <td>City</td>
                            <td><input type="text" name="city" value="<?php echo  $r['city'] ; ?>" size="16" onkeyup="isalpha(this)"/></td>
                        </tr>
                        <tr>
                            <td>PIN Code</td>
                            <td><input type="hidden" name="student" value="<?php echo  $r['stdid'] ; ?>"/><input type="text" name="pin" value="<?php echo $r['pincode']; ?>" size="16" onkeyup="isnum(this)" /></td>
                        </tr>

                    </table>
<?php
                    closedb();
                }
            } else {
                $result = executeQuery("select * from student order by stdid;");
                if (mysql_num_rows($result) == 0) {
                    echo "<h3 style=\"color:#0000cc;text-align:center;\">No Users Yet..!</h3>";
                } else {
                    $i = 0;
?>
                    <table cellpadding="30" cellspacing="10" class="datatable">
                        <tr>
                            <th>&nbsp;</th>
                            <th>User Name</th>
                            <th>Email-ID</th>
                            <th>Contact Number</th>
                            <th>Edit</th>
                        </tr>
                <?php
                    while ($r = mysql_fetch_array($result)) {
                        $i = $i + 1;
                        if ($i % 2 == 0)
                            echo "<tr class=\"alt\">";
                        else
                            echo "<tr>";
                        echo "<td style=\"text-align:center;\">
                                <input type=\"checkbox\" name=\"d$i\" value=\"" . $r['stdid'] . "\" />
                                </td><td>" .$r['stdname']. "</td><td>" .$r['emailid']. "</td><td>" .$r['contactno']. "</td>"
                                ."<td >
                            <a title=\"Edit " . $r['stdname']."\"href=\"usermng.php?edit=" .$r['stdname']. "\">
                            <h4>Edit</h4>
                            </a></td></tr>";
                    }
                ?>
                    </table>
            <?php
                }
                closedb();
            }
        }
?>

                </div>
            </form>
            <div id="footer">
                <p style="font-size:70%;color:#ffffff;"> Developed By-<b>Pravin Jadhav & Aditya Veera</b>
            </div>
        </div>
    </body>
</html>

