<?php
    include ('../database/config.php');

    $id = $_POST['id'];

    $sqlexamsubjects = "SELECT * FROM `staffsignature` WHERE staff_id= '$id'";
    $resultexamsubjects = mysqli_query($link, $sqlexamsubjects);
    $rowexamsubjects = mysqli_fetch_assoc($resultexamsubjects);
    $row_cntexamsubjects = mysqli_num_rows($resultexamsubjects);

    if($row_cntexamsubjects > 0)
    {
            // Get the full request URI
            $requestUri = $_SERVER['REQUEST_URI'];

            // Remove the leading `/` if needed and remove query parameters if present
            $path = parse_url($requestUri, PHP_URL_PATH);

            // Display the result
            // echo $path;  // Output: /admin/settingTeacherDefaultComments.php
            if ($path == "admin/settingTeacherDefaultComments.php")
            {
              echo'<div align="center" style="padding:5%;">
                      <img src="../img/signature/'.$rowexamsubjects['Signature'].'" width="20%" height="auto"/>
                  </div>';
            } else {
              echo $path;
              echo'<div align="center" style="padding:5%;">
                      <img src="'. 'https://schoollift.s3.us-east-2.amazonaws.com/'. $rowexamsubjects['Signature'].'" width="20%" height="auto"/>
                  </div>';
            }


    }
    else
    {
        echo 'No Signature has been uploaded';
    }

?>
