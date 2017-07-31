

 <?php
      error_reporting(0);
      session_start();
      include_once '../oesdb.php';

      if(isset($_REQUEST['admsubmit']))
      {
          
          $result=executeQuery("select * from adminlogin where admname='".$_REQUEST['name']."' and admpassword='".md5($_REQUEST['password'])."'");
        
         // $result=mysql_query("select * from adminlogin where admname='".htmlspecialchars($_REQUEST['name'])."' and admpassword='".md5(htmlspecialchars($_REQUEST['password']))."'");
          if(mysql_num_rows($result)>0)
          {
              
              $r=mysql_fetch_array($result);
              if(strcmp($r['admpassword'],md5($_REQUEST['password']))==0)
              {
                  $_SESSION['admname']=$r['admname'];
                  unset($_GLOBALS['message']);
                  header('Location: admwelcome.php');
              }else
          {
             $_GLOBALS['message']="Check Your user name and Password.";
                 
          }

          }
          else
          {
              $_GLOBALS['message']="Check Your user name and Password.";
              
          }
          closedb();
      }
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
    <title>Administrator Login</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" type="text/css" href="../oes.css"/>
  </head>
  <body>

      <?php
      
        if(isset($_GLOBALS['message']))
        {
         echo "<div class=\"message\">".$_GLOBALS['message']."</div>";
        }
      ?>
      <div id="container">
                <div class="header">
                <h3 class="headtext"> &nbsp;Exam Material Management System </h3><h4 style="color:#ffffff;text-align:center;margin:0 0 5px 5px;"><i>...because Examination Matters</i></h4>
            </div>
      <div class="menubar">
        &nbsp;
      </div>
      <div class="page">
       <br/><br/><br/><br/><br/><br/><br/><br/>
              <form id="indexform" action="index.php" method="post">
              <table cellpadding="30" cellspacing="10">
              <tr>
                  <td>Admin Name</td>
                  <td><input type="text" name="name" value="" size="16" /></td>

              </tr>
              <tr>
                  <td> Password</td>
                  <td><input type="password" name="password" value="" size="16" /></td>
              </tr>

              <tr>
                  <td colspan="2">
                      <input type="submit" value="Log In" name="admsubmit" class="subbtn" />
                  </td><td></td>
              </tr>
            </table>

        </form>
<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
      </div>

      <div id="footer">
          <p style="font-size:70%;color:#ffffff;"> Developed By-<b>Pravin Jadhav & Aditya Veera</b>
      </div>
      </div>
  </body>
</html>
