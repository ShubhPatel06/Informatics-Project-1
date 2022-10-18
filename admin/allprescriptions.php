<?php
session_start();
include('../config/db_connection.php');
include('authentication.php');
include('includes/header.php');

?>

<div class="container-fluid px-4">
  <h1 class="mt-4">All Prescriptions</h1>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Dashboard</li>
    <li class="breadcrumb-item">All Prescriptions</li>
  </ol>

  <div class="table-responsive">
    <table id="allprescriptionTable" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Prescription Image</th>
          <th>Customer Name</th>
          <th>Hospital Email</th>
          <th>Status</th>
          <th>Approved by</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>

  <div class="modal fade" id="updateprescriptionModal" tabindex="-1" aria-labelledby="updateprescriptionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateprescriptionModalLabel"><i class='fa-solid fa-pen-to-square'></i> Edit
            Prescription Status
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="updateprescriptionForm">
          <div class="modal-body">
            <div id="message"></div>

            <div class="form-group mb-3">
              <label for="">Prescription ID</label>
              <input readonly type="number" id="prescription_id" name="prescription_id" class="form-control"
                placeholder="Prescription ID">
            </div>
            <div class="form-group mb-3">
              <label for="">Customer Name</label>
              <input readonly type="text" id="updatecustomer_name" name="updatecustomer_name" class="form-control"
                placeholder="Customer Name">
            </div>
            <div class="form-group mb-3">
              <label for="">Hospital Email</label>
              <input readonly type="email" id="updatehospitalemail" name="updatehospitalemail" class="form-control"
                placeholder="Hospital Email">
            </div>
            <div class="form-group mb-3">
              <label for="">Status</label>
              <input type="text" id="updatestatus" name="updatestatus" class="form-control" placeholder="Status">
            </div>

          </div>
          <div class="modal-footer">
            <input type="hidden" name="action" id="action" value="editallPrescription" />
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="updatePrescription">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="viewallModal" tabindex="-1" aria-labelledby="viewallModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewallModalLabel">View Prescription</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="container" id="displayImg">

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
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
  var allprescriptionTable = $('#allprescriptionTable').DataTable({
    "lengthChange": true,
    "processing": true,
    "serverSide": true,
    "order": [],
    "autoWidth": false,
    "ajax": {
      url: "crud.php",
      type: "POST",
      data: {
        action: 'getAllPrescriptions'
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


  $("#allprescriptionTable").on('click', '.updateBtn', function() {
    var prescription_id = $(this).attr("id");
    var action = 'getAllPrescriptionbyID';

    $.ajax({
      url: 'crud.php',
      type: "POST",
      data: {
        prescription_id: prescription_id,
        action: action
      },
      dataType: "json",
      success: function(data) {
        $('#updateprescriptionModal').modal('show');
        $('#prescription_id').val(data.prescription_id);
        $('#updatecustomer_name').val(data.customer_name);
        $('#updatehospitalemail').val(data.hospital_email);
        $('#updatestatus').val(data.status);
      }
    });
  });

  $("form[id='updateprescriptionForm']").validate({
    errorClass: 'error',
    rules: {
      status: "required",
    },
    messages: {
      status: "Status is required",
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
              "Prescription updated successfully" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            // document.getElementById("updateuserForm").reset();
            allprescriptionTable.ajax.reload();

          } else if (response == "failed") {
            $('#message').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Prescription could not be updated! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("updateprescriptionForm").reset();
          } else {
            $('#message').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Prescription could not be updated! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("updateprescriptionForm").reset();
          }
        }
      });
    }
  });

  $("#allprescriptionTable").on('click', '.viewallBtn', function() {
    var prescription_id = $(this).attr("id");
    var action = 'getAllPrescriptionforView';

    $.ajax({
      url: 'crud.php',
      type: "POST",
      data: {
        prescription_id: prescription_id,
        action: action
      },
      dataType: "json",
      success: function(data) {
        $('#viewallModal').modal('show');
        $('#displayImg').html('<img src="../prescriptionuploads/' + data.prescription_image +
          '" alt="" width="100%" height="450px">');
      }
    });
  });
});
</script>