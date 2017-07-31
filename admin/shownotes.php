<?php
include_once 'oesdb.php';
?>
<html>
    <head>
        <title>Upload Content</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="stylesheet" type="text/css" href="../oes.css"/>
        <script type="text/javascript" src="../validate.js" ></script>
    </head>
    <body>
                
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
                        <table width="80%" border="1">
                        <tr>
                        <th colspan="4">your uploads...<label><a href="index.php">upload new files...</a></label></th>
                        </tr>
                        <tr>
                        <td>File Name</td>
                        <td>File Type</td>
                        <td>File Size(KB)</td>
                        <td>View</td>
                        </tr>
                        <?php
                        $sql="SELECT * FROM tbl_uploads";
                        $result_set=mysql_query($sql);
                        while($row=mysql_fetch_array($result_set))
                        {
                            ?>
                            <tr>
                            <td><?php echo $row['file'] ?></td>
                            <td><?php echo $row['type'] ?></td>
                            <td><?php echo $row['size'] ?></td>
                            <td><a href="uploads/<?php echo $row['file'] ?>" target="_blank">view file</a></td>
                            </tr>
                            <?php
                        }
                        ?>
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

