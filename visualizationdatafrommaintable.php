<?php

 
 n


 //$dateStart= date('Y-m-d', strtotime($_GET['dateStart'])); 
if (isset($_GET['dateStart'])){
  $dateStart= date('Y-m-d', strtotime($_GET['dateStart'])); 
}else{
     $dateStart = date("Y-m-d");
     $dateStart = "$dateStart 00:00:00	";
}

// $dateEnd= date('Y-m-d', strtotime($_GET['dateEnd']));

 if (isset($_GET['dateEnd'])){
  $dateEnd= date('Y-m-d', strtotime($_GET['dateEnd'])); 
}else{
	$dateEnd  = date("Y-m-d");
     $dateEnd = "$dateEnd 23:59:59	";
}
//end init values for date


//connection to db
$servername = "localhost";
$username = "root";
$password = "#@Pr0ducti0n#123*Kris";
$dbname = "qualitydb";
$mysqli = new mysqli("localhost", $username, $password, $dbname); 
//end init connnection


$mysqli->set_charset("utf8");

if (isset($_GET['subject'])){
  $filterValue1= $_GET['subject']; 
}else{
     $filterValue1 = '*';
}
if (isset($_GET['articleNum'])){
  $filterValue2= $_GET['articleNum']; 
}else{
     $filterValue2 = '*';
}
  $recordPerPage = 20;
  $page = "";
  if (isset($_GET['page']))
  {
    $page = $_GET['page'];
  }
  else
  {
    $page= 1 ;
  }  
  $startFrom = ($page-1) * $recordPerPage;
?>
<html>
 <head>
  <title>Главна таблица</title>
  <META http-equiv="content-type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Language" content="bg">  
  <style>
table {
  border-collapse: collapse;
  width: 100%;
}
th, td {
  text-align: left;
  padding: 4px;
}
tr:nth-child(even) {background-color: #f2f2f2;}
#link1 {
  color: black;
  text-decoration: none; /* no underline */
}
#rowhovver:hover {
  background-color: #B4D5FA ;
}
</style>

 </head>
 <body>

<form name="form" action="" method="get">
<table border=1 cellspacing=10 cellpadding=10 ">
<tr style="background-color:#3577FE;">
  <td style="font-size:20px"><b>Пин номер:<b>
  </td>
  <td>
  <input style="font-size:20px" type="text" name="subject" id="subject" value="<?php if(isset($_GET['subject'])){ echo htmlentities($_GET['subject']);}?>">
  </td>
  <td style="font-size:20px"><b>Артикулен номер:<b>
  </td>
  <td>
  <input style="font-size:20px" type="text" name="articleNum" id="articleNum" <?php echo "value=$filterValue2"?>>
  </td>
  <td>
  <label style="font-size:20px" for="start"><b>Начална дата:<b></label>
  </td>
  <td>
          <input style="font-size:20px" type="date" id="dateStart" name="dateStart" <?php echo "value=$dateStart";?> min="2020-01-01" max="2032-12-31">
  </td>
  <td>
  <label style="font-size:20px" for="end"><b>Крайна дата:<b></label>
  </td>
  <td>
  <input style="font-size:20px" type="date" id="dateEnd" name="dateEnd" <?php echo "value=$dateEnd";?> min="2020-01-01" max="2032-12-31">
  </td>
  <td>
  <input style= style="font-size:20px" type="submit" value="Търси">
  </td>
</tr>

</table>
</form>
 <?php
 //echo "бляблябля";
$i = 1;
// if ($filterValue1 != "*") {
//   $query = "SELECT * FROM maintable WHERE timest >= '$dateStart 00:00:00'AND timest <= '$dateEnd 23:59:59' AND pinNumber = $filterValue1 ";
//   } else {
//     $query = "SELECT * FROM maintable WHERE timest >= '$dateStart 00:00:00'AND timest <= '$dateEnd 23:59:59'";
//   }


  switch ([$filterValue1, $filterValue2]) {
    case ['*', '*']:
      $query = "SELECT * FROM maintable WHERE timest >= '$dateStart 00:00:00'AND timest <= '$dateEnd 23:59:59'";
  
    break;
  
    case [$filterValue1, '*'];
    $query = "SELECT * FROM maintable WHERE timest >= '$dateStart 00:00:00'AND timest <= '$dateEnd 23:59:59' AND pinNumber = $filterValue1 ";
    break;
    
    case ['*', $filterValue2];
    $query = "SELECT * FROM maintable WHERE timest >= '$dateStart 00:00:00'AND timest <= '$dateEnd 23:59:59'  AND projectArticleNumber = $filterValue2 ";
    break;
    
    case [$filterValue1, $filterValue2];
    $query = "SELECT * FROM maintable WHERE timest >= '$dateStart 00:00:00'AND timest <= '$dateEnd 23:59:59' AND pinNumber = $filterValue1 AND projectArticleNumber = $filterValue2 ";
    break;
  
  }
  //mysqli($connecDB,"SET NAMES UTF8");
$result = $mysqli->query($query);
$totalRecords = mysqli_num_rows($result);
$totalPages = ceil($totalRecords/$recordPerPage); 
$startLoop = $page;
$totalRecords = mysqli_num_rows($result);
$totalPages = ceil($totalRecords/$recordPerPage); 
$startLoop = $page;



