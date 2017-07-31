

<?php
error_reporting(0);
session_start();
        if(!isset($_SESSION['stdname'])){
            $_GLOBALS['message']="Session Timeout.Click here to <a href=\"index.php\">Re-LogIn</a>";
        }
        else if(isset($_REQUEST['logout'])){
                unset($_SESSION['stdname']);
            $_GLOBALS['message']="You are Loggged Out Successfully.";
            header('Location: index.php');
        }
?>
<html>
    <head>
        <title>Welcome Student</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="stylesheet" type="text/css" href="oes.css"/>
    </head>
    <body>
        <?php
       
        if($_GLOBALS['message']) {
            echo "<div class=\"message\">".$_GLOBALS['message']."</div>";
        }
        ?>
        <div id="container">
           <div class="header">
                <h3 class="headtext"> &nbsp; Exam Material managem System </h3><h4 style="color:#ffffff;text-align:center;margin:0 0 5px 5px;"><i>...Because Exams Matter</i></h4>
            </div>
            <div class="menubar">

                <form name="stdwelcome" action="stdwelcome.php" method="post">
                    <ul id="menu">
                        <?php if(isset($_SESSION['stdname'])){ ?>
                        <li><input type="submit" value="LogOut" name="logout" class="subbtn" title="Log Out"/></li>
                        <?php } ?>
                    </ul>
                </form>
            </div>
            <div class="stdpage">
                <?php if(isset($_SESSION['stdname'])){ ?>

        
                <img height="600" width="100%" alt="back" src="images/trans.png" class="btmimg" />
                <div class="topimg">
                    <table align="center">
                    <tr><td>
                    <table>
                        <tr>
                            <td>
                             <h2><a href="stdtest.php">click To Start Test</a></h2>
                                
                            </td>
                            </tr>
                        </table>
                        </td>
                        </tr>
                        <tr>
                        <td>
                        <br/><br/><br/>
                        <table>
                            <tr>
                            <td>
                               <h2><a href="viewresult.php">Click to View Result</a></h2>
                            </td>
                            </tr>
                            </table>
                            <br/><br/><br/>
                            </td>
                            </tr>
                            <tr>
                            <td>
                            <table>
                            <tr>
                            <td>
                                <h2><a href="editprofile.php?edit=edit">Edit Profile</a></h2>
                            </td>
                         </tr>
                    </table>
                    <br/><br/><br/>
                        </td></tr>

                        <tr>
                            <td>
                            <table>
                            <tr>
                            <td>
                                <h2><a href="shownotes.php">Notes</a></h2>
                            </td>
                         </tr>
                    </table>
                        </td></tr>
                    </table>
                </div>
                <?php }?>

            </div>

           <div id="footer">
          <p style="font-size:70%;color:#ffffff;"> Developed By-<b>Pravin Jadhav & Aditya Veera</b><br/> 
      </div>
      </div>
  </body>
</html>
