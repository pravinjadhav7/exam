<?php

error_reporting(0);
session_start();
include_once 'oesdb.php';
if (!isset($_SESSION['stdname'])) {
    $_GLOBALS['message'] = "Session Timeout.Click here to <a href=\"index.php\">Re-LogIn</a>";
} else if (isset($_SESSION['starttime'])) {
    header('Location: testconducter.php');
} else if (isset($_REQUEST['logout'])) {

    unset($_SESSION['stdname']);
    header('Location: index.php');
} else if (isset($_REQUEST['dashboard'])) {

    header('Location: stdwelcome.php');
} else if (isset($_REQUEST['starttest'])) {

    if (!empty($_REQUEST['tc'])) {
        $result = executeQuery("select DECODE(testcode,'oespass') as tcode from test where testid=" . $_SESSION['testid'] . ";");

        if ($r = mysql_fetch_array($result)) {
            if (strcmp($r['tcode'], $_REQUEST['tc']) != 0) {
                $display = true;
                $_GLOBALS['message'] = "You have entered an Invalid Test Code.Try again.";
            } else {


                $result = executeQuery("select * from question where testid=" . $_SESSION['testid'] . " order by qnid;");
                if (mysql_num_rows($result) == 0) {
                    $_GLOBALS['message'] = "Tests questions cannot be selected.Please Try after some time!";
                } else {
                  //  executeQuery("COMMIT");
                    $error = false;


                    if (!executeQuery("insert into studenttest values(" . $_SESSION['stdid'] . "," . $_SESSION['testid'] . ",(select CURRENT_TIMESTAMP),date_add((select CURRENT_TIMESTAMP),INTERVAL (select duration from test where testid=" . $_SESSION['testid'] . ") MINUTE),0,'inprogress')"))
                        $_GLOBALS['message'] = "error" . mysql_error();

                    else {
                        while ($r = mysql_fetch_array($result)) {
                            if (!executeQuery("insert into studentquestion values(" . $_SESSION['stdid'] . "," . $_SESSION['testid'] . "," . $r['qnid'] . ",'unanswered',NULL)")) {
                                $_GLOBALS['message'] = "Failure while preparing questions for you.Try again";
                                $error = true;
                            }
                        }
                        if ($error == true) {
                    
                        } else {
                            $result = executeQuery("select totalquestions,duration from test where testid=" . $_SESSION['testid'] . ";");
                            $r = mysql_fetch_array($result);
                            $_SESSION['tqn'] = $r['totalquestions'] ;
                            $_SESSION['duration'] = $r['duration'] ;
                            $result = executeQuery("select DATE_FORMAT(starttime,'%Y-%m-%d %H:%i:%s') as startt,DATE_FORMAT(endtime,'%Y-%m-%d %H:%i:%s') as endt from studenttest where testid=" . $_SESSION['testid'] . " and stdid=" . $_SESSION['stdid'] . ";");
                            $r = mysql_fetch_array($result);
                            $_SESSION['starttime'] = $r['startt'];
                            $_SESSION['endtime'] = $r['endt'];
                            $_SESSION['qn'] = 1;
                            header('Location: testconducter.php');
                        }
                    }
                }
            }
        } else {
            $display = true;
            $_GLOBALS['message'] = "You have entered an Invalid Test Code.Try again.";
        }
    } else {
        $display = true;
        $_GLOBALS['message'] = "Enter the Test Code First!";
    }
} else if (isset($_REQUEST['testcode'])) {

    if ($r = mysql_fetch_array($result = executeQuery("select testid from test where testname='" .  $_REQUEST['testcode']  . "';"))) {
        $_SESSION['testname'] = $_REQUEST['testcode'];
        $_SESSION['testid'] = $r['testid'];
    }
} else if (isset($_REQUEST['savem'])) {

    if (empty($_REQUEST['cname']) || empty($_REQUEST['password']) || empty($_REQUEST['email'])) {
        $_GLOBALS['message'] = "Some of the required Fields are Empty.Therefore Nothing is Updated";
    } else {
        $query = "update student set stdname='" .  $_REQUEST['cname']  . "', stdpassword=ENCODE('" .  $_REQUEST['password']  . "','oespass'),emailid='" . $_REQUEST['email']  . "',contactno='" .  $_REQUEST['contactno']  . "',address='" .  $_REQUEST['address']  . "',city='" .  $_REQUEST['city']  . "',pincode='" .  $_REQUEST['pin']  . "' where stdid='" . $_REQUEST['student'] . "';";
        if (!@executeQuery($query))
            $_GLOBALS['message'] = mysql_error();
        else
            $_GLOBALS['message'] = "Your Profile is Successfully Updated.";
    }
    closedb();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <title> Tests</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta http-equiv="CACHE-CONTROL" content="NO-CACHE"/>
        <meta http-equiv="PRAGMA" content="NO-CACHE"/>
        <meta name="ROBOTS" content="NONE"/>

        <link rel="stylesheet" type="text/css" href="oes.css"/>
        <script type="text/javascript" src="validate.js" ></script>
    </head>
    <body >
        <?php
        if ($_GLOBALS['message']) {
            echo "<div class=\"message\">" . $_GLOBALS['message'] . "</div>";
        }
        ?>
        <div id="container">
            <div class="header">
                <h3 class="headtext"> &nbsp;Exam Materail Management Syatem </h3><h4 style="color:#ffffff;text-align:center;margin:0 0 5px 5px;"><i>...because Examination Matters</i></h4>
            </div>
            <form id="stdtest" action="stdtest.php" method="post">
                <div class="menubar">
                    <ul id="menu">
                        <?php
                        if (isset($_SESSION['stdname'])) {
                            
                        ?>
                            <li><input type="submit" value="LogOut" name="logout" class="subbtn" title="Log Out"/></li>
                            <li><input type="submit" value="Home" name="dashboard" class="subbtn" title="Dash Board"/></li>


                        </ul>
                    </div>
                    <div class="page">
                    <?php
                            if (isset($_REQUEST['testcode'])) {
                                echo "<div class=\"pmsg\" style=\"text-align:center;\">What is the Code of " . $_SESSION['testname'] . " ? </div>";
                            } else {
                                echo "<div class=\"pmsg\" style=\"text-align:center;\">Offered Tests</div>";
                            }
                    ?>
                    <?php
                            if (isset($_REQUEST['testcode']) || $display == true) {
                    ?>
                                <table cellpadding="30" cellspacing="10">
                                    <tr>
                                        <td>Enter Test Code</td>
                                        <td><input type="text" tabindex="1" name="tc" value="" size="16" /></td>
                                        <td><div class="help"><b>Note:</b><br/>Once you press start test<br/>button timer will be started</div></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <input type="submit" tabindex="3" value="Start Test" name="starttest" class="subbtn" />
                                        </td>
                                    </tr>
                                </table>


                    <?php
                            } else {
                                $result = executeQuery("select t.*,s.subname from test as t, subject as s where s.subid=t.subid and CURRENT_TIMESTAMP<t.testto and t.totalquestions=(select count(*) from question where testid=t.testid) and NOT EXISTS(select stdid,testid from studenttest where testid=t.testid and stdid=" . $_SESSION['stdid'] . ");");
                                if (mysql_num_rows($result) == 0) {
                                    echo"<h3 style=\"color:#0000cc;text-align:center;\">Sorry...! For this moment, You have not Offered to take any tests.</h3>";
                                } else {
                                  
                    ?>
                                    <table cellpadding="30" cellspacing="10" class="datatable">
                                        <tr>
                                            <th>Test Name</th>
                                            <th>Test Description</th>
                                            <th>Subject Name</th>
                                            <th>Duration</th>
                                            <th>Total Questions</th>
                                            <th>Take Test</th>
                                        </tr>
                        <?php
                                    while ($r = mysql_fetch_array($result)) {
                                        $i = $i + 1;
                                        if ($i % 2 == 0) {
                                            echo "<tr class=\"alt\">";
                                        } else {
                                            echo "<tr>";
                                        }
                                        echo "<td>"
                                        . $r['testname'] . 
                                        "</td><td>"
                                         . $r['testdesc'] . 
                                         "</td><td>"
                                          . $r['subname']  . 
                                          "</td><td>"
                                           . $r['duration']  . 
                                           "</td><td>"
                                            . $r['totalquestions']  . 
                                            "</td>"
                                        . "<td><a title=\"Start Test\" href=\"stdtest.php?testcode=" . $r['testname']  . "\">
                                            <h4>Start</>
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
<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>

                </div>

            </form>
            <div id="footer">
                <p style="font-size:70%;color:#ffffff;"> Developed By-<b>Pravin Jadhav & Aditya Veera</b>
            </div>
        </div>
    </body>
</html>

