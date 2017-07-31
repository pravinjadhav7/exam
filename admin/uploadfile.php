<?php
include_once 'dbconfig.php';

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
        <title>Upload Content</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="stylesheet" type="text/css" href="../oes.css"/>
        <script type="text/javascript" src="../validate.js" ></script>
    </head>
    <body>
                <?php
                        if(isset($_POST['btn-upload']))
                {    
                     
                    $file = rand(1000,100000)."-".$_FILES['file']['name'];
                    $file_loc = $_FILES['file']['tmp_name'];
                    $file_size = $_FILES['file']['size'];
                    $file_type = $_FILES['file']['type'];
                    $folder="../uploads/";
                    
                    $new_size = $file_size/1024;  
                    
                    $new_file_name = strtolower($file);
                    

                    $final_file=str_replace(' ','-',$new_file_name);
                    
                    if(move_uploaded_file($file_loc,$folder.$final_file))
                    {
                        $sql="INSERT INTO tbl_uploads(file,type,size) VALUES('$final_file','$file_type','$new_size')";
                        mysql_query($sql);
                        ?>
                            <script>
                        alert('successfully uploaded');
                        window.location.href='admwelcome.php?success';
                        </script>     
                        <?php
                    }
                    else
                    {
                        ?>
                        <script>
                        alert('error while uploading file');
                        window.location.href='index.php?fail';
                        </script>
                        <?php
                    }
                }


                    if(isset($_POST['dashboard'])) {
                            header("location:admwelcome.php");
                    }
                    if(isset($_POST['logout'])) {
                            header("location:index.php");
                    }
                ?>
        <div id="container">
            <div class="header">
               <h3 class="headtext"> &nbsp;Exam Material Management System </h3><h4 style="color:#ffffff;text-align:center;margin:0 0 5px 5px;"><i>...because Examination Matters</i></h4>
            </div>
            <form method="post" enctype="multipart/form-data" action="">
                <div class="menubar">
                    <ul id="menu">
                        <li><input type="submit" value="LogOut" name="logout" class="subbtn" title="Log Out"/></li>
                        <li><input type="submit" value="Home" name="dashboard" class="subbtn" title="Dash Board"/></li>
                    </ul>
                </div>
                <div class="page">
                <br/><br/><br/><br/><br/><br/>
                    <table border="1" cellpadding="20" cellspacing="20" style="text-align:left;margin-left:15em">
                        <tr>
                            <td>
                                <input type="file" name="file" />
                            </td>
                        </tr>
                            <td>
                                <button type="submit" name="btn-upload" style="width: 250" class="subbtn" >upload</button>
                            </td>
                        </tr>
                    </table>
<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
                </div>
            </form>
                                <?php
                        if(isset($_GET['success']))
                        {
                            ?>
                            <label>File Uploaded Successfully...</a></label>
                            <?php
                        }
                        else if(isset($_GET['fail']))
                        {
                            ?>
                            <label>Problem While File Uploading !</label>
                            <?php
                        }
                        else
                        {
                            ?>
                            <label>Try to upload any files(PDF, DOC, EXE, VIDEO, MP3, ZIP,etc...)</label>
                            <?php
                        }
                        ?>
            <div id="footer">
                <p style="font-size:70%;color:#ffffff;"> Developed By-<b>Pravin Jadhav & Aditya Veera</b><br/> 
            </div>
        </div>
    </body>
</html>

