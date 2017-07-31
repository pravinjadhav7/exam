<?php
error_reporting(0);
session_start();
include_once 'oesdb.php';
$final=false;
if(!isset($_SESSION['stdname'])) {
    $_GLOBALS['message']="Session Timeout.Click here to <a href=\"index.php\">Re-LogIn</a>";
}
else if(isset($_REQUEST['logout']))
{
       unset($_SESSION['stdname']);
       header('Location: index.php');

}
else if(isset($_REQUEST['dashboard'])){
     header('Location: stdwelcome.php');

    }else if(isset($_REQUEST['next']) || isset($_REQUEST['summary']) || isset($_REQUEST['viewsummary']))
    {
        $answer='unanswered';
        if(time()<strtotime($_SESSION['endtime']))
        {
            if(isset($_REQUEST['markreview']))
            {
                $answer='review';
            }
            else if(isset($_REQUEST['answer']))
            {
                $answer='answered';
            }
            else
            {
                $answer='unanswered';
            }
            if(strcmp($answer,"unanswered")!=0)
            {
                if(strcmp($answer,"answered")==0)
                {
                    $query="update studentquestion set answered='answered',stdanswer='". $_REQUEST['answer'] ."' where stdid=".$_SESSION['stdid']." and testid=".$_SESSION['testid']." and qnid=".$_SESSION['qn'].";";
                }
                else
                {
                    $query="update studentquestion set answered='review',stdanswer='". $_REQUEST['answer'] ."' where stdid=".$_SESSION['stdid']." and testid=".$_SESSION['testid']." and qnid=".$_SESSION['qn'].";";
                }
                if(!executeQuery($query))
                {
                
                $_GLOBALS['message']="Your previous answer is not updated.Please answer once again";
                }
                closedb();
            }
            if(isset($_REQUEST['viewsummary']))
            {
                 header('Location: summary.php');
            }
            if(isset($_REQUEST['summary']))
             {
                     
                     header('Location: summary.php');
             }
        }
        if((int)$_SESSION['qn']<(int)$_SESSION['tqn'])
        {
        $_SESSION['qn']=$_SESSION['qn']+1;
       
        }
        if((int)$_SESSION['qn']==(int)$_SESSION['tqn'])
        {
           $final=true;
        }

    }
    else if(isset($_REQUEST['previous']))
    {
        $answer='unanswered';
        if(time()<strtotime($_SESSION['endtime']))
        {
            if(isset($_REQUEST['markreview']))
            {
                $answer='review';
            }
            else if(isset($_REQUEST['answer']))
            {
                $answer='answered';
            }
            else
            {
                $answer='unanswered';
            }
            if(strcmp($answer,"unanswered")!=0)
            {
                if(strcmp($answer,"answered")==0)
                {
                    $query="update studentquestion set answered='answered',stdanswer='". $_REQUEST['answer'] ."' where stdid=".$_SESSION['stdid']." and testid=".$_SESSION['testid']." and qnid=".$_SESSION['qn'].";";
                }
                else
                {
                    $query="update studentquestion set answered='review',stdanswer='". $_REQUEST['answer'] ."' where stdid=".$_SESSION['stdid']." and testid=".$_SESSION['testid']." and qnid=".$_SESSION['qn'].";";
                }
                if(!executeQuery($query))
                {
                // to do
                $_GLOBALS['message']="Your previous answer is not updated.Please answer once again";
                }
                closedb();
            }
        }
        if((int)$_SESSION['qn']>1)
        {
            $_SESSION['qn']=$_SESSION['qn']-1;
        }

    }
    else if(isset($_REQUEST['fs']))
    {
        header('Location: testack.php');
    }
