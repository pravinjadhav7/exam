
<?php
error_reporting(0);
session_start();
include_once '../oesdb.php';
if (!isset($_SESSION['admname'])) {
    $_GLOBALS['message'] = "Session Timeout.Click here to <a href=\"index.php\">LogIn Again</a>";
} else if (isset($_REQUEST['logout'])) {
    unset($_SESSION['admname']);
    header('Location: index.php');
} else if (isset($_REQUEST['dashboard'])) {
    header('Location: admwelcome.php');
} else if (isset($_REQUEST['delete'])) { 
    unset($_REQUEST['delete']);
    $hasvar = false;
    foreach ($_REQUEST as $variable) {
        if (is_numeric($variable)) { 
            $hasvar = true;

            if (!@executeQuery("delete from test where testid=$variable")) {
                if (mysql_errno () == 1451) 
                    $_GLOBALS['message'] = "Error";
                else
                    $_GLOBALS['message'] = mysql_errno();
            }
        }
    }
    if (!isset($_GLOBALS['message']) && $hasvar == true)
        $_GLOBALS['message'] = "Selected Tests are successfully Deleted";
    else if (!$hasvar) {
        $_GLOBALS['message'] = "First Select the Tests to be Deleted.";
    }
} else if (isset($_REQUEST['savem'])) {
    
    $fromtime = $_REQUEST['testfrom'] . " " . date("H:i:s");
    $totime = $_REQUEST['testto'] . " 23:59:59";
    $_GLOBALS['message'] = strtotime($totime) . "  " . strtotime($fromtime) . "  " . time();
    
    if (strtotime($fromtime) > strtotime($totime) || strtotime($totime) < time())
        
        $_GLOBALS['message'] = "Start date of test is less than end date or last date of test is less than today's date.<br/>Therefore Nothing is Updated";
    
    else if (empty($_REQUEST['testname']) || empty($_REQUEST['testdesc']) || empty($_REQUEST['totalqn']) || empty($_REQUEST['duration']) || empty($_REQUEST['testfrom']) || empty($_REQUEST['testto']) || empty($_REQUEST['testcode'])) {
        $_GLOBALS['message'] = "Some of the required Fields are Empty.Therefore Nothing is Updated";
    } else {
    
        $query = "update test set testname='" . $_REQUEST['testname'] . "',testdesc='" . $_REQUEST['testdesc'] . "',subid=" . 
                $_REQUEST['subject'].",testfrom='" . $fromtime . "',testto='" . $totime . "',duration=" . $_REQUEST['duration']
                 . ",totalquestions=" . $_REQUEST['totalqn'] . ",testcode=ENCODE('" . $_REQUEST['testcode'] . "','oespass') where testid=" . $_REQUEST['testid'] . ";";
    

        if (!@executeQuery($query))
            $_GLOBALS['message'] = mysql_error();
        else
            $_GLOBALS['message'] = "Successfully Updated.";
    }
    closedb();
}
else if (isset($_REQUEST['savea'])) {

    //Adding information in the database
    $noerror = true;
    $fromtime = $_REQUEST['testfrom'] . " " . date("H:i:s");
    $totime = $_REQUEST['testto'] . " 23:59:59";
    if (strtotime($fromtime) > strtotime($totime) || strtotime($fromtime) < (time() - 3600)) {
        $noerror = false;
        $_GLOBALS['message'] = "Date Not valid.";
    } else if ((strtotime($totime) - strtotime($fromtime)) <= 3600 * 24) {
        $noerror = true;
        $_GLOBALS['message'] = "Note:<br/>The test is valid upto " . date(DATE_RFC850, strtotime($totime));
    }

    //$_GLOBALS['message']="time".date_format($first, DATE_ATOM)."<br/>time ".date_format($second, DATE_ATOM);


    $result = executeQuery("select max(testid) as tst from test");
    $r = mysql_fetch_array($result);
    if (is_null($r['tst']))
        $newstd = 1;
    else
        $newstd=$r['tst'] + 1;

    // $_GLOBALS['message']=$newstd;
    if (strcmp($_REQUEST['subject'], "<Choose the Subject>") == 0 || empty($_REQUEST['testname']) || empty($_REQUEST['testdesc']) || empty($_REQUEST['totalqn']) || empty($_REQUEST['duration']) || empty($_REQUEST['testfrom']) || empty($_REQUEST['testto']) || empty($_REQUEST['testcode'])) 
    {
        $_GLOBALS['message'] = "Some of the required Fields are Empty";
   
    } else if ($noerror) {
       
        $query = "insert into test values($newstd,'" . $_REQUEST['testname'] . "','" . $_REQUEST['testdesc'] . "',(select curDate()),(select curTime())," . $_REQUEST['subject'] . ",'" . $fromtime . "','" . $totime . "'," .                $_REQUEST['duration'] . "," . $_REQUEST['totalqn'] . ",0,ENCODE('" . $_REQUEST['testcode'] . "','oespass'),NULL)";
        
        if (!@executeQuery($query)) {
            if (mysql_errno () == 1062) //duplicate value
                $_GLOBALS['message'] = "Given Test Name voilates some constraints, please try with some other name.";
            else
                $_GLOBALS['message'] = mysql_error();
        }
        else
            $_GLOBALS['message'] = $_GLOBALS['message'] . "<br/>Successfully New Test is Created.";
    }
    closedb();
}
else if (isset($_REQUEST['manageqn'])) {


    //$tempa=explode(" ",$_REQUEST['testqn']);
    // $testname=substr($_REQUEST['manageqn'],0,-10);
    $testname = $_REQUEST['manageqn'];
    $result = executeQuery("select testid from test where testname='" . $testname. "';");

    if ($r = mysql_fetch_array($result)) {
        $_SESSION['testname'] = $testname;
        $_SESSION['testqn'] = $r['testid'];
        //  $_GLOBALS['message']=$_SESSION['testname'];
    
        header('Location: prepqn.php');
    }
}
?>
<html>
    <head>
        <title>OES-Manage Tests</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="stylesheet" type="text/css" href="../oes.css"/>
        <link rel="stylesheet" type="text/css" media="all" href="../calendar/jsDatePick.css" />
        <script type="text/javascript" src="../calendar/jsDatePick.full.1.1.js"></script>
        <script type="text/javascript">
            window.onload = function(){
                new JsDatePick({
                    useMode:2,
                    target:"testfrom"
                });

                new JsDatePick({
                    useMode:2,
                    target:"testto"
                });
            };
        </script>

        <script type="text/javascript" src="../validate.js" ></script>
    </head>
    <body>
