@php

use App\Models\EmployeeInformation;
// Get all employees from the database
$employees = EmployeeInformation::all();
@endphp

@extends('layouts.master')

@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">Employee Information</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Employee Information</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2>Employee List</h2>
                    </div>
                    <div class="card-body">
                        <table id="employeeTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Date of Birth</th>
                                    <th>Address</th>
                                    <th>Contact Number</th>
                                    <th>Email</th>
                                    <th>Nationality</th>
                                    <th>Marital Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Edit Employee Modal -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editEmployeeForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editEmployeeModalLabel">Edit Employee Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_employee_id" name="employee_id">

                    <!-- User Name (Dropdown) -->
                <div class="form-group">
                    <label for="edit_user_id">User Name</label>
                    <select id="edit_user_id" name="user_id" class="form-control">
                        <option value="">Select User</option> <!-- Optional placeholder -->
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $user->id == old('user_id', '') ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>


                    <!-- Name -->
                   

                    <!-- Date of Birth -->
                    <div class="form-group">
                        <label for="edit_date_of_birth">Date of Birth</label>
                        <input type="date" id="edit_date_of_birth" name="date_of_birth" class="form-control">
                    </div>

                    <!-- Address -->
                    <div class="form-group">
                        <label for="edit_address">Address</label>
                        <input type="text" id="edit_address" name="address" class="form-control" placeholder="Enter address">
                    </div>

                    <!-- Contact Number -->
                    <div class="form-group">
                        <label for="edit_contact_number">Contact Number</label>
                        <input type="text" id="edit_contact_number" name="contact_number" class="form-control" placeholder="Enter contact number">
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="edit_email">Email</label>
                        <input type="email" id="edit_email" name="email" class="form-control" placeholder="Enter email">
                    </div>

                    <!-- Nationality -->
                    <div class="form-group">
                        <label for="edit_nationality">Nationality</label>
                        <input type="text" id="edit_nationality" name="nationality" class="form-control" placeholder="Enter nationality">
                    </div>

                    <!-- Marital Status -->
                    <div class="form-group">
                        <label for="edit_marital_status">Marital Status</label>
                        <input type="text" id="edit_marital_status" name="marital_status" class="form-control" placeholder="Enter marital status">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
    var table = $('#employeeTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("personal.information") }}',
        columns: [
            { data: 'id', name: 'id' },
            // { data: 'user_id', name: 'user_id' },
            { data: 'name', name: 'name' },
            { data: 'date_of_birth', name: 'date_of_birth' },
            { data: 'address', name: 'address' },
            { data: 'contact_number', name: 'contact_number' },
            { data: 'email', name: 'email' },
            { data: 'nationality', name: 'nationality' },
            { data: 'marital_status', name: 'marital_status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // CSRF token for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Edit Employee Information
    $('#employeeTable').on('click', '.edit', function() {
    var employeeId = $(this).data('id');
    $.ajax({
        url: '{{ route("employee_information.edit", ":id") }}'.replace(':id', employeeId),
        type: 'GET',
        success: function(data) {
            $('#edit_employee_id').val(data.id);
            $('#edit_user_id').val(data.user_id); // Populate dropdown
            $('#edit_name').val(data.name);
            $('#edit_date_of_birth').val(data.date_of_birth);
            $('#edit_address').val(data.address);
            $('#edit_contact_number').val(data.contact_number);
            $('#edit_email').val(data.email);
            $('#edit_nationality').val(data.nationality);
            $('#edit_marital_status').val(data.marital_status);
            $('#editEmployeeModal').modal('show');
        }
    });
});


    // Update Employee Information
    $('#editEmployeeForm').on('submit', function(event) {
        event.preventDefault();
        var employeeId = $('#edit_employee_id').val();
        $.ajax({
            url: '{{ route("employee_information.update", ":id") }}'.replace(':id', employeeId),
            type: 'POST',
            data: $(this).serialize() + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message,
                    timer: 2000
                });
                table.ajax.reload();
                $('#editEmployeeModal').modal('hide');
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                var errorMessage = '<ul>';
                $.each(errors, function(key, value) {
                    errorMessage += '<li>' + value[0] + '</li>';
                });
                errorMessage += '</ul>';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: errorMessage
                });
            }
        });
    });

    // Delete Employee Information with SweetAlert confirmation
    $('#employeeTable').on('click', '.delete', function() {
        var employeeId = $(this).data('id');
        alert('{{ route("employee_information.delete", ":id") }}'.replace(':id', employeeId));
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("employee_information.delete", ":id") }}'.replace(':id', employeeId),
                    type: 'DELETE',
                    data: {
                        "_token": $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            timer: 2000
                        });
                        table.ajax.reload();
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Unable to delete employee information!'
                        });
                    }
                });
            }
        });
    });
});

</script>
@endsection
