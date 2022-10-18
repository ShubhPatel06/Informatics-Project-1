<?php
include('../config/db_connection.php');
include('authentication.php');
include('includes/header.php');

?>

<div class="container-fluid px-4" id="reload">
  <h1 class=" mt-4">Medicine Types</h1>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Dashboard</li>
    <li class="breadcrumb-item">Medicine Types</li>
  </ol>
  <div align="right" class="mb-2">
    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#typeModal">
      Add Medicine Type
    </button>
  </div>
  <div class="table-responsive">
    <table id="typesTable" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Medicine Type ID</th>
          <th>Medicine Type</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>

  <!-- Add Category Modal -->
  <div class="modal fade" id="typeModal" tabindex="-1" aria-labelledby="typeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="typeModalLabel"><i class='fa fa-plus'></i> Add Medicine Type</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="addTypeForm">
          <div class="modal-body">
            <div id="error"></div>
            <div class="form-group mb-3">
              <input type="text" name="medicine_type" class="form-control" placeholder="Medicine Type">
            </div>
          </div>

          <div class="modal-footer">
            <input type="hidden" name="action" id="action" value="addType" />

            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="saveType">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Update Category Modal -->
  <div class="modal fade" id="updatetypeModal" tabindex="-1" aria-labelledby="updatetypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updatetypeModalLabel"><i class='fa-solid fa-pen-to-square'></i> Edit Medicine Type
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="updateTypeForm">
          <div class="modal-body">
            <div id="message"></div>
            <div class="form-group mb-3">
              <label for="">Medicine Type ID</label>
              <input readonly type="number" id="updatetype_id" name="updatetype_id" class="form-control"
                placeholder="Medicine Type ID">
            </div>
            <div class="form-group mb-3">
              <label for="">Medicine Type</label>
              <input type="text" id="updatemedicine_type" name="updatemedicine_type" class="form-control"
                placeholder="Medicine Type">
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="action" id="action" value="editType" />

            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="updateType">Update</button>
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
  var table = $('#typesTable').DataTable({
    "lengthChange": true,
    "processing": true,
    "serverSide": true,
    "order": [],
    "autoWidth": false,
    "ajax": {
      url: "crud.php",
      type: "POST",
      data: {
        action: 'getTypes'
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

  $("form[id='addTypeForm']").validate({
    errorClass: 'error',
    rules: {
      medicine_type: "required",
    },
    messages: {
      medicine_type: "Medicine type is required",
    },
    submitHandler: function(form) {
      $.ajax({
        url: 'crud.php',
        type: 'POST',
        data: $(form).serialize(),
        success: function(response) {
          if (response == "typeFail") {
            $('#error').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Medicine type already exists" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("addTypeForm").reset();
          } else if (response == "success") {
            $('#error').html(
              '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>' +
              "Medicine Type added successfully" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("addTypeForm").reset();
            table.ajax.reload();

          } else if (response == "failed") {
            $('#error').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Medicine type could not be added! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("addTypeForm").reset();
          } else {
            $('#error').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Medicien type could not be added! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("addTypeForm").reset();
          }
        }
      });
    }
  });

  $("#typesTable").on('click', '.updateBtn', function() {
    var type_id = $(this).attr("id");
    var action = 'getTypebyID';

    $.ajax({
      url: 'crud.php',
      type: "POST",
      data: {
        type_id: type_id,
        action: action
      },
      dataType: "json",
      success: function(data) {
        $('#updatetypeModal').modal('show');

        $('#updatetype_id').val(data.type_id);
        $('#updatemedicine_type').val(data.medicine_type);
        // $('.modal-title').html("<i class='fa fa-plus'></i> Edit Category");
      }
    });
  });


  $("form[id='updateTypeForm']").validate({
    errorClass: 'error',
    rules: {
      medicine_type: "required",
    },
    messages: {
      medicine_type: "Medicine type is required",
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
              "Medicine type update successfully" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );

            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            table.ajax.reload();

          } else if (response == "failed") {
            $('#message').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Medicine type could not be updated! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("updateCategoryForm").reset();
          } else {
            $('#message').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Medicine type could not be updated! Please Try again" +
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

  $("#typesTable").on('click', '.deleteBtn', function() {
    var type_id = $(this).attr("id");
    var action = 'deleteType';
    if (confirm("Are you sure you want to delete this medicine type?")) {
      $.ajax({
        url: 'crud.php',
        type: "POST",
        data: {
          type_id: type_id,
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