<?php
if ($_GLOBALS['message']) {
    echo "<div class=\"message\">" . $_GLOBALS['message'] . "</div>";
}
?>
        <div id="container">
            <div class="header">
               <h3 class="headtext"> &nbsp;Exam Material Management System </h3><h4 style="color:#ffffff;text-align:center;margin:0 0 5px 5px;"><i>...Because Exams Matter</i></h4>
            </div>
            <form name="testmng" action="testmng.php" method="post">
                <div class="menubar">


                    <ul id="menu">
                    <?php
                    if (isset($_SESSION['admname'])) {
                    ?>
                        <li><input type="submit" value="LogOut" name="logout" class="subbtn" title="Log Out"/></li>
                        <li><input type="submit" value="Home" name="dashboard" class="subbtn" title="Dash Board"/></li>

                    <?php
                        if (isset($_REQUEST['add'])) {
                    ?>
                        <li><input type="submit" value="Cancel" name="cancel" class="subbtn" title="Cancel"/></li>
                        <li><input type="submit" value="Save" name="savea" class="subbtn" onclick="validatetestform('testmng')" title="Save the Changes"/></li>

                    <?php
                        } else if (isset($_REQUEST['edit'])) { 
                    ?>
                        <li><input type="submit" value="Cancel" name="cancel" class="subbtn" title="Cancel"/></li>
                        <li><input type="submit" value="Save" name="savem" class="subbtn" onclick="validatetestform('testmng')" title="Save the changes"/></li>

                    <?php
                        } else { 
                    ?>
                        <li><input type="submit" value="Add" name="add" class="subbtn" title="Add"/></li>
                    <?php }
                    } ?>
                    </ul>

                </div>
                <div class="page">
