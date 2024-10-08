<x-learning-interest-modal.learning-interest-modal :users="$users"/>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Learning Interests</h2>
            </div>
            <div class="card-body">
                <form id="learningInterestForm">
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

                    <div id="learningInterestsFields">
                        <div class="learning-interest-entry row mb-3">
                            <div class="col-md-5">
                                <label for="interest">Learning Interest:</label>
                                <input type="text" class="form-control" name="interest[]" placeholder="Learning Interest" required>
                            </div>
                            <div class="col-md-5">
                                <label for="completed_course">Completed Course:</label>
                                <input type="text" class="form-control" name="completed_course[]" placeholder="Completed Course" required>
                            </div>
{{--                            <div class="col-md-2 mt-4">--}}
{{--                                <label>&nbsp;</label>--}}
{{--                                <button type="button" class="btn btn-danger removeInterest">Remove</button>--}}
{{--                            </div>--}}
                        </div>
                    </div>

                    <button type="button" id="addInterest" class="btn btn-success">Add Another Interest</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Display Learning Interests in a DataTable -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Learning Interests List</h2>
            </div>
            <div class="card-body">
                <table id="learningInterestsTable" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th style="width: 10%">Name</th>
                        <th style="width: 10%">Interest</th>
                        <th style="width: 10%">Completed Course</th>
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
        // Add more learning interest fields
        $('#addInterest').click(function() {
            let interestRow = `
            <div class="learning-interest-entry row mb-3">
                <div class="col-md-5">
                    <label for="interest">Learning Interest:</label>
                    <input type="text" class="form-control" name="interest[]" placeholder="Learning Interest" required>
                </div>
                <div class="col-md-5">
                    <label for="completed_course">Completed Course:</label>
                    <input type="text" class="form-control" name="completed_course[]" placeholder="Completed Course" required>
                </div>
                <div class="col-md-2 mt-4">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-danger removeInterest">Remove</button>
                </div>
            </div>`;
            $('#learningInterestsFields').append(interestRow);
        });

        // Remove learning interest field
        $(document).on('click', '.removeInterest', function() {
            $(this).closest('.learning-interest-entry').remove();
        });

        // Submit the form using AJAX
        $('#learningInterestForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route("learning-interest.store") }}', // Update with your route
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.message) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Learning interests added successfully!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            $('#learningInterestForm')[0].reset();
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

        // Initialize DataTable to display learning interests
        var learningInterestTable = $('#learningInterestsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("learning-interest.index") }}', // Update with your route
            columns: [
                { data: 'users.name', name: 'users.name' },
                { data: 'interest', name: 'interest' },
                { data: 'completed_course', name: 'completed_course' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        });

        // Edit learning interest
        $(document).on('click', '.additional_info-edit', function () {
            const id = $(this).data('id');

            $.ajax({
                url: '{{ route("learning-interest.edit", ":id") }}'.replace(':id', id), // Your API route
                type: 'GET',
                success: function (data) {
                    // alert(data.id);
                    console.log(data);
                    $('#edit_interest_id').val(data.id);
                    $('#edit_interest').val(data.interest);
                    $('#edit_completed_course').val(data.completed_course);
                    $('#edit_user_id option').each(function () {
                        $(this).prop('selected', $(this).val() == data.user_id);
                    });
                    $('#edit_user_id').val(data.user_id);

                    $('#editLearningInterestModal').modal('show');
                }
            });
        });

        // Update learning interest
        $(document).on('submit', '#editLearningInterestForm', function (e) {
            e.preventDefault();
            const formData = $(this).serialize();
            const id = $('#edit_interest_id').val();
            $.ajax({
                url: '{{ route("learning-interest.update", ":id") }}'.replace(':id', id),
                type: 'PUT',
                data: formData,
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.success,
                        confirmButtonText: 'OK',
                    }).then(() => {
                        $('#editLearningInterestModal').modal('hide');
                        learningInterestTable.ajax.reload();
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

        // Delete learning interest
        $(document).on('click', '.learning_interest-delete', function () {
            const id = $(this).data('id');
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
                        url: '{{ route("learning-interest.delete", ":id") }}'.replace(':id', id),
                        type: 'DELETE',
                        success: function (response) {
                            // alert(response);
                            // debugger;
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.success,
                                timer: 2000
                            })
                            console.log('response');
                            console.log(response);
                            learningInterestTable.ajax.reload();
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
