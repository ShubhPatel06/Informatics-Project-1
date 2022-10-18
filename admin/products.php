<?php
include('../config/db_connection.php');
include('authentication.php');
include('includes/header.php');

?>

<div class="container-fluid px-4">
  <h1 class="mt-4">Products</h1>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Dashboard</li>
    <li class="breadcrumb-item">Products</li>
  </ol>
  <div align="right" class="mb-2">
    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#productModal">
      Add Product
    </button>
  </div>
  <div class="table-responsive">
    <table id="productsTable" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Product Name</th>
          <th>Product Description</th>
          <th>Product Image</th>
          <th>Unit Price</th>
          <th>Quantity</th>
          <th>Subcategory</th>
          <th>Medicine Type</th>
          <th>Added By</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>

  <!-- Add Product Modal -->
  <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="productModalLabel"><i class='fa fa-plus'></i> Add Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="addproductForm" enctype="multipart/form-data">
          <div class="modal-body">
            <div id="error"></div>

            <div class="form-group mb-3">
              <input type="text" id="product_name" name="product_name" class="form-control" placeholder="Product Name">
              <span id="error_productName" class="text-danger ms-3"></span>

            </div>
            <div class="form-group mb-3">
              <!-- <input type="text" id="product_description" name="product_description" class="form-control"
                placeholder="Product Description"> -->
              <textarea class="form-control" id="product_description" name="product_description"
                placeholder="Product Description" rows="3"></textarea>
              <span id="error_productDesc" class="text-danger ms-3"></span>

            </div>
            <div class="form-group mb-3">
              <input type="file" id="product_image" name="product_image" class="form-control">
              <span id="error_productImg" class="text-danger ms-3"></span>

            </div>
            <div class="form-group mb-3">
              <input type="number" id="unit_price" name="unit_price" class="form-control" placeholder="Unit Price">
              <span id="error_unitPrice" class="text-danger ms-3"></span>

            </div>
            <div class="form-group mb-3">
              <input type="number" id="available_quantity" name="available_quantity" class="form-control"
                placeholder="Available Quantity">
              <span id="error_availableQuantity" class="text-danger ms-3"></span>

            </div>
            <div class="form-group mb-3">
              <input type="number" id="subcategory_id" name="subcategory_id" class="form-control"
                placeholder="Sub Category ID">
              <span id="error_prodsubcategoryID" class="text-danger ms-3"></span>
            </div>
            <div class="form-group mb-3">
              <input type="number" id="medicinetype_id" name="medicinetype_id" class="form-control"
                placeholder="Medicine Type ID">
              <span id="error_medTypeID" class="text-danger ms-3"></span>
            </div>
            <div class="form-group mb-3">
              <input type="number" id="added_by" name="added_by" class="form-control" placeholder="Admin ID">
              <span id="error_addedBy" class="text-danger ms-3"></span>

            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="action" id="action" value="addProduct" />

            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="saveProduct">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Update Product Modal -->
  <div class="modal fade" id="updateproductModal" tabindex="-1" aria-labelledby="updateproductModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateproductModalLabel"><i class='fa fa-plus'></i> Edit Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="updateproductForm">
          <div class="modal-body">
            <div id="message"></div>
            <div class="form-group mb-3">
              <input readonly type="number" id="updateproduct_id" name="updateproduct_id" class="form-control"
                placeholder="Product ID">
            </div>
            <div class="form-group mb-3">
              <input type="text" id="updateproduct_name" name="updateproduct_name" class="form-control"
                placeholder="Product Name">
            </div>
            <div class="form-group mb-3">
              <input type="text" id="updateproduct_description" name="updateproduct_description" class="form-control"
                placeholder="Product Description">
            </div>
            <div class="form-group mb-3">
              <input type="file" id="updateproduct_image" name="updateproduct_image" class="form-control"
                placeholder="Product Image">
              <span id="uploaded_image"></span>
            </div>
            <div class="form-group mb-3">
              <input type="number" id="updateunit_price" name="updateunit_price" class="form-control"
                placeholder="Unit Price">
            </div>
            <div class="form-group mb-3">
              <input type="number" id="updateavailable_quantity" name="updateavailable_quantity" class="form-control"
                placeholder="Available Quantity">
            </div>
            <div class="form-group mb-3">
              <input type="number" id="updatesubcategory_id" name="updatesubcategory_id" class="form-control"
                placeholder="Sub Category ID">
            </div>
            <div class="form-group mb-3">
              <input type="number" id="updatemedType_id" name="updatemedType_id" class="form-control"
                placeholder="Medicine Type ID">
            </div>
            <div class="form-group mb-3">
              <input type="number" id="updateadded_by" name="updateadded_by" class="form-control"
                placeholder="Admin ID">
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="action" id="action" value="editProduct" />

            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="updateProduct">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>


</div>


<?php
include('includes/footer.php');
include('includes/scripts.php');
?>

