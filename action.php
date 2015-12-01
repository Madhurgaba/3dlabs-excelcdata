<?php
 
ini_set("display_errors",1);
include 'excel_reader2.php';
require_once 'config.php';
$message = null;

$allowed_extensions = array('xls');

$upload_path = 'excel';

if (!empty($_FILES['file'])) {

	if ($_FILES['file']['error'] == 0) {
			
		// check extension
		$file = explode(".", $_FILES['file']['name']);
		$file_name = $_FILES['file']['name'];
		$extension = array_pop($file);
		
		if (in_array($extension, $allowed_extensions)) {
	
			if (move_uploaded_file($_FILES['file']['tmp_name'], $upload_path.'/'.$_FILES['file']['name'])) {
				 
 
				$data = new Spreadsheet_Excel_Reader($file_name);
				 
				echo "Total Sheets in this xls file: ".count($data->sheets)."<br /><br />";
				 
				$html="<table border='1'>";
				for($i=0;$i<count($data->sheets);$i++) // Loop to get all sheets in a file.
				{	
					if(count($data->sheets[$i]['cells'])>0) // checking sheet not empty
					{
						echo "Sheet $i:<br /><br />Total rows in sheet $i  ".count($data->sheets[$i]['cells'])."<br />";
						for($j=1;$j<=count($data->sheets[$i]['cells']);$j++) // loop used to get each row of the sheet
						{ 
							$html.="<tr>";
							for($k=1;$k<=count($data->sheets[$i]['cells'][$j]);$k++) // This loop is created to get data in a table format.
							{
								$html.="<td>";
								if(isset($data->sheets[$i]['cells'][$j][$k])){
								$html.=$data->sheets[$i]['cells'][$j][$k];	
								}else{
									$html.= 'NULL'; 
								}
								$html.="</td>";
							}
							$eid = mysqli_real_escape_string($connection,$data->sheets[$i]['cells'][$j][1]);
							$name = mysqli_real_escape_string($connection,$data->sheets[$i]['cells'][$j][2]);
							$email = mysqli_real_escape_string($connection,$data->sheets[$i]['cells'][$j][3]);
							$dob = mysqli_real_escape_string($connection,$data->sheets[$i]['cells'][$j][4]);
							$query = "insert into test (eid,name,email,dob) values('".$eid."','".$name."','".$email."','".$dob."')";
				 
							mysqli_query($connection,$query);
							$html.="</tr>";
						}
					}
				 
				}
				 
				$html.="</table>";
				echo $html;
				echo "<br />Data Inserted in dababase";
				   		$message = '<span class="green">File has been uploaded successfully</span>';	
				
			}
			
		} else {
			$message = '<span class="red">Only .xls file format is allowed</span>';
		}
		
		
	} else {
		$message = '<span class="red">There was a problem with your file</span>';
	}
	
}
?>