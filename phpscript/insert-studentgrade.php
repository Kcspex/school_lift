<?php 
include ('../database/config.php');

$classid = explode(',',$_POST['classid']);

$GradingTitleid = $_POST['GradingTitleid'];

$sqltosel = mysqli_query($link,"SELECT * FROM `assigngradingtclass` WHERE GradingTitle = '$GradingTitleid'");
$count = mysqli_num_rows($sqltosel);

if($count > 0){
    
    $sql = "DELETE FROM `assigngradingtclass` WHERE GradingTitle = '$GradingTitleid'";
    
    if(mysqli_query($link,$sql))
    {
        
        foreach($classid as $classidNew){
            $classidNew;
            
            $sqlInsert =("INSERT INTO `assigngradingtclass`(`GradingTitle`, `ClassID`) VALUES ('$GradingTitleid','$classidNew')");
            
            if(mysqli_query($link,$sqlInsert))
            {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Successfully updated<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button></div>';
            }
            else
            {
                echo "Error: " . $sqlInsert . "<br>" . mysqli_error($link);
            }
        }
            
    }
    else
    {
        echo "Error: " . $sql . "<br>" . mysqli_error($link);
    }
}
else
{
	foreach($classid as $classidNew){
        $classidNew;
        
        $sqlInsert =("INSERT INTO `assigngradingtclass`(`GradingTitle`, `ClassID`) VALUES ('$GradingTitleid','$classidNew')");
        
        if(mysqli_query($link,$sqlInsert))
        {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Successfully updated<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button></div>';
        }
        else
        {
            echo "Error: " . $sqlInsert . "<br>" . mysqli_error($link);
        }
    }
}

?>