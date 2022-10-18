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
	// Get all users
	if($_POST["action"] == 'getUsers')
	{
		$sqlQuery = "SELECT * FROM `tbl_users`";

		if(isset($_POST['search']['value']))
		{
			$search_value = $_POST['search']['value'];
			$sqlQuery .= " WHERE first_name like '%".$search_value."%'";
			$sqlQuery .= " OR last_name like '%".$search_value."%'";
			$sqlQuery .= " OR email like '%".$search_value."%'";
			$sqlQuery .= " OR gender like '%".$search_value."%'";
		}

		if(isset($_POST['order']))
		{
				$column_name = $_POST['order'][0]['column'];
				$order = $_POST['order'][0]['dir'];
				$sqlQuery .= " ORDER BY ".$column_name." ".$order."";
		}
		else
		{
				$sqlQuery .= " ORDER BY user_id asc";
		}
		
		if($_POST['length'] != -1)
		{
				$start = $_POST['start'];
				$length = $_POST['length'];
				$sqlQuery .= " LIMIT  ".$start.", ".$length;
		}   

		$result = mysqli_query($connection, $sqlQuery);
		$numRows = mysqli_num_rows($result);

		$sqlQueryTotal = "SELECT * FROM `tbl_users`";
		$resultTotal = mysqli_query($connection, $sqlQueryTotal);
		$numRowsTotal = mysqli_num_rows($resultTotal);

		$userData = array();	
		while( $users = mysqli_fetch_assoc($result) ) {		
			$userRows = array();			
			$userRows[] = $users['user_id'];
			$userRows[] = $users['first_name'];					
			$userRows[] = $users['last_name'];					
			$userRows[] = $users['phoneNo'];					
			$userRows[] = $users['email'];	
			$userRows[] = $users['gender'];					
			$userRows[] = $users['dob'];					
			$userRows[] = $users['role'];					
			$userRows[] = '<button name="update" type="button" id="'.$users["user_id"].'" class="btn btn-success updateBtn"><i class="fa-solid fa-pen-to-square"></i></button>
			<button type="button" name="delete" id="'.$users["user_id"].'" class="btn btn-danger deleteBtn"><i class="fa-solid fa-trash-can"></i></button>';
			$userData[] = $userRows;
		}
		$output = array(
			"draw"	=>	intval($_POST["draw"]),			
			"iTotalRecords"	=> 	$numRows,
			"iTotalDisplayRecords"	=>  $numRowsTotal,
			"data"	=> 	$userData
		);
		echo json_encode($output);
  }

	if($_POST["action"] == 'addUser'){

		if(isset($_POST['saveUser'])){
			$fname = mysqli_real_escape_string($connection, $_POST['first_name']);
			$lname =mysqli_real_escape_string($connection, $_POST['last_name']);
			$phoneNo = mysqli_real_escape_string($connection, $_POST['phoneNo']);
			$email = mysqli_real_escape_string($connection, $_POST['email']);
			$gender = mysqli_real_escape_string($connection, $_POST['gender']);
			$password = mysqli_real_escape_string($connection, $_POST['password']);
			$hashed_password = password_hash($password, PASSWORD_DEFAULT);
			$dob = mysqli_real_escape_string($connection, $_POST['dob']);
			$role = mysqli_real_escape_string($connection, $_POST['role']);

				
				// Check if user with same email exists
				$checkEmail = "SELECT `email` FROM `tbl_users` WHERE `email` = '$email'";
				$checkEmail_run = mysqli_query($connection, $checkEmail);
		
				if (mysqli_num_rows($checkEmail_run) > 0) {
					echo "emailFail";
				}
				else {
					$insert_user = "INSERT INTO `tbl_users` (`first_name`, `last_name`, `phoneNo`, `email`, `gender`, `password`, `dob`, `role`) VALUES ('$fname','$lname', '$phoneNo', '$email', '$gender', '$hashed_password', '$dob', '$role')";
					$query_execute = mysqli_query($connection, $insert_user);
		
					if ($query_execute) {
						echo "success";
					}
					else {
						echo "Failed";
					}
				}

		}
		else{
			header("Location: registeredusers.php");
			exit(0);
		}
  }

		// Get user by ID for update
		if($_POST["action"] == 'getUserbyID'){
			if(isset($_POST['user_id'])) {
				$sqlQuery = "SELECT * FROM `tbl_users` WHERE `user_id` = '".$_POST["user_id"]."'";
				$result = mysqli_query($connection, $sqlQuery);	
				$row = mysqli_fetch_assoc($result);
				echo json_encode($row);
			}
		}

		// Update User
		if($_POST["action"] == 'editUser'){
			
			if(isset($_POST['updateUser'])){
	
				$userId = mysqli_real_escape_string($connection, $_POST['user_id']);
				$fname = mysqli_real_escape_string($connection, $_POST['updatefirst_name']);
				$lname =mysqli_real_escape_string($connection, $_POST['updatelast_name']);
				$phoneNo = mysqli_real_escape_string($connection, $_POST['updatephoneNo']);
				$email = mysqli_real_escape_string($connection, $_POST['updateemail']);
				$gender = mysqli_real_escape_string($connection, $_POST['updategender']);
				$dob = mysqli_real_escape_string($connection, $_POST['updatedob']);
				$role = mysqli_real_escape_string($connection, $_POST['updaterole']);
	
						$update_user = "UPDATE `tbl_users` SET `first_name`='$fname', `last_name` = '$lname', `phoneNo` = '$phoneNo', `email` = '$email', `gender` = '$gender', `dob` = '$dob', `role` = '$role'  WHERE `user_id` = '$userId'";
						$query_execute = mysqli_query($connection, $update_user);
			
						if ($query_execute) {
							echo "success";
						}
						else {
							echo "failed";
						}
			}
			else{
				header("Location: registeredusers.php");
				exit(0);
			}
		}

	// Delete Users
	if($_POST["action"] == 'deleteUser'){
		if($_POST["user_id"]) {
			$sqlDelete = "DELETE FROM `tbl_users` WHERE `user_id` = '".$_POST["user_id"]."'";		
			mysqli_query($connection, $sqlDelete);		
		}
	}

	// Get all categories
	if($_POST["action"] == 'getCategories')
	{
		$sqlQuery = "SELECT * FROM `tbl_categories`";

		if(isset($_POST['search']['value']))
		{
			$search_value = $_POST['search']['value'];
			$sqlQuery .= " WHERE category_name like '%".$search_value."%'";
		}

		if(isset($_POST['order']))
		{
				$column_name = $_POST['order'][0]['column'];
				$order = $_POST['order'][0]['dir'];
				$sqlQuery .= " ORDER BY ".$column_name." ".$order."";
		}
		else
		{
				$sqlQuery .= " ORDER BY category_id asc";
		}
		
		if($_POST['length'] != -1)
		{
				$start = $_POST['start'];
				$length = $_POST['length'];
				$sqlQuery .= " LIMIT  ".$start.", ".$length;
		}   

		$result = mysqli_query($connection, $sqlQuery);
		$numRows = mysqli_num_rows($result);

		$sqlQueryTotal = "SELECT * FROM `tbl_categories`";
		$resultTotal = mysqli_query($connection, $sqlQueryTotal);
		$numRowsTotal = mysqli_num_rows($resultTotal);

		$categoryData = array();	
		while( $category = mysqli_fetch_assoc($result) ) {		
			$catRows = array();			
			$catRows[] = $category['category_id'];
			$catRows[] = $category['category_name'];					
			$catRows[] = '<button name="update" type="button" id="'.$category["category_id"].'" class="btn btn-success updateBtn"><i class="fa-solid fa-pen-to-square"></i></button>
			<button type="button" name="delete" id="'.$category["category_id"].'" class="btn btn-danger deleteBtn"><i class="fa-solid fa-trash-can"></i></button>';
			$categoryData[] = $catRows;
		}
		$output = array(
			"draw"	=>	intval($_POST["draw"]),			
			"iTotalRecords"	=> 	$numRows,
			"iTotalDisplayRecords"	=>  $numRowsTotal,
			"data"	=> 	$categoryData
		);
		echo json_encode($output);
  }

	// Add category
	if($_POST["action"] == 'addCategory'){

		if(isset($_POST['saveCategory'])){

			$categoryName = mysqli_real_escape_string($connection, $_POST['category_name']);
		
				// Check if category with same name exists
				$checkCategory = "SELECT `category_name` FROM `tbl_categories` WHERE `category_name` = '$categoryName'";
				$checkCategory_execute = mysqli_query($connection, $checkCategory);
		
				if (mysqli_num_rows($checkCategory_execute) > 0) {
					echo "categoryFail";
				}
				else {
					$insert_category = "INSERT INTO `tbl_categories` (`category_name`) VALUES ('$categoryName')";
					$query_execute = mysqli_query($connection, $insert_category);
		
					if ($query_execute) {
						echo "success";
					}
					else {
						echo "failed";
					}
				}
		}
		else{
			header("Location: categories.php");
			exit(0);	
	  }
  }

	// Get category by ID for update
	if($_POST["action"] == 'getCatbyID'){
		if(isset($_POST['category_id'])) {
			$sqlQuery = "SELECT * FROM `tbl_categories` WHERE `category_id` = '".$_POST["category_id"]."'";
			$result = mysqli_query($connection, $sqlQuery);	
			$row = mysqli_fetch_assoc($result);
			echo json_encode($row);
		}
	}
	
	// Update Category
	if($_POST["action"] == 'editCategory'){
		if(isset($_POST['updateCategory'])){

			$categoryId = mysqli_real_escape_string($connection, $_POST['updatecategory_id']);
			$categoryName = mysqli_real_escape_string($connection, $_POST['updatecategory_name']);
		
					$update_category = "UPDATE `tbl_categories` SET `category_name`='$categoryName' WHERE `category_id` = '$categoryId'";
					$query_execute = mysqli_query($connection, $update_category);
		
					if ($query_execute) {
						echo "success";
					}
					else {
						echo "failed";
					}
		}
		else{
			header("Location: categories.php");
			exit(0);
		}
	}

	// Delete Category
	if($_POST["action"] == 'deleteCat'){
		if($_POST["category_id"]) {
			$sqlDelete = "DELETE FROM `tbl_categories` WHERE `category_id` = '".$_POST["category_id"]."'";		
			mysqli_query($connection, $sqlDelete);		
		}
	}

		// Get all sub categories
		if($_POST["action"] == 'getSubCategories')
		{
			$sqlQuery = "SELECT * FROM `tbl_subcategories`";
	
			if(isset($_POST['search']['value']))
			{
				$search_value = $_POST['search']['value'];
				$sqlQuery .= " WHERE subcategory_name like '%".$search_value."%'";
				$sqlQuery .= " OR category like '%".$search_value."%'";

			}
	
			if(isset($_POST['order']))
			{
					$column_name = $_POST['order'][0]['column'];
					$order = $_POST['order'][0]['dir'];
					$sqlQuery .= " ORDER BY ".$column_name." ".$order."";
			}
			else
			{
					$sqlQuery .= " ORDER BY subcategory_id asc";
			}
			
			if($_POST['length'] != -1)
			{
					$start = $_POST['start'];
					$length = $_POST['length'];
					$sqlQuery .= " LIMIT  ".$start.", ".$length;
			}   
	
			$result = mysqli_query($connection, $sqlQuery);
			$numRows = mysqli_num_rows($result);
	
			$sqlQueryTotal = "SELECT * FROM `tbl_subcategories`";
			$resultTotal = mysqli_query($connection, $sqlQueryTotal);
			$numRowsTotal = mysqli_num_rows($resultTotal);
	
			$subcategoryData = array();	
			while( $subcategory = mysqli_fetch_assoc($result) ) {		
				$subcatRows = array();			
				$subcatRows[] = $subcategory['subcategory_id'];
				$subcatRows[] = $subcategory['subcategory_name'];					
				$subcatRows[] = $subcategory['category'];					
				$subcatRows[] = '<button name="update" type="button" id="'.$subcategory["subcategory_id"].'" class="btn btn-success updateBtn"><i class="fa-solid fa-pen-to-square"></i></button>
				<button type="button" name="delete" id="'.$subcategory["subcategory_id"].'" class="btn btn-danger deleteBtn"><i class="fa-solid fa-trash-can"></i></button>';
				$subcategoryData[] = $subcatRows;
			}
			$output = array(
				"draw"	=>	intval($_POST["draw"]),			
				"iTotalRecords"	=> 	$numRows,
				"iTotalDisplayRecords"	=>  $numRowsTotal,
				"data"	=> 	$subcategoryData
			);
			echo json_encode($output);
		}

	// Add sub category
	if($_POST["action"] == 'addSubCategory'){

		if(isset($_POST['saveSubCategory'])){

			$subcategoryName = mysqli_real_escape_string($connection, $_POST['subcategory_name']);
			$category = mysqli_real_escape_string($connection, $_POST['category']);

		
				// Check if category with same name exists
				$checkSubCategory = "SELECT `subcategory_name` FROM `tbl_subcategories` WHERE `subcategory_name` = '$subcategoryName'";
				$checkSubCategory_execute = mysqli_query($connection, $checkSubCategory);
		
				if (mysqli_num_rows($checkSubCategory_execute) > 0) {
					echo "subcategoryFail";
				}
				else {
					$insert_subcategory = "INSERT INTO `tbl_subcategories` (`subcategory_name`, `category`) VALUES ('$subcategoryName', '$category')";
					$query_execute = mysqli_query($connection, $insert_subcategory);
		
					if ($query_execute) {
						echo "success";
					}
					else {
						echo "failed";
					}
				}
		}
		else{
			header("Location: subcategories.php");
			exit(0);	
	  }
  }

		// Get sub category by ID for update
		if($_POST["action"] == 'getSubCatbyID'){
			if(isset($_POST['subcategory_id'])) {
				$sqlQuery = "SELECT * FROM `tbl_subcategories` WHERE `subcategory_id` = '".$_POST["subcategory_id"]."'";
				$result = mysqli_query($connection, $sqlQuery);	
				$row = mysqli_fetch_assoc($result);
				echo json_encode($row);
			}
		}

	// Update Sub Category
	if($_POST["action"] == 'editSubCategory'){
		if(isset($_POST['updateSubCategory'])){

			$subcategoryId = mysqli_real_escape_string($connection, $_POST['updatesubcategory_id']);
			$subcategoryName = mysqli_real_escape_string($connection, $_POST['updatesubcategory_name']);
			$category = mysqli_real_escape_string($connection, $_POST['updatecategory']);

		
					$update_subcategory = "UPDATE `tbl_subcategories` SET `subcategory_name`='$subcategoryName', `category`='$category' WHERE `subcategory_id` = '$subcategoryId'";
					$query_execute = mysqli_query($connection, $update_subcategory);
		
					if ($query_execute) {
						echo "success";
					}
					else {
						echo "failed";
					}
		}
		else{
			header("Location: subcategories.php");
			exit(0);
		}
	}

		// Delete Sub Category
		if($_POST["action"] == 'deleteSubCat'){
			if($_POST["subcategory_id"]) {
				$sqlDelete = "DELETE FROM `tbl_subcategories` WHERE `subcategory_id` = '".$_POST["subcategory_id"]."'";		
				mysqli_query($connection, $sqlDelete);		
			}
		}

	// Get all medicine types
	if($_POST["action"] == 'getTypes')
	{
		$sqlQuery = "SELECT * FROM `tbl_medicinetype`";

		if(isset($_POST['search']['value']))
		{
			$search_value = $_POST['search']['value'];
			$sqlQuery .= " WHERE medicine_type like '%".$search_value."%'";
		}

		if(isset($_POST['order']))
		{
				$column_name = $_POST['order'][0]['column'];
				$order = $_POST['order'][0]['dir'];
				$sqlQuery .= " ORDER BY ".$column_name." ".$order."";
		}
		else
		{
				$sqlQuery .= " ORDER BY type_id asc";
		}
		
		if($_POST['length'] != -1)
		{
				$start = $_POST['start'];
				$length = $_POST['length'];
				$sqlQuery .= " LIMIT  ".$start.", ".$length;
		}   

		$result = mysqli_query($connection, $sqlQuery);
		$numRows = mysqli_num_rows($result);

		$sqlQueryTotal = "SELECT * FROM `tbl_medicinetype`";
		$resultTotal = mysqli_query($connection, $sqlQueryTotal);
		$numRowsTotal = mysqli_num_rows($resultTotal);

		$typeData = array();	
		while( $type = mysqli_fetch_assoc($result) ) {		
			$typeRows = array();			
			$typeRows[] = $type['type_id'];
			$typeRows[] = $type['medicine_type'];					
			$typeRows[] = '<button name="update" type="button" id="'.$type["type_id"].'" class="btn btn-success updateBtn"><i class="fa-solid fa-pen-to-square"></i></button>
			<button type="button" name="delete" id="'.$type["type_id"].'" class="btn btn-danger deleteBtn"><i class="fa-solid fa-trash-can"></i></button>';
			$typeData[] = $typeRows;
		}
		$output = array(
			"draw"	=>	intval($_POST["draw"]),			
			"iTotalRecords"	=> 	$numRows,
			"iTotalDisplayRecords"	=>  $numRowsTotal,
			"data"	=> 	$typeData
		);
		echo json_encode($output);
  }

		// Add type
		if($_POST["action"] == 'addType'){

			if(isset($_POST['saveType'])){
	
				$typeName = mysqli_real_escape_string($connection, $_POST['medicine_type']);
			
					// Check if category with same name exists
					$checkType = "SELECT `medicine_type` FROM `tbl_medicinetype` WHERE `medicine_type` = '$typeName'";
					$checkType_execute = mysqli_query($connection, $checkType);
			
					if (mysqli_num_rows($checkType_execute) > 0) {
						echo "typeFail";
					}
					else {
						$insert_type = "INSERT INTO `tbl_medicinetype` (`medicine_type`) VALUES ('$typeName')";
						$query_execute = mysqli_query($connection, $insert_type);
			
						if ($query_execute) {
							echo "success";
						}
						else {
							echo "failed";
						}
					}
			}
			else{
				header("Location: medicinetypes.php");
				exit(0);	
			}
		}

			// Get type by ID for update
	if($_POST["action"] == 'getTypebyID'){
		if(isset($_POST['type_id'])) {
			$sqlQuery = "SELECT * FROM `tbl_medicinetype` WHERE `type_id` = '".$_POST["type_id"]."'";
			$result = mysqli_query($connection, $sqlQuery);	
			$row = mysqli_fetch_assoc($result);
			echo json_encode($row);
		}
	}
	
	// Update Type
	if($_POST["action"] == 'editType'){
		if(isset($_POST['updateType'])){

			$typeId = mysqli_real_escape_string($connection, $_POST['updatetype_id']);
			$medicineType = mysqli_real_escape_string($connection, $_POST['updatemedicine_type']);
		
					$update_type = "UPDATE `tbl_medicinetype` SET `medicine_type`='$medicineType' WHERE `type_id` = '$typeId'";
					$query_execute = mysqli_query($connection, $update_type);
		
					if ($query_execute) {
						echo "success";
					}
					else {
						echo "failed";
					}
		}
		else{
			header("Location: medicinetypes.php");
			exit(0);
		}
	}

		// Delete Type
		if($_POST["action"] == 'deleteType'){
			if($_POST["type_id"]) {
				$sqlDelete = "DELETE FROM `tbl_medicinetype` WHERE `type_id` = '".$_POST["type_id"]."'";		
				mysqli_query($connection, $sqlDelete);		
			}
		}

	// Get all products
	if($_POST["action"] == 'getProducts')
	{
		$sqlQuery = "SELECT * FROM `tbl_product`";

		if(isset($_POST['search']['value']))
		{
			$search_value = $_POST['search']['value'];
			$sqlQuery .= " WHERE product_name like '%".$search_value."%'";
			$sqlQuery .= " OR product_description like '%".$search_value."%'";
			$sqlQuery .= " OR unit_price like '%".$search_value."%'";
			$sqlQuery .= " OR available_quantity like '%".$search_value."%'";
		}

		if(isset($_POST['order']))
		{
				$column_name = $_POST['order'][0]['column'];
				$order = $_POST['order'][0]['dir'];
				$sqlQuery .= " ORDER BY ".$column_name." ".$order."";
		}
		else
		{
				$sqlQuery .= " ORDER BY product_id asc";
		}
		
		if($_POST['length'] != -1)
		{
				$start = $_POST['start'];
				$length = $_POST['length'];
				$sqlQuery .= " LIMIT  ".$start.", ".$length;
		}   

		$result = mysqli_query($connection, $sqlQuery);
		$numRows = mysqli_num_rows($result);

		$sqlQueryTotal = "SELECT * FROM `tbl_product`";
		$resultTotal = mysqli_query($connection, $sqlQueryTotal);
		$numRowsTotal = mysqli_num_rows($resultTotal);

		$productData = array();	
		while( $products = mysqli_fetch_assoc($result) ) {		
			$productRows = array();			
			$productRows[] = $products['product_id'];
			$productRows[] = $products['product_name'];					
			$productRows[] = $products['product_description'];					
			$productRows[] = '<img src="uploads/'.$products["product_image"].'" class="img-fluid img-thumbnail" width="75" height="75" />';				
			$productRows[] = $products['unit_price'];	
			$productRows[] = $products['available_quantity'];					
			$productRows[] = $products['subcategory_id'];	
			$productRows[] = $products['medicine_type'];					
			$productRows[] = $products['added_by'];					

			$productRows[] = '<button name="update" type="button" id="'.$products["product_id"].'" class="btn btn-success updateBtn"><i class="fa-solid fa-pen-to-square"></i></button>
			<button type="button" name="delete" id="'.$products["product_id"].'" class="btn btn-danger deleteBtn"><i class="fa-solid fa-trash-can"></i></button>';
			$productData[] = $productRows;
		}
		$output = array(
			"draw"	=>	intval($_POST["draw"]),			
			"iTotalRecords"	=> 	$numRows,
			"iTotalDisplayRecords"	=>  $numRowsTotal,
			"data"	=> 	$productData
		);
		echo json_encode($output);
  }

    // Add Product
		if($_POST["action"] == 'addProduct'){
				
			$product_name = mysqli_real_escape_string($connection, $_POST['product_name']);
			$product_description =mysqli_real_escape_string($connection, $_POST['product_description']);

			$filename = $_FILES["product_image"]["name"];
			$tempname = $_FILES["product_image"]["tmp_name"];
			$folder = "./uploads/" . $filename;
			move_uploaded_file($tempname, $folder);

			$unit_price = mysqli_real_escape_string($connection, $_POST['unit_price']);
			$available_quantity = mysqli_real_escape_string($connection, $_POST['available_quantity']);
			$subcategory_id = mysqli_real_escape_string($connection, $_POST['subcategory_id']);
			$medicine_type = mysqli_real_escape_string($connection, $_POST['medicinetype_id']);

			$created_at = date('Y-m-d H:i:s');
			$added_by = mysqli_real_escape_string($connection, $_POST['added_by']);

				// Check if product exists
				$checkProduct = "SELECT `product_name` FROM `tbl_product` WHERE `product_name` = '$product_name'";
				$checkProduct_run = mysqli_query($connection, $checkProduct);
		
				if (mysqli_num_rows($checkProduct_run) > 0) {
					echo json_encode("Fail");
				}
				else {
				$insert_product = "INSERT INTO `tbl_product` (`product_name`, `product_description`, `product_image`, `unit_price`, `available_quantity`, `subcategory_id`, `medicine_type`, `created_at`, `added_by`) VALUES ('$product_name', '$product_description', '$filename', '$unit_price', '$available_quantity', '$subcategory_id', '$medicine_type', '$created_at', '$added_by')";
				$query_execute = mysqli_query($connection, $insert_product);
	
					if ($query_execute) {
						// echo "success";
					echo json_encode("success");

					}
					else {
						// echo "Failed";
					echo json_encode("Failed");
					}
				}

		}

		// Get product by ID for update
		if($_POST["action"] == 'getProductbyID'){
			if(isset($_POST['product_id'])) {
				$sqlQuery = "SELECT * FROM `tbl_product` WHERE `product_id` = '".$_POST["product_id"]."'";
				$result = mysqli_query($connection, $sqlQuery);	
				$row = mysqli_fetch_assoc($result);
				echo json_encode($row);
			}
		}

		// Update Product
		if($_POST["action"] == 'editProduct'){

			$product_id = mysqli_real_escape_string($connection, $_POST['updateproduct_id']);
			$product_name = mysqli_real_escape_string($connection, $_POST['updateproduct_name']);
			$product_description =mysqli_real_escape_string($connection, $_POST['updateproduct_description']);

			$filename = $_FILES["updateproduct_image"]["name"];
			$tempname = $_FILES["updateproduct_image"]["tmp_name"];
			$folder = "./uploads/" . $filename;
			move_uploaded_file($tempname, $folder);

			$unit_price = mysqli_real_escape_string($connection, $_POST['updateunit_price']);
			$available_quantity = mysqli_real_escape_string($connection, $_POST['updateavailable_quantity']);
			$subcategory_id = mysqli_real_escape_string($connection, $_POST['updatesubcategory_id']);
			$medicine_type = mysqli_real_escape_string($connection, $_POST['updatemedType_id']);
			$updated_at = date('Y-m-d H:i:s');
			$added_by = mysqli_real_escape_string($connection, $_POST['updateadded_by']);

			
			$update_product = "UPDATE `tbl_product` SET `product_name` = '$product_name', `product_description` = '$product_description', `product_image` = '$filename', `unit_price` = '$unit_price', `available_quantity` = '$available_quantity', `subcategory_id` = '$subcategory_id', `medicine_type` = '$medicine_type', `updated_at` = '$updated_at', `added_by` = '$added_by' WHERE `product_id` = '$product_id'";
			$query_execute = mysqli_query($connection, $update_product);

			if ($query_execute) {
				echo json_encode("updatesuccess");
			}
			else {
				echo json_encode("updateFailed");
			}
		}

		// Delete Product
		if($_POST["action"] == 'deleteProduct'){
			if($_POST["product_id"]) {
				$sqlDelete = "DELETE FROM `tbl_product` WHERE `product_id` = '".$_POST["product_id"]."'";		
				mysqli_query($connection, $sqlDelete);		
			}
		}

			// Get all hospital staff
	if($_POST["action"] == 'getStaff')
	{
		$sqlQuery = "SELECT * FROM `tbl_hospitalstaff`";

		if(isset($_POST['search']['value']))
		{
			$search_value = $_POST['search']['value'];
			$sqlQuery .= " WHERE first_name like '%".$search_value."%'";
			$sqlQuery .= " OR last_name like '%".$search_value."%'";
			$sqlQuery .= " OR hospital_email like '%".$search_value."%'";
			$sqlQuery .= " OR gender like '%".$search_value."%'";
		}

		if(isset($_POST['order']))
		{
				$column_name = $_POST['order'][0]['column'];
				$order = $_POST['order'][0]['dir'];
				$sqlQuery .= " ORDER BY ".$column_name." ".$order."";
		}
		else
		{
				$sqlQuery .= " ORDER BY staff_id asc";
		}
		
		if($_POST['length'] != -1)
		{
				$start = $_POST['start'];
				$length = $_POST['length'];
				$sqlQuery .= " LIMIT  ".$start.", ".$length;
		}   

		$result = mysqli_query($connection, $sqlQuery);
		$numRows = mysqli_num_rows($result);

		$sqlQueryTotal = "SELECT * FROM `tbl_hospitalstaff`";
		$resultTotal = mysqli_query($connection, $sqlQueryTotal);
		$numRowsTotal = mysqli_num_rows($resultTotal);

		$staffData = array();	
		while( $staff = mysqli_fetch_assoc($result) ) {		
			$staffRows = array();			
			$staffRows[] = $staff['staff_id'];
			$staffRows[] = $staff['first_name'];					
			$staffRows[] = $staff['last_name'];					
			$staffRows[] = $staff['phoneNo'];					
			$staffRows[] = $staff['hospital_email'];	
			$staffRows[] = $staff['gender'];					
			$staffRows[] = $staff['role'];					
			$staffRows[] = '<button name="update" type="button" id="'.$staff["staff_id"].'" class="btn btn-success updateBtn"><i class="fa-solid fa-pen-to-square"></i></button>
			<button type="button" name="delete" id="'.$staff["staff_id"].'" class="btn btn-danger deleteBtn"><i class="fa-solid fa-trash-can"></i></button>';
			$staffData[] = $staffRows;
		}
		$output = array(
			"draw"	=>	intval($_POST["draw"]),			
			"iTotalRecords"	=> 	$numRows,
			"iTotalDisplayRecords"	=>  $numRowsTotal,
			"data"	=> 	$staffData
		);
		echo json_encode($output);
  }

	// add hospital staff
	if($_POST["action"] == 'addStaff'){

		if(isset($_POST['saveStaff'])){
			$fname = mysqli_real_escape_string($connection, $_POST['first_name']);
			$lname =mysqli_real_escape_string($connection, $_POST['last_name']);
			$phoneNo = mysqli_real_escape_string($connection, $_POST['phoneNo']);
			$email = mysqli_real_escape_string($connection, $_POST['email']);
			$gender = mysqli_real_escape_string($connection, $_POST['gender']);
			$password = mysqli_real_escape_string($connection, $_POST['password']);
			$hashed_password = password_hash($password, PASSWORD_DEFAULT);
			$role = mysqli_real_escape_string($connection, $_POST['role']);

				
				// Check if staff with same email exists
				$checkEmail = "SELECT `hospital_email` FROM `tbl_hospitalstaff` WHERE `hospital_email` = '$email'";
				$checkEmail_run = mysqli_query($connection, $checkEmail);
		
				if (mysqli_num_rows($checkEmail_run) > 0) {
					echo "emailFail";
				}
				else {
					$insert_staff = "INSERT INTO `tbl_hospitalstaff` (`first_name`, `last_name`, `phoneNo`, `hospital_email`, `gender`, `password`, `role`) VALUES ('$fname','$lname', '$phoneNo', '$email', '$gender', '$hashed_password', '$role')";
					$query_execute = mysqli_query($connection, $insert_staff);
		
					if ($query_execute) {
						echo "success";
					}
					else {
						echo "Failed";
					}
				}

		}
		else{
			header("Location: staffs.php");
			exit(0);
		}
  }

			// Get staff by ID for update
			if($_POST["action"] == 'getStaffbyID'){
				if(isset($_POST['staff_id'])) {
					$sqlQuery = "SELECT * FROM `tbl_hospitalstaff` WHERE `staff_id` = '".$_POST["staff_id"]."'";
					$result = mysqli_query($connection, $sqlQuery);	
					$row = mysqli_fetch_assoc($result);
					echo json_encode($row);
				}
			}
	
			// Update staff
			if($_POST["action"] == 'editStaff'){
				
				if(isset($_POST['updateStaff'])){
		
					$staffId = mysqli_real_escape_string($connection, $_POST['staff_id']);
					$fname = mysqli_real_escape_string($connection, $_POST['updatefirst_name']);
					$lname =mysqli_real_escape_string($connection, $_POST['updatelast_name']);
					$phoneNo = mysqli_real_escape_string($connection, $_POST['updatephoneNo']);
					$email = mysqli_real_escape_string($connection, $_POST['updateemail']);
					$gender = mysqli_real_escape_string($connection, $_POST['updategender']);
		
							$update_staff = "UPDATE `tbl_hospitalstaff` SET `first_name`='$fname', `last_name` = '$lname', `phoneNo` = '$phoneNo', `hospital_email` = '$email', `gender` = '$gender'  WHERE `staff_id` = '$staffId'";
							$query_execute = mysqli_query($connection, $update_staff);
				
							if ($query_execute) {
								echo "success";
							}
							else {
								echo "failed";
							}
				}
				else{
					header("Location: registeredusers.php");
					exit(0);
				}
			}

				// Delete staff
	if($_POST["action"] == 'deleteStaff'){
		if($_POST["staff_id"]) {
			$sqlDelete = "DELETE FROM `tbl_hospitalstaff` WHERE `staff_id` = '".$_POST["staff_id"]."'";		
			mysqli_query($connection, $sqlDelete);		
		}
	}

		// Get Prescriptions
		if($_POST["action"] == 'getPrescriptions')
		{
			$hospital_email = $_SESSION['auth_user']['email'];
	
			$sqlQuery = "SELECT * FROM `tbl_prescription` WHERE `status` = 'approved'";

	
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
				$prescriptionRows[] = $prescriptions['approved_by'];					
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
				// $approvedby = mysqli_real_escape_string($connection, $_POST['updateapprovedby']);
	
						$update_prescription = "UPDATE `tbl_prescription` SET `status` = '$status' WHERE `prescription_id` = '$prescriptionId'";
						$query_execute = mysqli_query($connection, $update_prescription);
			
						if ($query_execute) {
							echo "success";
						}
						else {
							echo "failed";
						}
			}
			else{
				header("Location: approvedprescriptions.php");
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

				// Get All Prescriptions
				if($_POST["action"] == 'getAllPrescriptions')
				{			
					$sqlQuery = "SELECT * FROM `tbl_prescription`";
			
					if(isset($_POST['search']['value']))
					{
						$search_value = $_POST['search']['value'];
						$sqlQuery .= " WHERE customer_name like '%".$search_value."%'";
						$sqlQuery .= " OR hospital_email like '%".$search_value."%'";
					}
			
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
						$prescriptionRows[] = $prescriptions['approved_by'];					
						$prescriptionRows[] = '<button name="update" type="button" id="'.$prescriptions["prescription_id"].'" class="btn btn-warning viewallBtn"><i class="fas fa-eye"></i></button> <button name="update" type="button" id="'.$prescriptions["prescription_id"].'" class="btn btn-success updateBtn"><i class="fa-solid fa-pen-to-square"></i></button>
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

				if($_POST["action"] == 'getAllPrescriptionbyID'){
					if(isset($_POST['prescription_id'])) {
						$sqlQuery = "SELECT * FROM `tbl_prescription` WHERE `prescription_id` = '".$_POST["prescription_id"]."'";
						$result = mysqli_query($connection, $sqlQuery);	
						$row = mysqli_fetch_assoc($result);
						echo json_encode($row);
					}
				}

						// Update All Prescription
		if($_POST["action"] == 'editallPrescription'){
			
			if(isset($_POST['updatePrescription'])){
	
				$prescriptionId = mysqli_real_escape_string($connection, $_POST['prescription_id']);
				$cname = mysqli_real_escape_string($connection, $_POST['updatecustomer_name']);
				$hemail = mysqli_real_escape_string($connection, $_POST['updatehospitalemail']);
				$status = mysqli_real_escape_string($connection, $_POST['updatestatus']);
				// $approvedby = mysqli_real_escape_string($connection, $_POST['updateapprovedby']);
	
						$update_prescription = "UPDATE `tbl_prescription` SET `status` = '$status' WHERE `prescription_id` = '$prescriptionId'";
						$query_execute = mysqli_query($connection, $update_prescription);
			
						if ($query_execute) {
							echo "success";
						}
						else {
							echo "failed";
						}
			}
			else{
				header("Location: allprescriptions.php");
				exit(0);
			}
		}

				if($_POST["action"] == 'getAllPrescriptionforView'){
			
					if(isset($_POST['prescription_id'])) {
						$sqlQuery = "SELECT `prescription_image` FROM `tbl_prescription` WHERE `prescription_id` = '".$_POST["prescription_id"]."'";
						$result = mysqli_query($connection, $sqlQuery);	
						$row = mysqli_fetch_assoc($result);
						echo json_encode($row);
					}
				}

			// Get all orders
	if($_POST["action"] == 'getOrders')
	{
		$sqlQuery = "SELECT * FROM `tbl_order`";

		if(isset($_POST['search']['value']))
		{
			$search_value = $_POST['search']['value'];
			$sqlQuery .= " WHERE customer_id like '%".$search_value."%'";
		}

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

		$sqlQueryTotal = "SELECT * FROM `tbl_users`";
		$resultTotal = mysqli_query($connection, $sqlQueryTotal);
		$numRowsTotal = mysqli_num_rows($resultTotal);

		$orderData = array();	
		while( $orders = mysqli_fetch_assoc($result) ) {		
			$orderRows = array();			
			$orderRows[] = $orders['order_id'];
			$orderRows[] = $orders['customer_id'];					
			$orderRows[] = $orders['order_amount'];					
			$orderRows[] = $orders['order_status'];					
			$orderRows[] = $orders['created_at'];	
			$orderRows[] = $orders['payment_type'];					
			$orderRows[] = $orders['delivery_address'];					
			$orderRows[] = $orders['updated_at'];					
			$orderRows[] = '<button name="update" type="button" id="'.$orders["order_id"].'" class="btn btn-success updateBtn"><i class="fa-solid fa-pen-to-square"></i></button>
			<button type="button" name="delete" id="'.$orders["order_id"].'" class="btn btn-warning viewBtn"><i class="fa-solid fa-eye"></i></button>';
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

			// Get order by ID for update
			if($_POST["action"] == 'getOrderbyID'){
				if(isset($_POST['order_id'])) {
					$sqlQuery = "SELECT * FROM `tbl_order` WHERE `order_id` = '".$_POST["order_id"]."'";
					$result = mysqli_query($connection, $sqlQuery);	
					$row = mysqli_fetch_assoc($result);
					echo json_encode($row);
				}
			}

			// Update Order
			if($_POST["action"] == 'editOrder'){

				$order_id = mysqli_real_escape_string($connection, $_POST['updateorder_id']);
				$order_status = mysqli_real_escape_string($connection, $_POST['updateorder_status']);
				$address = mysqli_real_escape_string($connection, $_POST['updateaddress']);
				$updated_at = date('Y-m-d H:i:s');	
				
				$update_order = "UPDATE `tbl_order` SET `order_status` = '$order_status', `delivery_address` = '$address', `updated_at` = '$updated_at' WHERE `order_id` = '$order_id'";
				$query_execute = mysqli_query($connection, $update_order);
	
				if ($query_execute) {
					echo "success";
				}
				else {
					echo "Failed";
				}
			}

			// View Order Details
			if($_POST["action"] == 'getOrderDetails'){
				if(isset($_POST['order_id'])) {
					$sqlQuery = "SELECT * FROM `tbl_orderdetails` WHERE `order_id` = '".$_POST["order_id"]."'";
					$result = mysqli_query($connection, $sqlQuery);	
					// $row = mysqli_fetch_array($result);
					while ($row = mysqli_fetch_object($result)) {
						$json[] = array('product_id' => $row->product_id, 'product_name' => $row->product, 'unit_price' => $row->product_price, 'quantity' => $row->order_quantity, 'total'=>$row->orderdetails_total);
					}
					$data = json_encode($json);
					echo $data; 
				}
			}

			// Admin create order
			// Get products
			if($_POST["action"] == 'getProductsforOrder')
			{
				$sqlQuery = "SELECT * FROM `tbl_product`";
		
				if(isset($_POST['search']['value']))
				{
					$search_value = $_POST['search']['value'];
					$sqlQuery .= " WHERE product_name like '%".$search_value."%'";
					$sqlQuery .= " OR product_description like '%".$search_value."%'";
					$sqlQuery .= " OR unit_price like '%".$search_value."%'";
					$sqlQuery .= " OR available_quantity like '%".$search_value."%'";
				}
		
				if(isset($_POST['order']))
				{
						$column_name = $_POST['order'][0]['column'];
						$order = $_POST['order'][0]['dir'];
						$sqlQuery .= " ORDER BY ".$column_name." ".$order."";
				}
				else
				{
						$sqlQuery .= " ORDER BY product_id asc";
				}
				
				if($_POST['length'] != -1)
				{
						$start = $_POST['start'];
						$length = $_POST['length'];
						$sqlQuery .= " LIMIT  ".$start.", ".$length;
				}   
		
				$result = mysqli_query($connection, $sqlQuery);
				$numRows = mysqli_num_rows($result);
		
				$sqlQueryTotal = "SELECT * FROM `tbl_product`";
				$resultTotal = mysqli_query($connection, $sqlQueryTotal);
				$numRowsTotal = mysqli_num_rows($resultTotal);
		
				$productData = array();	
				while( $products = mysqli_fetch_assoc($result) ) {		
					$productRows = array();			
					$productRows[] = $products['product_id'];
					$productRows[] = $products['product_name'];					
					$productRows[] = $products['product_description'];					
					$productRows[] = '<img src="uploads/'.$products["product_image"].'" class="img-fluid img-thumbnail" width="75" height="75" />';				
					$productRows[] = $products['unit_price'];	
		
					$productRows[] = '<button name="addtocart" type="button" data-product-id="'.$products["product_id"].'" data-product-name="'.$products["product_name"].'" data-unit-price="'.$products["unit_price"].'" class="btn btn-success addtocart"><i class="fas fa-shopping-bag"></i></button><input type="hidden" class="product_quantity" id="' .$products["product_id"].
					'" value="1">';
					$productData[] = $productRows;
				}
				$output = array(
					"draw"	=>	intval($_POST["draw"]),			
					"iTotalRecords"	=> 	$numRows,
					"iTotalDisplayRecords"	=>  $numRowsTotal,
					"data"	=> 	$productData
				);
				echo json_encode($output);
			}

			// add to cart
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

      // count cart items
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

	if($_POST["action"] == 'placeOrder'){
    $products = mysqli_real_escape_string($connection, $_POST['products']);
    $customer_id = mysqli_real_escape_string($connection, $_POST['customer_id']);
    $customer_email = mysqli_real_escape_string($connection, $_POST['customer_email']);
    $total = mysqli_real_escape_string($connection, $_POST['grand_total']);
    $created_at = date('Y-m-d H:i:s');
    // $paymenttype = mysqli_real_escape_string($connection, $_POST['paymenttype']);
    // $address = mysqli_real_escape_string($connection, $_POST['address']);

    $insert_order = "INSERT INTO `tbl_order` (`customer_id`, `order_amount`, `order_status`, `created_at`, `payment_type`, `delivery_address`) VALUES ('$customer_id', '$total', 'Pending Payment', '$created_at', '2', 'Customer to fill details')";
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
				$mail->addAddress($customer_email); 

				//Content
				$mail->isHTML(true);                                  
				$mail->Subject = 'Prescription Processed';
				$mail->Body    = 'Hello, The prescription uploaded by you has been approved and processed. Please check your order by logging in. Thank you.';

				$mail->send();
      $data .= '<div class="text-center">
								<h1 class="display-4 mt-2 text-danger">Order has been placed</h1>
								<h2 class="text-success">Customer is required to make payment for order to be delivered</h2>
								<h4 class="bg-danger text-light rounded p-2">Items Purchased : ' . $products . '</h4>
								<h4>Total Amount to be paid : Ksh. ' . number_format($total,2) . '</h4>
						  </div>';
      echo $data;

      }
      else {
      echo "orderFailed";
      }
    }


}

?>