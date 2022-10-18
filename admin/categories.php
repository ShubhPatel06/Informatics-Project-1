<?php
include('../config/db_connection.php');
include('authentication.php');
include('includes/header.php');

?>

<div class="container-fluid px-4" id="reload">
  <h1 class=" mt-4">Categories</h1>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Dashboard</li>
    <li class="breadcrumb-item">Categories</li>
  </ol>
  <div align="right" class="mb-2">
    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#categoryModal">
      Add Category
    </button>
  </div>
  <div class="table-responsive">
    <table id="categoriesTable" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Category ID</th>
          <th>Category Name</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>

  <!-- Add Category Modal -->
  <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="categoryModalLabel"><i class='fa fa-plus'></i> Add Category</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="addCategoryForm">
          <div class="modal-body">
            <div id="error"></div>
            <div class="form-group mb-3">
              <input type="text" name="category_name" class="form-control" placeholder="Category Name">
            </div>
          </div>

          <div class="modal-footer">
            <input type="hidden" name="action" id="action" value="addCategory" />

            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="saveCategory">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Update Category Modal -->
  <div class="modal fade" id="updatecategoryModal" tabindex="-1" aria-labelledby="updatecategoryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updatecategoryModalLabel"><i class='fa-solid fa-pen-to-square'></i> Edit Category
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="updateCategoryForm">
          <div class="modal-body">
            <div id="message"></div>
            <div class="form-group mb-3">
              <label for="">Category ID</label>
              <input readonly type="number" id="updatecategory_id" name="updatecategory_id" class="form-control"
                placeholder="Category ID">
            </div>
            <div class="form-group mb-3">
              <label for="">Category Name</label>
              <input type="text" id="updatecategory_name" name="updatecategory_name" class="form-control"
                placeholder="Category Name">
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="action" id="action" value="editCategory" />

            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="updateCategory">Update</button>
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
$('document').ready(function() {
  var table = $('#categoriesTable').DataTable({
    "lengthChange": true,
    "processing": true,
    "serverSide": true,
    "order": [],
    "autoWidth": false,
    "ajax": {
      url: "crud.php",
      type: "POST",
      data: {
        action: 'getCategories'
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

  $("form[id='addCategoryForm']").validate({
    errorClass: 'error',
    rules: {
      category_name: "required",
    },
    messages: {
      category_name: "Category name is required",
    },
    submitHandler: function(form) {
      $.ajax({
        url: 'crud.php',
        type: 'POST',
        data: $(form).serialize(),
        success: function(response) {
          if (response == "categoryFail") {
            $('#error').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Category already exists" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("addCategoryForm").reset();
          } else if (response == "success") {
            $('#error').html(
              '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>' +
              "Category added successfully" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("addCategoryForm").reset();
            table.ajax.reload();

          } else if (response == "failed") {
            $('#error').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Category could not be added! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("addCategoryForm").reset();
          } else {
            $('#error').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Category could not be added! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("addCategoryForm").reset();
          }
        }
      });
    }
  });

  $("#categoriesTable").on('click', '.updateBtn', function() {
    var category_id = $(this).attr("id");
    var action = 'getCatbyID';

    $.ajax({
      url: 'crud.php',
      type: "POST",
      data: {
        category_id: category_id,
        action: action
      },
      dataType: "json",
      success: function(data) {
        $('#updatecategoryModal').modal('show');

        $('#updatecategory_id').val(data.category_id);
        $('#updatecategory_name').val(data.category_name);
        // $('.modal-title').html("<i class='fa fa-plus'></i> Edit Category");
      }
    });
  });


  $("form[id='updateCategoryForm']").validate({
    errorClass: 'error',
    rules: {
      category_name: "required",
    },
    messages: {
      category_name: "Category name is required",
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
              "Category update successfully" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            // window.setTimeout(function() {
            //   $("#message").fadeTo(500, 0).slideUp(500, function() {
            //     $(this).remove();
            //   });
            // }, 1000);
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            // document.getElementById("updateCategoryForm").reset();
            table.ajax.reload();

          } else if (response == "failed") {
            $('#message').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Category could not be update! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("updateCategoryForm").reset();
          } else {
            $('#message').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Category could not be update! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("updateCategoryForm").reset();
          }
        }
      });
    }
  });

  $("#categoriesTable").on('click', '.deleteBtn', function() {
    var category_id = $(this).attr("id");
    var action = 'deleteCat';
    if (confirm("Are you sure you want to delete this category?")) {
      $.ajax({
        url: 'crud.php',
        type: "POST",
        data: {
          category_id: category_id,
          action: action
        },
        success: function(response) {
          table.ajax.reload();
        }
      });
    } else {
      return false;
    }
  });
});
</script>