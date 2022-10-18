<?php
session_start();
include('config/db_connection.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


if(isset($_POST["action"]))
{
  if($_POST["action"] == 'get_subcategory'){
    if(isset($_POST['category_id'])) {
      $sqlQuery = "SELECT * FROM `tbl_subcategories` WHERE `category` = '".$_POST["category_id"]."'";
      $result = mysqli_query($connection, $sqlQuery);	
      while ($row = mysqli_fetch_object($result)) {
        $json[] = array('subcategory_id' => $row->subcategory_id, 'subcategory_name' => $row->subcategory_name, 'category' => $row->category);
      }
      // $row = mysqli_fetch_object($result);
      // echo json_encode($row);
      $data = json_encode($json);
      echo $data; 
    }
  }

  if($_POST["action"] == 'get_product'){
    if(isset($_POST['subcategory_id'])) {
      $sqlQuery = "SELECT * FROM `tbl_product` WHERE `subcategory_id` = '".$_POST["subcategory_id"]."'";
      $result = mysqli_query($connection, $sqlQuery);	
      while ($row = mysqli_fetch_object($result)) {
        $json[] = array('product_id' => $row->product_id, 'product_name' => $row->product_name, 'product_description' => $row->product_description, 'product_image' => $row->product_image, 'unit_price'=>$row->unit_price, 'available_quantity'=>$row->available_quantity, 'subcategory_id'=>$row->subcategory_id, 'medicine_type'=>$row->medicine_type);
      }
      $data = json_encode($json);
      echo $data; 
    }
  }

  if($_POST["action"] == 'get_singleproduct'){
    if(isset($_POST['product_id'])) {
      $sqlQuery = "SELECT * FROM `tbl_product` WHERE `product_id` = '".$_POST["product_id"]."'";
      $result = mysqli_query($connection, $sqlQuery);	
      while ($row = mysqli_fetch_object($result)) {
        $json[] = array('product_id' => $row->product_id, 'product_name' => $row->product_name, 'product_description' => $row->product_description, 'product_image' => $row->product_image, 'unit_price'=>$row->unit_price, 'available_quantity'=>$row->available_quantity, 'subcategory_id'=>$row->subcategory_id, 'medicine_type'=>$row->medicine_type);
      }
      $data = json_encode($json);
      echo $data; 
    }
  }

  if($_POST["action"] == 'addtoCart'){
    $product_id = mysqli_real_escape_string($connection, $_POST['product_id']);
		$product_name = mysqli_real_escape_string($connection, $_POST['product_name']);
		$quantity = mysqli_real_escape_string($connection, $_POST['quantity']);
		$product_price = mysqli_real_escape_string($connection, $_POST['unit_price']);
    $totalprice = $quantity * $product_price;

      // Check if product exists in cart
      $checkProduct = "SELECT `product_id` FROM `tbl_cart` WHERE `product_id` = '$product_id'";
      $checkProduct_execute = mysqli_query($connection, $checkProduct);
  
      if (mysqli_num_rows($checkProduct_execute) > 0) {
        echo "cartFail";
      }
      else {
        $insert_toCart = "INSERT INTO `tbl_cart` (`product_id`, `product_name`, `product_price`, `quantity`, `total_price`) VALUES ('$product_id', '$product_name', '$product_price', '$quantity', '$totalprice')";
        $query_execute = mysqli_query($connection, $insert_toCart);
  
        if ($query_execute) {
          // echo "success";
          echo "success";
        }
        else {
          echo "failed";
        }
      }
  }

  if($_POST["action"] == 'getCartCount'){
    $query = "SELECT * FROM `tbl_cart`";
    $query_execute = mysqli_query($connection, $query);
    $result = mysqli_num_rows($query_execute);
    echo $result;
  }

  if($_POST["action"] == 'changeQty'){
    $row_id = mysqli_real_escape_string($connection, $_POST['row_id']);
		$quantity = mysqli_real_escape_string($connection, $_POST['quantity']);
		$product_price = mysqli_real_escape_string($connection, $_POST['product_price']);
    $totalprice = $quantity * $product_price;

        $update_cart = "UPDATE `tbl_cart` SET `quantity` = '$quantity', `total_price` = '$totalprice' WHERE `id` = '$row_id'";
        $query_execute = mysqli_query($connection, $update_cart);
  
        if ($query_execute) {
          // echo "success";
          echo "success";
        }
        else {
          echo "failed";
        }
  }

  if($_POST["action"] == 'removeItem'){
    $row_id = mysqli_real_escape_string($connection, $_POST['id']);

        $remove_single_item = "DELETE FROM `tbl_cart` WHERE id='$row_id'";
        $query_execute = mysqli_query($connection, $remove_single_item);
  
        if ($query_execute) {
          echo "success";
        }
        else {
          echo "failed";
        } 
  }

  if($_POST["action"] == 'clearCart'){

        $clearCart = "DELETE FROM `tbl_cart`";
        $query_execute = mysqli_query($connection, $clearCart);
  
        if ($query_execute) {
          echo "success";
        }
        else {
          echo "failed";
        } 
  }

  // upload prescription
  if($_POST["action"] == 'uploadPrescription'){
				
    $filename = $_FILES["prescription_image"]["name"];
    $tempname = $_FILES["prescription_image"]["tmp_name"];
    $folder = "prescriptionuploads/" . $filename;
    move_uploaded_file($tempname, $folder);
    
    $user_id = mysqli_real_escape_string($connection, $_POST['user_id']);
    $user_name = mysqli_real_escape_string($connection, $_POST['user_name']);
    $hospital_email = mysqli_real_escape_string($connection, $_POST['hospital_email']);

    
      $insert_prescription = "INSERT INTO `tbl_prescription` (`prescription_image`, `added_by`, `customer_name`, `hospital_email`) VALUES ('$filename', '$user_id', '$user_name', '$hospital_email')";
      $query_execute = mysqli_query($connection, $insert_prescription);

        if ($query_execute) {
        // echo json_encode("success");
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
        $mail->setFrom('isprojectexample@gmail.com', 'myPharma');
        $mail->addAddress($hospital_email); 

        //Content
        $mail->isHTML(true);                                  
        $mail->Subject = 'Prescription Upload';
        $mail->Body    = 'Hello, A patient of name '.$user_name.' uploaded a prescription that was prescribed by a doctor from your hospital. Please verify the prescription for us for further processing. Thank you.';

        $mail->send();
        echo json_encode("Sent");
        }
        else {
          // echo "Failed";
        echo json_encode("Failed");
        }
  }

  if($_POST["action"] == 'placeOrder'){
    $products = mysqli_real_escape_string($connection, $_POST['products']);
    $customer_id = mysqli_real_escape_string($connection, $_POST['customer_id']);
    $total = mysqli_real_escape_string($connection, $_POST['grand_total']);
    $created_at = date('Y-m-d H:i:s');
    $paymenttype = mysqli_real_escape_string($connection, $_POST['paymenttype']);
    $address = mysqli_real_escape_string($connection, $_POST['address']);

    $insert_order = "INSERT INTO `tbl_order` (`customer_id`, `order_amount`, `order_status`, `created_at`, `payment_type`, `delivery_address`) VALUES ('$customer_id', '$total', 'Paid', '$created_at', '$paymenttype', '$address')";
    $query_execute = mysqli_query($connection, $insert_order);

      if ($query_execute) {
        $data = '';
        $id = mysqli_insert_id($connection);
        $getCartItems = "SELECT * FROM `tbl_cart`";
        $getCartItems_execute = mysqli_query($connection, $getCartItems);
        $createdat = date('Y-m-d H:i:s');

        while ($result = mysqli_fetch_assoc($getCartItems_execute)){
          $product_id = $result['product_id'];
          $product = $result['product_name'];
          $product_price = $result['product_price'];
          $quantity = $result['quantity'];
          $totalprice = $result['total_price'];

          $insert_details = "INSERT INTO `tbl_orderdetails` (`order_id`, `product_id`, `product`, `product_price`, `order_quantity`, `orderdetails_total`, `created_at`) VALUES ('$id', '$product_id', '$product', '$product_price', '$quantity', '$totalprice', '$createdat')";
          $query_details = mysqli_query($connection, $insert_details);

          if ($query_details) {
            $emptyCart = "DELETE FROM `tbl_cart`";
            $query_empty = mysqli_query($connection, $emptyCart);
      
          }
          else {
            echo "detailsFailed";  
          }

        }
      $data .= '<div class="text-center">
								<h1 class="display-4 mt-2 text-danger">Thank You!</h1>
								<h1 class="text-success">You will proceed to the payment page!</h1>
								<h4 class="bg-danger text-light rounded p-2">Items Purchased : ' . $products . '</h4>
								<h4>Your Name : ' . $_SESSION['auth_user']['user_name']. '</h4>
								<h4>Your E-mail : ' . $_SESSION['auth_user']['email'] . '</h4>
								<h4>Your Phone : ' . $_SESSION['auth_user']['phoneNo'] . '</h4>
								<h4>Total Amount Paid : Ksh. ' . number_format($total,2) . '</h4>
						  </div>';
      echo $data;

      }
      else {
      echo "orderFailed";
      }
    }

  // Get orders for user view
	if($_POST["action"] == 'getOrders')
	{
    $customer_id = $_SESSION['auth_user']['user_id'];
		$sqlQuery = "SELECT * FROM `tbl_order` WHERE `customer_id` = '$customer_id'";

		if(isset($_POST['order']))
		{
				$column_name = $_POST['order'][0]['column'];
				$order = $_POST['order'][0]['dir'];
				$sqlQuery .= " ORDER BY ".$column_name." ".$order."";
		}
		else
		{
				$sqlQuery .= " ORDER BY order_id asc";
		}
		
		if($_POST['length'] != -1)
		{
				$start = $_POST['start'];
				$length = $_POST['length'];
				$sqlQuery .= " LIMIT  ".$start.", ".$length;
		}   

		$result = mysqli_query($connection, $sqlQuery);
		$numRows = mysqli_num_rows($result);

		$sqlQueryTotal = "SELECT * FROM `tbl_order`";
		$resultTotal = mysqli_query($connection, $sqlQueryTotal);
		$numRowsTotal = mysqli_num_rows($resultTotal);

		$orderData = array();	
		while( $orders = mysqli_fetch_assoc($result) ) {		
			$orderRows = array();			
			$orderRows[] = $orders['order_id'];
			$orderRows[] = $orders['order_amount'];					
			$orderRows[] = $orders['order_status'];					
			$orderRows[] = $orders['payment_type'];					
			$orderRows[] = $orders['delivery_address'];			
      if ($orders['order_status'] == 'Pending Payment') {
        $orderRows[] = '
        <button type="button" name="view" id="'.$orders["order_id"].'" class="btn btn-warning viewBtn"><i class="fas fa-eye"></i></button> <button type="button" name="pay" id="'.$orders["order_id"].'" class="btn btn-info payBtn"><i class="far fa-credit-card"></i></button>';
      }else{
			$orderRows[] = '
			<button type="button" name="view" id="'.$orders["order_id"].'" class="btn btn-warning viewBtn"><i class="fas fa-eye"></i></button>';
    }
			$orderData[] = $orderRows;
		}
		$output = array(
			"draw"	=>	intval($_POST["draw"]),			
			"iTotalRecords"	=> 	$numRows,
			"iTotalDisplayRecords"	=>  $numRowsTotal,
			"data"	=> 	$orderData
		);
		echo json_encode($output);
  }

  // View Order Details
  if($_POST["action"] == 'getOrderDetails'){
    if(isset($_POST['order_id'])) {
      $sqlQuery = "SELECT * FROM `tbl_orderdetails` WHERE `order_id` = '".$_POST["order_id"]."'";
      $result = mysqli_query($connection, $sqlQuery);	
      // $row = mysqli_fetch_array($result);
      while ($row = mysqli_fetch_object($result)) {
        $json[] = array('product_name' => $row->product, 'unit_price' => $row->product_price, 'quantity' => $row->order_quantity, 'total'=>$row->orderdetails_total);
      }
      $data = json_encode($json);
      echo $data; 
    }
  }

  if($_POST["action"] == 'getOrderbyIDforPayment'){
    if(isset($_POST['order_id'])) {
      $sqlQuery = "SELECT * FROM `tbl_order` WHERE `order_id` = '".$_POST["order_id"]."'";
      $result = mysqli_query($connection, $sqlQuery);	
      $row = mysqli_fetch_assoc($result);
      echo json_encode($row);
    }
  }

  // Update Order after payment
  if($_POST["action"] == 'makePayment'){

    $order_id = mysqli_real_escape_string($connection, $_POST['order_id']);
    $address = mysqli_real_escape_string($connection, $_POST['address']);
    $paymenttype = mysqli_real_escape_string($connection, $_POST['paymenttype']);
    $updated_at = date('Y-m-d H:i:s');	
    
    $update_order = "UPDATE `tbl_order` SET `order_status` = 'Paid', `payment_type` = '$paymenttype', `delivery_address` = '$address', `updated_at` = '$updated_at' WHERE `order_id` = '$order_id'";
    $query_execute = mysqli_query($connection, $update_order);

    if ($query_execute) {
      echo "success";
    }
    else {
      echo "Failed";
    }
  }


    // Get orders for user view
	if($_POST["action"] == 'getUserOrderDetails')
	{
    $customer_id = $_SESSION['auth_user']['user_id'];
		$sqlQuery = "SELECT * FROM `tbl_order` WHERE `customer_id` = '$customer_id'";

		if(isset($_POST['order']))
		{
				$column_name = $_POST['order'][0]['column'];
				$order = $_POST['order'][0]['dir'];
				$sqlQuery .= " ORDER BY ".$column_name." ".$order."";
		}
		else
		{
				$sqlQuery .= " ORDER BY order_id asc";
		}
		
		if($_POST['length'] != -1)
		{
				$start = $_POST['start'];
				$length = $_POST['length'];
				$sqlQuery .= " LIMIT  ".$start.", ".$length;
		}   

		$result = mysqli_query($connection, $sqlQuery);
		$numRows = mysqli_num_rows($result);

		$sqlQueryTotal = "SELECT * FROM `tbl_order`";
		$resultTotal = mysqli_query($connection, $sqlQueryTotal);
		$numRowsTotal = mysqli_num_rows($resultTotal);

		$orderData = array();	
		while( $orders = mysqli_fetch_assoc($result) ) {		
			$orderRows = array();			
			$orderRows[] = $orders['order_id'];
			$orderRows[] = $orders['order_amount'];					
			$orderRows[] = $orders['order_status'];					
			$orderRows[] = $orders['payment_type'];					
			$orderRows[] = $orders['delivery_address'];					
			$orderRows[] = '
			<button type="button" name="delete" id="'.$orders["order_id"].'" class="btn btn-warning viewBtn"><i class="fas fa-eye"></i></button>';
			$orderData[] = $orderRows;
		}
		$output = array(
			"draw"	=>	intval($_POST["draw"]),			
			"iTotalRecords"	=> 	$numRows,
			"iTotalDisplayRecords"	=>  $numRowsTotal,
			"data"	=> 	$orderData
		);
		echo json_encode($output);
  }

}

?>