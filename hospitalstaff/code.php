<?php
session_start();
include('../config/db_connection.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

if(isset($_POST["action"]))
{
	// Get prescriptions
	if($_POST["action"] == 'getPrescriptions')
	{
		$hospital_email = $_SESSION['auth_user']['email'];

		$sqlQuery = "SELECT * FROM `tbl_prescription` WHERE `hospital_email` = '$hospital_email'";

		// if(isset($_POST['search']['value']))
		// {
		// 	$search_value = $_POST['search']['value'];
		// 	$sqlQuery .= " OR customer_name like '%".$search_value."%'";
		// 	$sqlQuery .= " OR hospital_email like '%".$search_value."%'";
		// }

		if(isset($_POST['order']))
		{
				$column_name = $_POST['order'][0]['column'];
				$order = $_POST['order'][0]['dir'];
				$sqlQuery .= " ORDER BY ".$column_name." ".$order."";
		}
		else
		{
				$sqlQuery .= " ORDER BY prescription_id asc";
		}
		
		if($_POST['length'] != -1)
		{
				$start = $_POST['start'];
				$length = $_POST['length'];
				$sqlQuery .= " LIMIT  ".$start.", ".$length;
		}   

		$result = mysqli_query($connection, $sqlQuery);
		$numRows = mysqli_num_rows($result);

		$sqlQueryTotal = "SELECT * FROM `tbl_prescription`";
		$resultTotal = mysqli_query($connection, $sqlQueryTotal);
		$numRowsTotal = mysqli_num_rows($resultTotal);


		$prescriptionData = array();	
		while( $prescriptions = mysqli_fetch_assoc($result) ) {		
			$prescriptionRows = array();			
			$prescriptionRows[] = $prescriptions['prescription_id'];
			$prescriptionRows[] = '<img src="../prescriptionuploads/'.$prescriptions["prescription_image"].'" class="img-fluid img-thumbnail" width="75" height="75" />';					
			$prescriptionRows[] = $prescriptions['customer_name'];					
			$prescriptionRows[] = $prescriptions['hospital_email'];					
			$prescriptionRows[] = $prescriptions['status'];					
			$prescriptionRows[] = '<button name="update" type="button" id="'.$prescriptions["prescription_id"].'" class="btn btn-warning viewBtn"><i class="fas fa-eye"></i></button> <button name="update" type="button" id="'.$prescriptions["prescription_id"].'" class="btn btn-success updateBtn"><i class="fa-solid fa-pen-to-square"></i></button>
			';
			$prescriptionData[] = $prescriptionRows;
		}
		$output = array(
			"draw"	=>	intval($_POST["draw"]),			
			"iTotalRecords"	=> 	$numRows,
			"iTotalDisplayRecords"	=>  $numRowsTotal,
			"data"	=> 	$prescriptionData
		);
		echo json_encode($output);
  }

		// Get prescription by ID for update
		if($_POST["action"] == 'getPrescriptionbyID'){
			if(isset($_POST['prescription_id'])) {
				$sqlQuery = "SELECT * FROM `tbl_prescription` WHERE `prescription_id` = '".$_POST["prescription_id"]."'";
				$result = mysqli_query($connection, $sqlQuery);	
				$row = mysqli_fetch_assoc($result);
				echo json_encode($row);
			}
		}

		// Update Prescription
		if($_POST["action"] == 'editPrescription'){
			
			if(isset($_POST['updatePrescription'])){
				
				$prescriptionId = mysqli_real_escape_string($connection, $_POST['prescription_id']);
				$cname = mysqli_real_escape_string($connection, $_POST['updatecustomer_name']);
				$hemail = mysqli_real_escape_string($connection, $_POST['updatehospitalemail']);
				$status = mysqli_real_escape_string($connection, $_POST['updatestatus']);
				$approvedby = mysqli_real_escape_string($connection, $_POST['updateapprovedby']);
	
						$update_prescription = "UPDATE `tbl_prescription` SET `status` = '$status', `approved_by` = '$approvedby' WHERE `prescription_id` = '$prescriptionId'";
						$query_execute = mysqli_query($connection, $update_prescription);
			
						if ($query_execute) {
							$mail = new PHPMailer(true);

							// $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      
							$mail->isSMTP();                                            
							$mail->Host       = 'smtp.gmail.com';                     
							$mail->SMTPAuth   = true;                                   
							$mail->Username   = ''; //gmail account that will send the email                
							$mail->Password   = ''; // Generate and paste app password here by going under two factor authentication in your google account                               
							$mail->SMTPSecure = 'ssl';            
							$mail->Port       = 465;                                    
			
							//Recipients
							$mail->setFrom($hemail, 'Hospital');
							$mail->addAddress('isprojectexample@gmail.com'); 
			
							//Content
							$mail->isHTML(true);                                  
							$mail->Subject = 'Prescription Verified';
							$mail->Body    = 'Hello, The prescription uploaded by '.$cname.' that was prescribed by a doctor from our hospital has been verified. Thank you.';
			
							$mail->send();
							echo "success";
						}
						else {
							echo "failed";
						}
			}
			else{
				header("Location: prescriptions.php");
				exit(0);
			}
		}

		if($_POST["action"] == 'getPrescriptionforView'){
			
			if(isset($_POST['prescription_id'])) {
				$sqlQuery = "SELECT `prescription_image` FROM `tbl_prescription` WHERE `prescription_id` = '".$_POST["prescription_id"]."'";
				$result = mysqli_query($connection, $sqlQuery);	
				$row = mysqli_fetch_assoc($result);
				echo json_encode($row);
			}
		}
}

?>