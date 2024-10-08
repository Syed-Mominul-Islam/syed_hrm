$(document).ready(function() {
    alert('this script is working');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var table = $('#employeeTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("personal.information") }}',
        pageLength: 2, 
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