?>
<?php
header("Cache-Control: no-cache, must-revalidate");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
    <title>Test Conducter</title>
    <link rel="stylesheet" type="text/css" href="oes.css"/>
    <script type="text/javascript" src="validate.js" ></script>
    <script type="text/javascript" src="cdtimer.js" ></script>
    <script type="text/javascript" >
        <?php
                $elapsed=time()-strtotime($_SESSION['starttime']);
                if(((int)$elapsed/60)<(int)$_SESSION['duration'])
                {
                    $result=executeQuery("select TIME_FORMAT(TIMEDIFF(endtime,CURRENT_TIMESTAMP),'%H') as hour,TIME_FORMAT(TIMEDIFF(endtime,CURRENT_TIMESTAMP),'%i') as min,TIME_FORMAT(TIMEDIFF(endtime,CURRENT_TIMESTAMP),'%s') as sec from studenttest where stdid=".$_SESSION['stdid']." and testid=".$_SESSION['testid'].";");
                    if($rslt=mysql_fetch_array($result))
                    {
                     echo "var hour=".$rslt['hour'].";";
                     echo "var min=".$rslt['min'].";";
                     echo "var sec=".$rslt['sec'].";";
                    }
                    else
                    {
                        $_GLOBALS['message']="Try Again";
                    }
                    closedb();
                }
                else
                {
                    echo "var sec=01;var min=00;var hour=00;";
                }
        ?>
        
    </script>

    </head>
  <body >
      <?php

        if($_GLOBALS['message']) {
            echo "<div class=\"message\">".$_GLOBALS['message']."</div>";
        }
        ?>
      <div id="container">
      <div class="header">
                <h3 class="headtext"> &nbsp;Exam Material Management System  </h3><h4 style="color:#ffffff;text-align:center;margin:0 0 5px 5px;"><i>...Because Exams Matter</i></h4>
            </div>
           <form id="testconducter" action="testconducter.php" method="post">
          <div class="menubar" style="text-align:center;">
              <h2 style="font-family:helvetica,sans-serif;font-weight:bolder;font-size:120%;color:#f50000;padding-top:0.3em;letter-spacing:1px;">Test Conducter</h2>
          </div>
      <div class="page">
          <?php
         
          if(isset($_SESSION['stdname']))
          {
                $result=executeQuery("select stdanswer,answered from studentquestion where stdid=".$_SESSION['stdid']." and testid=".$_SESSION['testid']." and qnid=".$_SESSION['qn'].";");
                $r1=mysql_fetch_array($result);
                $result=executeQuery("select * from question where testid=".$_SESSION['testid']." and qnid=".$_SESSION['qn'].";");
                $r=mysql_fetch_array($result);
          ?>
          <div class="tc">

              <table border="0" width="100%" class="ntab">
                  <tr>
                      <th style="width:40%;"><h3><span id="timer" class="timerclass"></span></h3></th>
                      <th style="width:40%;"><h4 style="color: #af0a36;">Question No: <?php echo $_SESSION['qn']; ?> </h4></th>
                      
                  </tr>
              </table>
             <textarea cols="100" rows="8" name="question" readonly style="width:96.8%;text-align:left;margin-left:2%;margin-top:2px;font-size:120%;font-weight:bold;margin-bottom:0;color:#0000ff;padding:2px 2px 2px 2px;"><?php echo  $r['question'] ; ?></textarea>
              <table border="0" width="100%" class="ntab">
                  <tr><td>&nbsp;</td></tr>
                  <tr><td >1. 
                  <input type="radio" name="answer" value="optiona" <?php if((strcmp( $r1['answered'] ,"review")==0 ||strcmp( $r1['answered'] ,"answered")==0)&& strcmp( $r1['stdanswer'] ,"optiona")==0 ){echo "checked";} ?>> <?php echo  $r['optiona'] ; ?>
                  </input>
                  </td></tr>
                  <tr><td >2. 
                  <input type="radio" name="answer" value="optionb" <?php if((strcmp( $r1['answered'] ,"review")==0 ||strcmp( $r1['answered'] ,"answered")==0)&& strcmp( $r1['stdanswer'] ,"optionb")==0 ){echo "checked";} ?>> <?php echo  $r['optionb'] ; ?>
                  </input>
                  </td></tr>
                  <tr><td >3. 
                  <input type="radio" name="answer" value="optionc" <?php if((strcmp( $r1['answered'] ,"review")==0 ||strcmp( $r1['answered'] ,"answered")==0)&& strcmp( $r1['stdanswer'] ,"optionc")==0 ){echo "checked";} ?>> <?php echo  $r['optionc'] ; ?>
                  </input>
                  </td></tr>
                  <tr><td >4. 
                  <input type="radio" name="answer" value="optiond" <?php if((strcmp( $r1['answered'] ,"review")==0 ||strcmp( $r1['answered'] ,"answered")==0)&& strcmp( $r1['stdanswer'] ,"optiond")==0 ){echo "checked";} ?>> <?php echo  $r['optiond'] ; ?>
                  </input>
                  </td></tr>
                  <tr><td>&nbsp;</td></tr>
                  
                  <tr>
                      <th style="width:80%;"><h4><input type="submit" name="<?php if($final==true){ echo "viewsummary" ;}else{ echo "next";} ?>" value="<?php if($final==true){ echo "View Summary" ;}else{ echo "Next";} ?>" class="subbtn"/></h4></th>
                      <th style="width:12%;text-align:right;"><h4><input type="submit" name="previous" value="Previous" class="subbtn"/></h4></th>
                  </tr>
                  
              </table>
              

          </div>
          <?php
          closedb();
          }
          ?>
      </div>

           </form>
     <div id="footer">
          <p style="font-size:70%;color:#ffffff;"> Developed By-<b>Pravin Jadhav & Aditya Veera</b></p>
      </div>
      </div>
  </body>
</html>