echo '<table border="1 " cellspacing="5" cellpadding="5"> 
      <tr> 
          <td> <font face="Arial"><b>Номер на запис</b></font> </td> 
          <td> <font face="Arial"><b>Пин номер</b></font> </td> 
          <td> <font face="Arial"><b>Вид грешка</b></font> </td> 
          <td> <font face="Arial"><b>Брой сгрешени</b></font> </td> 
          <td> <font face="Arial"><b>Артикулен номер на проекта</b></font> </td> 
       
          <td> <font face="Arial"><b>ОТК</b></font> </td> 
          <td> <font face="Arial"><b>Брой проверени</b></font> </td> 
          <td> <font face="Arial"><b>Време на записа</b></font> </td> 
      </tr>';





      $objArray = [];
      $objCount = 0;
      $sumEfficiency = 0;
      class note {
        public $id;
        public $pinNumber;
        public $errorKind;
        public $numberWithErrors;
        public $projectArticleNumber;
        public $numberMade;
        public $otkInicials;
        public $workingHours;
        public $numberCheck;
        public $timest;
      
        function __construct($id, $pinNumber, $errorKind, $numberWithErrors, $projectArticleNumber, $numberMade, $otkInicials, $workingHours, $numberCheck, $timest) {
          $this->id = $id;
          $this->pinNumber = $pinNumber;
          $this->errorKind = $errorKind;
          $this->numberWithErrors = $numberWithErrors;
          $this->projectArticleNumber = $projectArticleNumber;
          $this->numberMade = $numberMade;
          $this->otkInicials = $otkInicials;
          $this->workingHours = $workingHours;
          $this->numberCheck = $numberCheck;
          $this->timest = $timest;
        }
      
      } 


      while ($row = $result->fetch_assoc()) {
 
        $objArray[$objCount] = new note ($row["id"], $row["pinNumber"], $row["errorKind"], $row["numberWithErrors"], $row["projectArticleNumber"], $row["numberMade"], $row["otkInicials"], $row["workingHours"], $row["numberCheck"], $row["timest"]);
        $objCount++;


      }
      $pageCount2 = $page*$recordPerPage;
    $pageCount1 = $page*$recordPerPage-$recordPerPage;

    while ($pageCount1 < $pageCount2 and  isset ($objArray[$pageCount1])){     
    
     
      $field1name = $objArray[$pageCount1]->id;
      $field2name = $objArray[$pageCount1]->pinNumber;
      $field3name = $objArray[$pageCount1]->errorKind;
      $field4name = $objArray[$pageCount1]->numberWithErrors;
      $field5name = $objArray[$pageCount1]->projectArticleNumber;
      $field6name = $objArray[$pageCount1]->numberMade;
      $field7name = $objArray[$pageCount1]->workingHours;
      $field8name = $objArray[$pageCount1]->otkInicials;
      $field9name = $objArray[$pageCount1]->numberCheck;
      $field10name = $objArray[$pageCount1]->timest;
    
    
      echo '<tr id="rowhovver"> 
		
      <td>'.$field1name.'</td> 
        <td>'."<a id='link1' href='http://192.168.30.183/project/checkworkername.php?pinNumber=$field2name'>".$field2name."</a>".'</td> 
      <td>'.$field3name.'</td> 
      <td>'.$field4name.'</td> 
      <td>'.$field5name.'</td> 
    
      <td>'.$field8name.'</td> 
      <td>'.$field9name.'</td> 
      <td>'.$field10name.'</td> 
      </tr>';
      $pageCount1++;
    }


    echo '</table>';









?>

<br>
<?php

  echo  "<table>";
  echo "<tr>";
  echo "<td>";
  echo"<center>";
  while($i <= $totalPages){

    echo  "<a href='http://192.168.30.183/project/visualizationdatafrommaintable.php?subject=$filterValue1&articleNum=$filterValue2&dateStart=$dateStart&dateEnd=$dateEnd&page=$i'>$i </a>";
    $i++; 
    }
    echo "</center>";
    echo "</td>";
      echo "</tr>";   
      echo "<tr>";
      echo "<td>";
      echo"<center>";
        echo "<b>Страница:<b>";
        echo $page;
        echo "</center>";
        echo "</td>";
          echo "</tr>";  
    echo"</table>";
?>

<table  border=1 >
    <tr>
    <td style="background-color:#3577FE;">
      <a href='http://192.168.30.183/project/errorform.php'><center><b>Форма за въвеждане на грешки</b><center></a>
  </td>
  </tr>

    <tr>
    <td style="background-color:#3577FE;">
      <a href="http://192.168.30.183/project/checkworkername.php"><center><b>Проверка на служител</b></center></a>
      </td>
  </tr>
  <tr>
    <td style="background-color:#3577FE;">
      <a href="http://192.168.30.183/project/efficiencytable.php"><center><b>Форма за въвеждане на производителност</b></center></a>
      </td>
  </tr>



<a <?php echo"href='http://192.168.30.183/project/export/testexcelmaintable.php?subject=$filterValue1&articleNum=$filterValue2&dateStart=$dateStart&dateEnd=$dateEnd'"?>>Експортиране на данните в Ексел</a>

  </table>

 </body>
</html>
