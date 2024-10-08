<x-notable-project-modal.notable-project-modal :users="$users"/>

<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Notable Projects</h2>
                </div>
                <div class="card-body">
                    <form id="notableProjectForm">
                        @csrf
                        <div class="form-group">
                            <label for="user_id">Name:</label>
                            <select class="form-control" name="user_id" id="user_id" required>
                                <option value="">Select Name</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="notableProjectsFields">
                            <div class="notable-project-entry row mb-3">
                                <div class="col-md-10">
                                    <label for="notable_project_name">Project Name:</label>
                                    <input type="text" class="form-control" name="notable_project_name[]" placeholder="Project Name" required>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label for="notable_project_description">Project Description:</label>
                                    <textarea class="form-control" name="notable_project_description[]" placeholder="Project Description " required></textarea>
                                </div>
{{--                                <div class="col-md-2 mt-4">--}}
{{--                                    <label>&nbsp;</label>--}}
{{--                                    <button type="button" class="btn btn-danger removeProject">Remove</button>--}}
{{--                                </div>--}}
                            </div>
                        </div>

                        <button type="button" id="addProject" class="btn btn-success">Add Another Project</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Display Notable Projects in a DataTable -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Notable Projects List</h2>
                </div>
                <div class="card-body">
                    <table id="notableProjectsTable" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th style="width: 10%">Name</th>
                            <th style="width: 10%">Project Name</th>
                            <th style="width: 70%">Project Description</th>
                            <th style="width: 10%">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- DataTables will populate this section -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<script>
    $(document).ready(function() {
        // Add more project fields
        $('#addProject').click(function() {
            let projectRow = `
            <div class="notable-project-entry row mb-3">
                <div class="col-md-10">
                    <label for="notable_project_name">Project Name:</label>
                    <input type="text" class="form-control" name="notable_project_name[]" placeholder="Project Name" required>
                </div>
                <div class="col-md-12 mt-2">
                    <label for="notable_project_description">Project Description:</label>
                    <textarea class="form-control" name="notable_project_description[]" placeholder="Project Description" required></textarea>
                </div>
                <div class="col-md-2 mt-4">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-danger removeProject">Remove</button>
                </div>
            </div>`;
            $('#notableProjectsFields').append(projectRow);
        });

        // Remove project field
        $(document).on('click', '.removeProject', function() {
            $(this).closest('.notable-project-entry').remove();
        });

        // Submit the form using AJAX
        $('#notableProjectForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route("notable-project.store") }}', // Update with your route
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.message) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Skills added successfully!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            $('#notableProjectForm')[0].reset();
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred!',
                            icon: 'error',
                            confirmButtonText: 'Try Again'
                        });
                    }
                },
                error: function(response) {
                    alert('Failed to submit the form.');
                }
            });
        });

        // Initialize DataTable to display notable projects
        var notableProjectTable=$('#notableProjectsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("notable-project.index") }}', // Update with your route
            columns: [
                { data: 'users.name', name: 'users.name' },
                { data: 'notable_project_name', name: 'notable_project_name' },
                { data: 'notable_project_description', name: 'notable_project_description' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        });
        $(document).on('click', '.notable_project-edit', function () {
            const id = $(this).data('id');

            $.ajax({
                url: '{{ route("notable-project.edit", ":id") }}'.replace(':id', id), // Your API route
                type: 'GET',
                success: function (data) {
                    $('#edit_project_id').val(data.id);
                    // alert(skillId);
                    $('#edit_project_name').val(data.notable_project_name);
                    $('#edit_project_description').val(data.notable_project_description);
                    $('.edit_user_id option').each(function () {
                        $(this).prop('selected', $(this).val() == data.user_id);
                    });
                    $('#edit_user_id').val(data.user_id);

                    $('#editNotableProjectModal').modal('show');
                 }
            });
        });
        $(document).on('submit', '#editNotableProjectForm', function (e) {
            e.preventDefault();
            const formData = $(this).serialize();
            const id = $('#edit_project_id').val();
            alert(id);
            $.ajax({
                url: '{{ route("notable-project.update", ":id") }}'.replace(':id', id),
                type: 'PUT',
                data: formData,
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.success,
                        confirmButtonText: 'OK',
                    }).then(() => {
                        $('#editNotableProjectModal').modal('hide');
                        notableProjectTable.ajax.reload();
                    });
                },
                error: function (xhr) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = Object.values(errors).flat().join(', ');

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                        confirmButtonText: 'OK',
                    });
                }
            });
        });
        $(document).on('click', '.notable_project-delete', function () {
            const id = $(this).data('id');
            alert(id);
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("notable-project.delete", ":id") }}'.replace(':id', id),
                        type: 'DELETE',
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.success,
                                timer: 2000
                            });
                            notableProjectTable.ajax.reload();
                        },
                        error: function () {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Unable to delete skill information!',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });
    });
</script>