<script>
$(document).ready(function() {
  var productTable = $('#productsTable').DataTable({
    "lengthChange": true,
    "processing": true,
    "serverSide": true,
    "order": [],
    "autoWidth": false,
    "ajax": {
      url: "crud.php",
      type: "POST",
      data: {
        action: 'getProducts'
      },
      dataType: "json"
    },
    "columnDefs": [{
      "targets": [0],
      "orderable": false,
    }, ],
    "fixedColumns": false,
    "pageLength": 10
  });

  // Add Product
  $('#addproductForm').on('submit', function(e) {
    e.preventDefault();

    if ($.trim($('#product_name').val()).length == 0) {
      error_productName = 'Product Name is required!';
      $('#error_productName').text(error_productName);
    } else {
      error_productName = '';
      $('#error_productName').text(error_productName);
    }

    if ($.trim($('#product_description').val()).length == 0) {
      error_productDesc = 'Product Description is required!';
      $('#error_productDesc').text(error_productDesc);
    } else {
      error_productDesc = '';
      $('#error_productDesc').text(error_productDesc);
    }

    if ($('#product_image').val() == '') {
      error_productImg = 'Please select an image';
      $('#error_productImg').text(error_productImg);
    } else {
      error_productImg = '';
      $('#error_productImg').text(error_productImg);
    }

    if ($.trim($('#unit_price').val()).length == 0) {
      error_unitPrice = 'Unit Price is required!';
      $('#error_unitPrice').text(error_unitPrice);
    } else {
      error_unitPrice = '';
      $('#error_unitPrice').text(error_unitPrice);
    }

    if ($.trim($('#available_quantity').val()).length == 0) {
      error_availableQuantity = 'Available Quantity is required!';
      $('#error_availableQuantity').text(error_availableQuantity);
    } else {
      error_availableQuantity = '';
      $('#error_availableQuantity').text(error_availableQuantity);
    }

    if ($.trim($('#subcategory_id').val()).length == 0) {
      error_prodsubcategoryID = 'Sub-Category ID is required!';
      $('#error_prodsubcategoryID').text(error_prodsubcategoryID);
    } else {
      error_prodsubcategoryID = '';
      $('#error_prodsubcategoryID').text(error_prodsubcategoryID);
    }

    if ($.trim($('#medicinetype_id').val()).length == 0) {
      error_medTypeID = 'Medicine Type ID is required!';
      $('#error_medTypeID').text(error_medTypeID);
    } else {
      error_medTypeID = '';
      $('#error_medTypeID').text(error_medTypeID);
    }

    if ($.trim($('#added_by').val()).length == 0) {
      error_addedBy = 'Added by ID is required!';
      $('#error_addedBy').text(error_addedBy);
    } else {
      error_addedBy = '';
      $('#error_addedBy').text(error_addedBy);
    }

    if (error_productName != '' || error_productDesc != '' || error_productImg != '' ||
      error_unitPrice != '' || error_availableQuantity != '' || error_prodsubcategoryID !=
      '' || error_medTypeID != '' || error_addedBy != ''
    ) {
      return false;
    } else {

      $.ajax({
        url: "crud.php",
        type: "POST",
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        dataType: "json",
        success: function(response) {
          if (response == "Fail") {
            $('#error').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Product not added! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            document.getElementById("addproductForm").reset();
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
          } else if (response == "success") {
            $('#error').html(
              '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>' +
              "Product added successfully!" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            document.getElementById("addproductForm").reset();
            productTable.ajax.reload();
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
          } else if (response == "Failed") {
            $('#error').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Product could not be added! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("addproductForm").reset();
          } else {
            $('#error').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Product could not be added! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("addproductForm").reset();
          }
        }
      });
    }
  });


  $("#productsTable").on('click', '.updateBtn', function() {
    var product_id = $(this).attr("id");
    var action = 'getProductbyID';

    $.ajax({
      url: 'crud.php',
      type: "POST",
      data: {
        product_id: product_id,
        action: action
      },
      dataType: "json",
      success: function(data) {
        $('#updateproductModal').modal('show');
        $('#updateproduct_id').val(data.product_id);
        $('#updateproduct_name').val(data.product_name);
        $('#updateproduct_description').val(data.product_description);
        $('#updateunit_price').val(data.unit_price);
        $('#updateavailable_quantity').val(data.available_quantity);
        $('#updatesubcategory_id').val(data.subcategory_id);
        $('#updatemedType_id').val(data.medicine_type);
        $('#updateadded_by').val(data.added_by);
      }
    });
  });

  $('#updateproductForm').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
      url: "crud.php",
      type: "POST",
      data: new FormData(this),
      contentType: false,
      cache: false,
      processData: false,
      dataType: "json",
      success: function(response) {
        if (response == "updatesuccess") {
          $('#message').html(
            '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>' +
            "Product updated successfully" +
            '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
          );
          $(".alert").delay(1500).slideUp(400, function() {
            $(this).alert('close');
          });
          productTable.ajax.reload();
        } else if (response == "updateFailed") {
          $('#message').html(
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
            "Product could not be updated! Please Try again" +
            '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
          );
          $(".alert").delay(1500).slideUp(400, function() {
            $(this).alert('close');
          });
          document.getElementById("updateproductForm").reset();
        } else {
          $('#message').html(
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
            "Product could not be updated! Please Try again" +
            '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
          );
          $(".alert").delay(1500).slideUp(400, function() {
            $(this).alert('close');
          });
          document.getElementById("updateproductForm").reset();
        }
      }
    });
  });

  $("#productsTable").on('click', '.deleteBtn', function() {
    var product_id = $(this).attr("id");
    var action = 'deleteProduct';
    if (confirm("Are you sure you want to delete this user?")) {
      $.ajax({
        url: 'crud.php',
        type: "POST",
        data: {
          product_id: product_id,
          action: action
        },
        success: function(response) {
          productTable.ajax.reload();
        }
      });
    } else {
      return false;
    }
  });

});
</script>