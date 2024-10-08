
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
    <!-- Table for displaying education backgrounds -->
    <table id="educationTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                {{-- <th>ID</th> --}}
                <th>Name</th> <!-- Name from users -->
                <th>Degree</th>
                <th>University</th>
                <th>Graduation Year</th>
                <th>Major</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- DataTables will populate this section -->
        </tbody>
    </table>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Education Background</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editEducationForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_degree" class="form-label">Degree</label>
                        <input type="text" class="form-control" id="edit_degree" name="degree" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_university" class="form-label">University</label>
                        <input type="text" class="form-control" id="edit_university" name="university" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_graduation_year" class="form-label">Graduation Year</label>
                        <input type="date" class="form-control" id="edit_graduation_year" name="graduation_year" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_major" class="form-label">Major</label>
                        <input type="text" class="form-control" id="edit_major" name="major" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery and DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        const table = $('#educationTable').DataTable({
            processing: true,
            serverSide: true,
            cache:false,
            responsive:true,
            ajax: {
                url: '{{ route('education-background.index') }}',
                type: 'GET',
            },
            columns: [
                // { data: 'id', name: 'id' },
                { data: 'users.name', name: 'users.name' }, // Access the user's name
                { data: 'degree', name: 'degree' },
                { data: 'university', name: 'university' },
                { data: 'graduation_year', name: 'graduation_year' },
                { data: 'major', name: 'major' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
                
            ]
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Handle edit button click
        $('#educationTable').on('click', '.btn-edit', function() {
            var id = $(this).data('id');

            // Fetch data for the selected entry
            $.ajax({
                url:'{{ route("education-background.edit", ":id") }}'.replace(':id', id),
                type: 'GET',
                success: function(data) {
                    // Populate the modal fields with existing data
                    $('#edit_id').val(data.id);
                    $('#edit_degree').val(data.degree);
                    $('#edit_university').val(data.university);
                    $('#edit_graduation_year').val(data.graduation_year);
                    $('#edit_major').val(data.major);

                    // Show the modal
                    $('#editModal').modal('show');
                },
                error: function(err) {
                    alert('Error fetching data for edit');
                }
            });
        });

        // Handle form submission for updating the record
        $('#editEducationForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            var id = $('#edit_id').val();
            $.ajax({
                url: '{{ route("education-background.update", ":id") }}'.replace(':id', id),
                type: 'PUT',
                data: $(this).serialize(),
                success: function(response) {
                    // $('#editModal').modal('hide'); // Hide the modal
                    table.ajax.reload(); // Reload DataTable
                    $('#editModal').modal('hide'); // Hide the modal
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        timer: 2000
                    });
                },
                error: function(err) {
                    alert('Error updating record');
                }
            });
        });

        // Handle delete button click
        $('#educationTable').on('click', '.btn-delete', function() {
            var id = $(this).data('id');
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
                    url: '{{ route("education-background.destroy", ":id") }}'.replace(':id', id),
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
