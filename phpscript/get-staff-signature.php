<?php
    include ('../database/config.php');
    
    $id = $_POST['id'];
    
    $sqlexamsubjects = "SELECT * FROM `staffsignature` WHERE staff_id= '$id'";
    $resultexamsubjects = mysqli_query($link, $sqlexamsubjects);
    $rowexamsubjects = mysqli_fetch_assoc($resultexamsubjects);
    $row_cntexamsubjects = mysqli_num_rows($resultexamsubjects);

    if($row_cntexamsubjects > 0)
    {
        
            echo'<div align="center" style="padding:5%;">
                    <img src="../img/signature/'.$rowexamsubjects['Signature'].'" width="20%" height="auto"/>
                </div>';
            
    }
    else
    {
        echo 'No Signature has been uploaded';
    }

?>
