<x-additional-information-modal.additional-information-modal :users="$users"/>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Additional Information</h2>
            </div>
            <div class="card-body">
                <form id="additionalInfoForm">
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

                    <div id="additionalInfoFields">
                        <div class="additional-info-entry row mb-3">
                            <div class="col-md-4">
                                <label for="languages_known">Languages Known:</label>
                                <input type="text" class="form-control" name="languages_known[]" placeholder="Language" required>
                            </div>
                            <div class="col-md-4">
                                <label for="hobbies">Hobbies:</label>
                                <input type="text" class="form-control" name="hobbies[]" placeholder="Hobby" required>
                            </div>
                            <div class="col-md-4">
                                <label for="volunteer_work">Volunteer Work:</label>
                                <input type="text" class="form-control" name="volunteer_work[]" placeholder="Volunteer Work" required>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="addAdditionalInfo" class="btn btn-success">Add Another Info</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Display Additional Information in a DataTable -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Additional Information List</h2>
            </div>
            <div class="card-body">
                <table id="additionalInfoTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Language Known</th>
                            <th>Hobbies</th>
                            <th>Volunteer Work</th>
                            <th>Actions</th>
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
        // Add more additional info fields
        $('#addAdditionalInfo').click(function() {
            let additionalInfoRow = `
            <div class="additional-info-entry row mb-3">
                <div class="col-md-4">
                    <label for="languages_known">Languages Known:</label>
                    <input type="text" class="form-control" name="languages_known[]" placeholder="Language" required>
                </div>
                <div class="col-md-4">
                    <label for="hobbies">Hobbies:</label>
                    <input type="text" class="form-control" name="hobbies[]" placeholder="Hobby" required>
                </div>
                <div class="col-md-4">
                    <label for="volunteer_work">Volunteer Work:</label>
                    <input type="text" class="form-control" name="volunteer_work[]" placeholder="Volunteer Work" required>
                </div>
                <div class="col-md-2 mt-4">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-danger removeInfo">Remove</button>
                </div>
            </div>`;
            $('#additionalInfoFields').append(additionalInfoRow);
        });

        // Remove additional info field
        $(document).on('click', '.removeInfo', function() {
            $(this).closest('.additional-info-entry').remove();
        });

        // Submit the form using AJAX
        $('#additionalInfoForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route("additional-information.store") }}', // Update with your route
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.message) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Additional information added successfully!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            $('#additionalInfoForm')[0].reset();
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

        var additionalInfoTable = $('#additionalInfoTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("additional-information.index") }}', // Update with your route
            columns: [
                { data: 'users.name', name: 'users.name' },
                {data: 'languages_known', name: 'languages_known'},
                {data: 'hobbies', name: 'hobbies'},
                { data: 'volunteer_work', name: 'volunteer_work' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        });
        $(document).on('click', '.additional_info-edit', function () {
            const id = $(this).data('id'); // Get the ID from the data attribute
            alert(id);
            // Make an AJAX request to fetch the data for the selected item
            $.ajax({
                url: '{{ route("additional-information.edit", ":id") }}'.replace(':id', id), // Update with your route
                type: 'GET',
                success: function (data) {
                    // Pre-fill the modal with data
                    $('#editId').val(data.id);
                    $('#editLanguagesKnown').val(data.languages_known);
                    $('#editHobbies').val(data.hobbies);
                    $('#editVolunteerWork').val(data.volunteer_work);
                    $('.edit_user_id option').each(function () {
                        $(this).prop('selected', $(this).val() == data.user_id);
                    });
                    $('#edit_user_id').val(data.user_id);

                    // Open the modal
                    $('#editAdditionalInfoModal').modal('show');
                },
                error: function () {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Unable to fetch additional info!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
        $('#editAdditionalInfoForm').submit(function (e) {
            e.preventDefault();

            const id = $('#editId').val(); // Get the ID from the hidden input field
            const formData = $(this).serialize(); // Serialize form data, including CSRF token

            // Make an AJAX request to update the data
            $.ajax({
                url: '{{ route("additional-information.update", ":id") }}'.replace(':id', id),
                type: 'PUT', // Use PUT for update
                data: formData,
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Additional information updated successfully!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            $('#editAdditionalInfoModal').modal('hide'); // Hide the modal
                            additionalInfoTable.ajax.reload(); // Reload DataTable
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while updating!',
                            icon: 'error',
                            confirmButtonText: 'Try Again'
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to update the additional info!',
                        icon: 'error',
                        confirmButtonText: 'Try Again'
                    });
                }
            });
        });
        // Delete learning interest
        $(document).on('click', '.additional_info-delete', function () {
            const id = $(this).data('id'); // Get the ID from the data attribute
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
                        url: '{{ route("additional-information.delete", ":id") }}'.replace(':id', id),
                        type: 'DELETE',
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.success,
                                timer: 2000
                            });
                            additionalInfoTable.ajax.reload(); // Reload DataTable
                        },
                        error: function () {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Unable to delete learning interest!',
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