<?php
if (isset($_SESSION['admname'])) {

    if (isset($_REQUEST['forpq']))
        echo "<div class=\"pmsg\" style=\"text-align:center\"> Which test questions Do you want to Manage? <br/><b>Help:</b>Click on Questions button to manage the questions of respective tests</div>";
    if (isset($_REQUEST['add'])) {
?>
                    <table cellpadding="20" cellspacing="20" style="text-align:left;" >
                        <tr>
                            <td>Subject Name</td>
                            <td>
                                <select name="subject">
                                    <option selected value="<Choose the Subject>">&lt;Choose the Subject&gt;</option>
                                <?php
                                      $result = executeQuery("select subid,subname from subject;");
                                      while ($r = mysql_fetch_array($result)) {

                                      echo "<option value=\"".$r['subid']."\">". $r['subname']. "</option>";
        }
        closedb();
?>
                                </select>
                            </td>

                        </tr>
                        <tr>
                            <td>Test Name</td>
                            <td><input type="text" name="testname" value="" size="16" onkeyup="isalphanum(this)" /></td>
                            <td><div class="help"><b>Note:</b><br/>Test Name must be Unique<br/> in order to identify different<br/> tests on same subject.</div></td>
                        </tr>
                        <tr>
                            <td>Test Description</td>
                            <td><textarea name="testdesc" cols="20" rows="3" ></textarea></td>
                            <td><div class="help"><b>Describe here:</b><br/>What the test is all about?</div></td>
                        </tr>
                        <tr>
                            <td>Total Questions</td>
                            <td><input type="text" name="totalqn" value="" size="16" onkeyup="isnum(this)" /></td>

                        </tr>
                        <tr>
                            <td>Duration(Mins)</td>
                            <td><input type="text" name="duration" value="" size="16" onkeyup="isnum(this)" /></td>

                        </tr>
                        <tr>
                            <td>Test From </td>
                            <td><input id="testfrom" type="text" name="testfrom" value="" size="16" readonly /></td>
                        </tr>
                        <tr>
                            <td>Test To </td>
                            <td><input id="testto" type="text" name="testto" value="" size="16" readonly /></td>
                        </tr>

                        <tr>
                            <td>Test Secret Code</td>
                            <td><input type="text" name="testcode" value="" size="16" onkeyup="isalphanum(this)" /></td>
                            <td><div class="help"><b>Note:</b><br/>Candidates must enter<br/>this code in order to <br/> take the test</div></td>
                        </tr>

                    </table>

<?php
    } else if (isset($_REQUEST['edit'])) {
  
        $result = executeQuery("select t.totalquestions,t.duration,t.testid,t.testname,t.testdesc,t.subid,s.subname,DECODE(t.testcode,'oespass') as tcode,DATE_FORMAT(t.testfrom,'%Y-%m-%d') as testfrom,DATE_FORMAT(t.testto,'%Y-%m-%d') as testto from test as t,subject as s where t.subid=s.subid and t.testname='" . htmlspecialchars($_REQUEST['edit'], ENT_QUOTES) . "';");

        if (mysql_num_rows($result) == 0) {
            header('Location: testmng.php');
        } else if ($r = mysql_fetch_array($result)) {


            ?>
                    <table cellpadding="20" cellspacing="20" style="text-align:left;margin-left:15em" >
                        <tr>
                            <td>Subject Name</td>
                            <td>
                                <select name="subject">
<?php
            $result = executeQuery("select subid,subname from subject;");
            while ($r1 = mysql_fetch_array($result)) {
                if (strcmp($r['subname'], $r1['subname']) == 0)
                    echo "<option value=\"" . $r1['subid'] . "\" selected>" . $r1['subname'] . "</option>";
                else
                    echo "<option value=\"" . $r1['subid'] . "\">" . $r1['subname'] . "</option>";
            }
            closedb();
?>
                                </select>
                            </td>

                        </tr>
                        <tr>
                            <td>Test Name</td>
                            <td><input type="hidden" name="testid" value="<?php echo $r['testid']; ?>"/>
                            <input type="text" name="testname" value="<?php echo $r['testname']; ?>" size="16" onkeyup="isalphanum(this)" /></td>
                            <td><div class="help"><b>Note:</b><br/>Test Name must be Unique<br/> in order to identify different<br/> tests on same subject.</div></td>
                        </tr>
                        <tr>
                            <td>Test Description</td>
                            <td><textarea name="testdesc" cols="20" rows="3" ><?php echo $r['testdesc']; ?></textarea></td>
                            <td><div class="help"><b>Describe here:</b><br/>What the test is all about?</div></td>
                        </tr>
                        <tr>
                            <td>Total Questions</td>
                            <td><input type="text" name="totalqn" value="<?php echo $r['totalquestions']; ?>" size="16" onkeyup="isnum(this)" /></td>

                        </tr>
                        <tr>
                            <td>Duration(Mins)</td>
                            <td><input type="text" name="duration" value="<?php echo $r['duration']; ?>" size="16" onkeyup="isnum(this)" /></td>

                        </tr>
                        <tr>
                            <td>Test From </td>
                            <td><input id="testfrom" type="text" name="testfrom" value="<?php echo $r['testfrom']; ?>" size="16" readonly /></td>
                        </tr>
                        <tr>
                            <td>Test To </td>
                            <td><input id="testto" type="text" name="testto" value="<?php echo $r['testto']; ?>" size="16" readonly /></td>
                        </tr>

                        <tr>
                            <td>Test Secret Code</td>
                            <td><input type="text" name="testcode" value="<?php echo $r['tcode']; ?>" size="16" onkeyup="isalphanum(this)" /></td>
                            <td><div class="help"><b>Note:</b><br/>Candidates must enter<br/>this code in order to <br/> take the test</div></td>
                        </tr>

                    </table>
<?php
                                    closedb();
                                }
                            }

                            else {

                                $result = executeQuery("select t.testid,t.testname,t.testdesc,s.subname,DECODE(t.testcode,'oespass') as tcode,DATE_FORMAT(t.testfrom,'%d-%M-%Y') as testfrom,DATE_FORMAT(t.testto,'%d-%M-%Y %H:%i:%s %p') as testto from test as t,subject as s where t.subid=s.subid order by t.testdate desc,t.testtime desc;");
                                if (mysql_num_rows($result) == 0) {
                                    echo "<h3 style=\"color:#0000cc;text-align:center;\">No Tests Yet..!</h3>";
                                } else {
                                    $i = 0;
?>
                                    <table cellpadding="30" cellspacing="10" class="datatable">
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>Test Description</th>
                                            <th>Subject Name</th>
                                            <th>Test Secret Code</th>
                                            <th>Validity</th>
                                            <th>Edit</th>
                                            <th style="text-align:center;">Manage<br/>Questions</th>
                                        </tr>
<?php
                                    while ($r = mysql_fetch_array($result)) {
                                        $i = $i + 1;
                                        if ($i % 2 == 0)
                                            echo "<tr class=\"alt\">";
                                        else
                                            echo "<tr>";
                                        echo "<td style=\"text-align:center;\">
                                            <input type=\"checkbox\" name=\"d$i\" value=\"".$r['testid']."\"/></td><td>".
                                            $r['testname'].":".$r['testdesc']."</td><td>".$r['subname']."</td><td>". 
                                            $r['tcode']."</td><td>".$r['testfrom']." To ".$r['testto']."</td>"
                                            ."<td>
                                            <a title=\"Edit ".$r['testname']."\"href=\"testmng.php?edit=" . $r['testname']."\">
                                           <h4>Edit</h4>
                                           </a></td>"
                                       ."<td><a title=\"Manage Questions of ".$r['testname']."\"href=\"testmng.php?manageqn=". 
                                        $r['testname']."\">
                                        <h4>Add Que</h4>
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
                <p style="font-size:70%;color:#ffffff;"> Developed By-<b>Pravin Jadhav & Aditya Veera</b><br/>
            </div>
        </div>
    </body>
</html>
