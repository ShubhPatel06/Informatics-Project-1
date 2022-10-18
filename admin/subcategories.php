<?php
include('../config/db_connection.php');
include('authentication.php');
include('includes/header.php');

?>

<div class="container-fluid px-4">
  <h1 class="mt-4">Sub Categories</h1>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Dashboard</li>
    <li class="breadcrumb-item">Subcategories</li>
  </ol>
  <div align="right" class="mb-2">
    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#subcategoryModal">
      Add Sub-category
    </button>
  </div>
  <div class="table-responsive">
    <table id="subcategoriesTable" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Subcategory ID</th>
          <th>Subcategory Name</th>
          <th>Category</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>

  <!-- Add Sub Category Modal -->
  <div class="modal fade" id="subcategoryModal" tabindex="-1" aria-labelledby="subcategoryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="subcategoryModalLabel"><i class='fa fa-plus'></i> Add Sub Category</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="addSubCategoryForm">
          <div class="modal-body">
            <div id="error"></div>
            <div class="form-group mb-3">
              <input type="text" name="subcategory_name" class="form-control" placeholder="Sub Category Name">
            </div>
            <div class="form-group mb-3">
              <input type="number" name="category" class="form-control" placeholder="Category ID" min=1>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="action" id="action" value="addSubCategory" />

            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="saveSubCategory">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Update Sub Category Modal -->
  <div class="modal fade" id="updatesubcategoryModal" tabindex="-1" aria-labelledby="updatesubcategoryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updatesubcategoryModalLabel"><i class='fa-solid fa-pen-to-square'></i> Edit Sub
            Category
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="updateSubCategoryForm">
          <div class="modal-body">
            <div id="message"></div>
            <div class="form-group mb-3">
              <label for="">Sub Category ID</label>
              <input readonly type="number" id="updatesubcategory_id" name="updatesubcategory_id" class="form-control"
                placeholder="Sub Category ID">
            </div>
            <div class="form-group mb-3">
              <label for="">Sub Category Name</label>
              <input type="text" id="updatesubcategory_name" name="updatesubcategory_name" class="form-control"
                placeholder="Sub Category Name">
            </div>

            <div class="form-group mb-3">
              <label for="">Category ID</label>
              <input type="number" min=1 id="updatecategory" name="updatecategory" class="form-control"
                placeholder="Category ID">
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="action" id="action" value="editSubCategory" />

            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="updateSubCategory">Update</button>
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
  var Subtable = $('#subcategoriesTable').DataTable({
    "lengthChange": true,
    "processing": true,
    "serverSide": true,
    "order": [],
    "autoWidth": false,
    "ajax": {
      url: "crud.php",
      type: "POST",
      data: {
        action: 'getSubCategories'
      },
      dataType: "json"
    },
    "columnDefs": [{
      "width": '30%',
      "targets": [0],
      "orderable": false,
    }, ],
    "fixedColumns": false,
    "pageLength": 10
  });

  $("form[id='addSubCategoryForm']").validate({
    errorClass: 'error',
    rules: {
      subcategory_name: "required",
      category: "required",
    },
    messages: {
      subcategory_name: "Sub Category Name is required",
      category: "Category ID is required",

    },
    submitHandler: function(form) {
      $.ajax({
        url: 'crud.php',
        type: 'POST',
        data: $(form).serialize(),
        success: function(response) {
          if (response == "subcategoryFail") {
            $('#error').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Sub Category already exists" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("addSubCategoryForm").reset();
          } else if (response == "success") {
            $('#error').html(
              '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>' +
              "Sub Category added successfully" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("addSubCategoryForm").reset();
            Subtable.ajax.reload();

          } else if (response == "failed") {
            $('#error').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Sub Category could not be added! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("addSubCategoryForm").reset();
          } else {
            $('#error').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Sub Category could not be added! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("addSubCategoryForm").reset();
          }
        }
      });
    }
  });

  $("#subcategoriesTable").on('click', '.updateBtn', function() {
    var subcategory_id = $(this).attr("id");
    var action = 'getSubCatbyID';

    $.ajax({
      url: 'crud.php',
      type: "POST",
      data: {
        subcategory_id: subcategory_id,
        action: action
      },
      dataType: "json",
      success: function(data) {
        $('#updatesubcategoryModal').modal('show');

        $('#updatesubcategory_id').val(data.subcategory_id);
        $('#updatesubcategory_name').val(data.subcategory_name);
        $('#updatecategory').val(data.category);

      }
    });
  });

  $("form[id='updateSubCategoryForm']").validate({
    errorClass: 'error',
    rules: {
      subcategory_name: "required",
      category: "required",

    },
    messages: {
      subcategory_name: "Sub Category name is required",
      category: "Category ID is required",

    },
    submitHandler: function(form) {
      $.ajax({
        url: 'crud.php',
        type: 'POST',
        data: $(form).serialize(),
        success: function(response) {
          if (response == "success") {
            $('#message').html(
              '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>' +
              "Sub Category updated successfully" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            Subtable.ajax.reload();

          } else if (response == "failed") {
            $('#message').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Sub Category could not be update! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("updateSubCategoryForm").reset();
          } else {
            $('#message').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Sub Category could not be update! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("updateSubCategoryForm").reset();
          }
        }
      });
    }
  });

  $("#subcategoriesTable").on('click', '.deleteBtn', function() {
    var subcategory_id = $(this).attr("id");
    var action = 'deleteSubCat';
    if (confirm("Are you sure you want to delete this Sub category?")) {
      $.ajax({
        url: 'crud.php',
        type: "POST",
        data: {
          subcategory_id: subcategory_id,
          action: action
        },
        success: function(response) {
          Subtable.ajax.reload();
        }
      });
    } else {
      return false;
    }
  });

});
</script>