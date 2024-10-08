@extends('layouts.master')
@section('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.19.0/ckeditor.js"
            integrity="sha512-tjxUra6WjSA8H5+nC7G61SVqEXj1e958LdR4N8BGZeRx9tObm/YhsrUzY6tH4EuHQyZqOyu317pgV7f8YPFoAQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@endsection
@section('content')
    <!-- Edit Employee Modal -->
    {{-- Edit Other Qualification Modal --}}
    <!-- Edit Employee Modal -->
    <x-other-qualification-modal.other-qualification-modal :users="$users"/>
    <x-work-responsibilities-modal.work-responsibilities-modal :users="$users"/>
    <x-job-experience-modal.job-experience-modal :users="$users"/>
    <x-professional-skill-modal.professional-skill-modal :users="$users"/>

    <x-interpersonal-skill-modal.interpersonal-skill-modal :users="$users"/>
    <div class="page-wrapper">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">CV Create</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">CV</li>
                        </ul>
                    </div>
                </div>
            </div>

            @include('component.employeeInformation')
            @include('component.employeeEducation')
            <x-other-qualification.other-qualifications :users="$users"/>
            <x-work-responsibilities.work-responsibilities :users="$users"/>
            <x-job-experience.job-experience :users="$users"/>
            <x-professional-skill.professional-skill :users="$users"/>
            <x-interpersonal-skill.interpersonal-skill :users="$users"/>
            <x-notable-project.notable-project-table :users="$users"/>
            <x-learning-interest.learning-interest :users="$users"/>
            <x-additional-information.additional-information :users="$users"/>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

    {{-- <script src="{{asset('js/employee_information.js')}}"></script> --}}
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        $(document).ready(function () {
            var table = $('#employeeTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("personal.information") }}',
                pageLength: 5,
                columns: [
                    {data: 'id', name: 'id'},
                    // { data: 'user_id', name: 'user_id' },
                    {data: 'name', name: 'name'},
                    {data: 'date_of_birth', name: 'date_of_birth'},
                    {data: 'address', name: 'address'},
                    {data: 'contact_number', name: 'contact_number'},
                    {data: 'email', name: 'email'},
                    {data: 'nationality', name: 'nationality'},
                    {data: 'marital_status', name: 'marital_status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });


            // Edit Employee Information
            $('#employeeTable').on('click', '.edit', function () {
                var employeeId = $(this).data('id');
                $.ajax({
                    url: '{{ route("employee_information.edit", ":id") }}'.replace(':id', employeeId),
                    type: 'GET',
                    success: function (data) {
                        $('#edit_employee_id').val(data.id);
                        $('#edit_user_id').val(data.user_id); // Populate dropdown
                        $('#edit_name').val(data.name);
                        $('#edit_date_of_birth').val(data.date_of_birth);
                        $('#edit_address').val(data.address);
                        $('#edit_contact_number').val(data.contact_number);
                        $('#edit_email').val(data.email);
                        $('#edit_nationality').val(data.nationality);
                        $('#edit_marital_status').val(data.marital_status);
                        $('#edit_user_id option').each(function () {
                            if ($(this).val() == data.user_id) {
                                $(this).prop('selected', true);
                            } else {
                                $(this).prop('selected', false);
                            }
                        });
                        $('#editEmployeeModal').modal('show');
                    }
                });
            });


            // Update Employee Information
            $('#editEmployeeForm').on('submit', function (event) {
                event.preventDefault();
                var employeeId = $('#edit_employee_id').val();
                $.ajax({
                    url: '{{ route("employee_information.update", ":id") }}'.replace(':id', employeeId),
                    type: 'POST',
                    data: $(this).serialize() + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            timer: 2000
                        });
                        table.ajax.reload();
                        $('#editEmployeeModal').modal('hide');
                    },
                    error: function (xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '<ul>';
                        $.each(errors, function (key, value) {
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
            $('#employeeTable').on('click', '.delete', function () {
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
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message,
                                    timer: 2000
                                });
                                table.ajax.reload();
                            },
                            error: function () {
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

    <script>
        $(document).ready(function () {
            $('#employeeForm').on('submit', function (event) {
                event.preventDefault(); // Prevent the default form submission

                // Gather form data
                var formData = $(this).serialize();

                $.ajax({
                    url: '{{ route("employee_information.store") }}', // Your route to the controller method
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        console.log(response);
                        // Use SweetAlert for success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            confirmButtonText: 'Okay',
                            willClose: () => {
                                table.ajax.reload(); // Refresh the DataTable when the modal closes
                            }
                        });
                        // table.ajax.reload();
                        window.location.reload();
                        $('#employeeForm')[0].reset(); // Reset the form
                        // table.ajax.reload();
                    },
                    error: function (xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '<ul>';
                        $.each(errors, function (key, value) {
                            errorMessage += '<li>' + value[0] + '</li>'; // Display the first error message for each field
                        });
                        errorMessage += '</ul>';

                        // Use SweetAlert for error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: errorMessage, // Use HTML to display the error messages
                            confirmButtonText: 'Okay'
                        });
                    }
                });
            });
            $('#educationBackgroundForm').on('submit', function (event) {
                event.preventDefault(); // Prevent the default form submission

                alert('fdsaf');
                var formData = $(this).serialize();
                console.log(formData);
                $.ajax({
                    url: '{{ route("education_background.store") }}', // Your route to the controller method
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        console.log(response);
                        // Use SweetAlert for success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            confirmButtonText: 'Okay',
                            willClose: () => {
                                educationTable.ajax.reload(); // Refresh the DataTable when the modal closes
                            }
                        });
                        window.location.reload();

                        $('#educationBackgroundForm')[0].reset(); // Reset the form
                    },
                    error: function (xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '<ul>';
                        $.each(errors, function (key, value) {
                            errorMessage += '<li>' + value[0] + '</li>'; // Display the first error message for each field
                        });
                        errorMessage += '</ul>';

                        // Use SweetAlert for error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: errorMessage, // Use HTML to display the error messages
                            confirmButtonText: 'Okay'
                        });
                    }
                });
            });
        });

    </script>
    <script>
        $(document).ready(function () {
            // Initialize DataTable
            const educationTable = $('#educationTable').DataTable({
                processing: true,
                serverSide: true,
                cache: false,
                responsive: true,
                ajax: {
                    url: '{{ route('education-background.index') }}',
                    type: 'GET',
                },
                columns: [
                    // { data: 'id', name: 'id' },
                    {data: 'users.name', name: 'users.name'}, // Access the user's name
                    {data: 'degree', name: 'degree'},
                    {data: 'university', name: 'university'},
                    {data: 'graduation_year', name: 'graduation_year'},
                    {data: 'major', name: 'major'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false}

                ]
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Handle edit button click
            $('#educationTable').on('click', '.btn-edit', function () {
                var id = $(this).data('id');

                // Fetch data for the selected entry
                $.ajax({
                    url: '{{ route("education-background.edit", ":id") }}'.replace(':id', id),
                    type: 'GET',
                    success: function (data) {
                        // Populate the modal fields with existing data
                        $('#edit_id').val(data.id);
                        $('#edit_degree').val(data.degree);
                        $('#edit_university').val(data.university);
                        $('#edit_graduation_year').val(data.graduation_year);
                        $('#edit_major').val(data.major);
                        $('#edit_user_id option').each(function () {
                            if ($(this).val() == data.user_id) {
                                $(this).prop('selected', true);
                            } else {
                                $(this).prop('selected', false);
                            }
                        });
                        // Show the modal
                        $('#editModal').modal('show');
                    },
                    error: function (err) {
                        alert('Error fetching data for edit');
                    }
                });
            });

            // Handle form submission for updating the record
            $('#editEducationForm').on('submit', function (e) {
                e.preventDefault(); // Prevent the default form submission

                var id = $('#edit_id').val();
                $.ajax({
                    url: '{{ route("education-background.update", ":id") }}'.replace(':id', id),
                    type: 'PUT',
                    data: $(this).serialize(),
                    success: function (response) {
                        // $('#editModal').modal('hide'); // Hide the modal
                        educationTable.ajax.reload(); // Reload DataTable
                        $('#editModal').modal('hide'); // Hide the modal
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            timer: 2000
                        });
                    },
                    error: function (err) {
                        alert('Error updating record');
                    }
                });
            });

            // Handle delete button click
            $('#educationTable').on('click', '.btn-delete', function () {
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
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message,
                                    timer: 2000
                                });
                                educationTable.ajax.reload();
                            },
                            error: function () {
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
    <script>
        $(document).ready(function () {
            // Function to add a new qualification entry
            $('#addQualification').click(function () {
                $('#qualificationFields').append(`
                <div class="qualification-entry row mb-3">
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="qualification_name[]" placeholder="Qualification Name" required>
                    </div>
                    <div class="col-md-5">
                        <input type="date" class="form-control" name="passing_year[]" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger removeQualification">Remove</button>
                    </div>
                </div>
            `);
            });

            // Function to remove a qualification entry
            $(document).on('click', '.removeQualification', function () {
                // $(this).closest('.qualification-entry').remove();
                if ($('.qualification-entry').length > 1) {
                    $(this).closest('.qualification-entry').remove();
                } else {
                    alert('At least one qualification entry is required.');
                }
            });
            $('#otherQualificationForm').on('submit', function (event) {
                event.preventDefault(); // Prevent the form from submitting normally
                // alert('load');
                let formData = $(this).serialize(); // Serialize the form data

                $.ajax({
                    url: '{{ route("other_qualifications.store") }}',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token
                    },
                    success: function (response) {
                        console.log(response);
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            timer: 2000
                        });
                        // Optionally clear the form or reload the page
                        // $('#otherQualificationForm')[0].reset();
                        // $('#qualificationFields').empty(); // Reset the dynamic fields

                        otherQalification.ajax.reload();
                    },
                    error: function (xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = '<ul>';
                        console.log(xhr);
                        $.each(errors, function (key, value) {
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

        });
    </script>
    <script>
        $(document).ready(function () {
            const otherQalification = $('#otherQalification').DataTable({
                processing: true,
                serverSide: true,
                cache: false,
                responsive: true,
                ajax: {
                    url: '{{ route('other-qualifications.index') }}',
                    type: 'GET',
                },
                columns: [
                    // { data: 'id', name: 'id' },
                    {data: 'users.name', name: 'users.name'}, // Access the user's name
                    {data: 'qualification_name', name: 'qualification_name'},
                    {data: 'passing_year', name: 'passing_year'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false}

                ]
            });
            $('#otherQalification').on('click', '.otherQalification-edit', function () {
                var id = $(this).data('id');
                // alert(id);
                $.ajax({
                    url: '{{ route("other-qualifications.edit", ":id") }}'.replace(':id', id),
                    type: 'GET',
                    success: function (data) {
                        console.log(data);
                        $('#edit_qualification_id').val(data.id);

                        $('#edit_user_id').val(data.user_id); // Populate dropdown
                        $('#edit_qualification_name').val(data.qualification_name);
                        $('#edit_passing_year').val(data.passing_year);
                        $('#edit_user_id option').each(function () {
                            if ($(this).val() == data.user_id) {
                                $(this).prop('selected', true);
                            } else {
                                $(this).prop('selected', false);
                            }
                        });
                        $('#editOtherQualificationModal').modal('show');
                    }
                });
            });
            $('#editOtherQualificationForm').on('submit', function (e) {
                e.preventDefault();

                // Collect form data
                var id = $('#edit_qualification_id').val();
                console.log(id);
                var formData = {
                    _token: $('input[name=_token]').val(), // CSRF token
                    user_id: $('#edit_user_id').val(),
                    qualification_name: $('#edit_qualification_name').val(),
                    passing_year: $('#edit_passing_year').val()
                };

                // Send AJAX request
                $.ajax({
                    url: '{{ route("other-qualifications.update", ":id") }}'.replace(':id', id), // URL for update route
                    method: 'PUT', // or PUT depending on your route
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            // Show success message using SweetAlert
                            Swal.fire({
                                title: 'Success!',
                                text: 'Qualification updated successfully.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Optionally reload the page or update the table dynamically
                                    $('#editOtherQualificationModal').modal('hide');
                                    // For example, you could reload the table or part of the page
                                    // location.reload();
                                    otherQalification.ajax.reload();
                                }
                            });
                        } else {
                            // Show error message using SweetAlert
                            Swal.fire({
                                title: 'Error!',
                                text: response.message || 'An error occurred while updating the qualification.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function (xhr) {
                        // Handle error with SweetAlert
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred: ' + xhr.responseText,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
            $('#otherQalification').on('click', '.otherQalification-delete', function () {
                // alert('je');
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
                            url: '{{ route("other-qualifications.destroy", ":id") }}'.replace(':id', id),
                            type: 'DELETE',
                            data: {
                                "_token": $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                            },
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message,
                                    timer: 2000
                                });
                                otherQalification.ajax.reload();
                            },
                            error: function () {
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
    <script>
        $(document).ready(function () {
            // alert('working');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            const responsibilitiesTable = $('#responsibilitiesTable').DataTable({
                processing: true,
                serverSide: true,
                cache: false,
                responsive: true,
                ajax: {
                    url: '{{ route('work-responsibilities.index') }}',
                    type: 'GET',
                },
                columns: [
                    // { data: 'id', name: 'id' },
                    {data: 'users.name', name: 'users.name'}, // Access the user's name
                    {data: 'responsibilities', name: 'responsibilities'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false}

                ]
            });

            // form
            $('#responsibilitiesTable').on('click', '.workresponsibilities-edit', function () {
                var id = $(this).data('id'); // Get the ID from the clicked button
                console.log('Editing responsibilities for ID:', id); // Log the ID for debugging

                $.ajax({
                    url: '{{ route("work-responsibilities.edit", ":id") }}'.replace(':id', id),
                    type: 'GET',
                    success: function (data) {
                        // Assuming `data` contains the work responsibilities details
                        console.log('Fetched data:', data); // Log the fetched data for debugging

                        // Populate the form fields in the modal
                        $('#edit_responsibilities_id').val(data.id);
                        // $('#edit_user_id').val(data.user_id); // Populate user dropdown
                        $('#edit_user_id option').each(function () {
                            if ($(this).val() == data.user_id) {
                                $(this).prop('selected', true);
                            } else {
                                $(this).prop('selected', false);
                            }
                        });
                        $('#edit_user_id').val(data.user_id);
                        // alert(data.user_id);
                        $('#edit_responsibilities').val(data.responsibilities); // Populate responsibilities textarea

                        // Show the modal
                        $('#editWorkResponsibilitiesModal').modal('show');
                    },
                    error: function (xhr, status, error) {
                        // Handle any errors
                        console.error('Error fetching responsibilities:', error); // Log the error
                        alert('Failed to load responsibilities. Please try again later.'); // Show user-friendly error message
                    }
                });
            });
            $('#responsibilitiesForm').on('submit', function (event) {
                event.preventDefault(); // Prevent the form from submitting normally
                // alert('load');
                let formData = $(this).serialize(); // Serialize the form data
                console.log(formData);
                $.ajax({
                    url: '{{ route("work-responsibilities.store") }}',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token
                    },
                    success: function (response) {
                        console.log(response);
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            timer: 2000
                        });
                        // Optionally clear the form or reload the page
                        $('#responsibilitiesForm')[0].reset();
                        responsibilitiesTable.ajax.reload();
                    },
                    error: function (xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = '<ul>';
                        console.log(xhr);
                        $.each(errors, function (key, value) {
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

            $('#editWorkResponsibilitiesForm').on('submit', function (e) {
                e.preventDefault();

                // Collect form data
                var id = $('#edit_responsibilities_id').val();
                // alert(id);
                console.log(id);
                alert($('.edit_user_id').val());
                var formData = {
                    _token: $('input[name=_token]').val(), // CSRF token
                    user_id: $('.edit_user_id').val(),
                    responsibilities: $('#edit_responsibilities').val(),
                    // passing_year: $('#edit_passing_year').val()
                };
                console.log(formData);


                // Send AJAX request
                $.ajax({
                    url: '{{ route("work-responsibilities.update", ":id") }}'.replace(':id', id), // URL for update route
                    method: 'PUT', // or PUT depending on your route
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            // Show success message using SweetAlert
                            Swal.fire({
                                title: 'Success!',
                                text: 'Responsibilities updated successfully.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Optionally reload the page or update the table dynamically
                                    $('#editWorkResponsibilitiesModal').modal('hide');
                                    // For example, you could reload the table or part of the page
                                    // location.reload();
                                    responsibilitiesTable.ajax.reload();
                                }
                            });
                        } else {
                            // Show error message using SweetAlert
                            Swal.fire({
                                title: 'Error!',
                                text: response.message || 'An error occurred while updating the qualification.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function (xhr) {
                        // Handle error with SweetAlert
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred: ' + xhr.responseText,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
            // workresponsibilities-delete
            $('#responsibilitiesTable').on('click', '.workresponsibilities-delete', function () {
                alert('working delete');
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
                            url: '{{ route("work-responsibilities.delete", ":id") }}'.replace(':id', id),
                            type: 'DELETE',
                            data: {
                                "_token": $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                            },
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message,
                                    timer: 2000
                                });
                                responsibilitiesTable.ajax.reload();
                            },
                            error: function () {
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
    <script>
        $(document).ready(function () {
            var JobExperienceTable = $('#jobExperienceTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("job-experience.index") }}',
                pageLength: 5,
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'company_name', name: 'company_name'},
                    {data: 'designation', name: 'designation'},
                    {data: 'date', name: 'date'},
                    {
                        data: 'key_responsibilities',
                        name: 'key_responsibilities',
                        render: function (data, type, row) {
                            // Render the data as raw HTML (do not escape)
                            return data;
                        }
                    },
                    {data: 'actions', name: 'actions', orderable: false, searchable: false}
                ]
            });

            // Initialize CKEditor for existing textareas
            $('textarea.ckeditor').each(function () {
                CKEDITOR.replace(this);
            });

            // Add Job Experience
            $('#addJobExperience').on('click', function () {
                const jobExperienceEntry = `
            <div class="job-experience-entry row mb-3">
                <div class="col-md-4">
                    <label for="company_name">Company Name:</label>
                    <input type="text" class="form-control" name="company_name[]" placeholder="Company Name" required>
                </div>
                <div class="col-md-4">
                    <label for="designation">Designation:</label>
                    <input type="text" class="form-control" name="designation[]" placeholder="Designation" required>
                </div>
                <div class="col-md-4">
                    <label for="date">Date:</label>
                    <input type="date" class="form-control" name="date[]" required>
                </div>
                <div class="col-md-12 mt-2">
                    <label for="key_responsibilities">Key Responsibilities:</label>
                    <textarea class="form-control ckeditor" name="key_responsibilities[]" placeholder="Key Responsibilities" required></textarea>
                </div>
                <div class="col-md-2 mt-4">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-danger removeJobExperience">Remove</button>
                </div>
            </div>`;
                $('#jobExperienceFields').append(jobExperienceEntry);

                // Initialize CKEditor for the newly added textarea
                CKEDITOR.replace($('.ckeditor').last()[0]);
            });

            // Remove Job Experience
            $(document).on('click', '.removeJobExperience', function () {
                const textareaName = $(this).closest('.job-experience-entry').find('textarea[name="key_responsibilities[]"]').attr('name');
                if (CKEDITOR.instances[textareaName]) {
                    CKEDITOR.instances[textareaName].destroy(); // Destroy the CKEditor instance before removing the element
                }
                $(this).closest('.job-experience-entry').remove();
            });

            // Submit the form
            $('#jobExperienceForm').on('submit', function (event) {
                event.preventDefault(); // Prevent the default form submission

                // Gather form data
                var formData = $(this).serializeArray(); // Use serializeArray for better handling of array inputs

                // Validate all key_responsibilities fields
                let allValid = true;
                $('textarea[name^="key_responsibilities"]').each(function () {
                    if (!CKEDITOR.instances[$(this).attr('name')].getData().trim()) {
                        allValid = false;
                        $(this).addClass('is-invalid'); // Add invalid class for visual feedback
                    } else {
                        $(this).removeClass('is-invalid'); // Remove invalid class if filled
                    }
                });

                if (!allValid) {
                    // Optionally show an alert or error message indicating required fields
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please fill all Key Responsibilities fields.',
                        confirmButtonText: 'Okay'
                    });
                    return; // Prevent form submission if validation fails
                }

                // Collect data from CKEditor instances
                formData = formData.map(function (field) {
                    if (field.name.startsWith('key_responsibilities')) {
                        field.value = CKEDITOR.instances[field.name].getData();
                    }
                    return field;
                });

                $.ajax({
                    url: '{{ route("job-experience.store") }}', // Your route to the controller method
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Set the CSRF token
                    },
                    success: function (response) {
                        console.log(response);
                        // Use SweetAlert for success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            confirmButtonText: 'Okay',
                        });
                        window.location.reload(); // Reload the page to show new data
                        $('#jobExperienceForm')[0].reset(); // Reset the form
                    },
                    error: function (xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '<ul>';
                        $.each(errors, function (key, value) {
                            errorMessage += '<li>' + value[0] + '</li>'; // Display the first error message for each field
                        });
                        errorMessage += '</ul>';

                        // Use SweetAlert for error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: errorMessage, // Use HTML to display the error messages
                            confirmButtonText: 'Okay'
                        });
                    }
                });
            });
            $('#jobExperienceTable').on('click', '.job_experiences-edit', function () {
                var id = $(this).data('id');
                alert(id);
                $.ajax({
                    url: '{{ route("job-experience.edit", ":id") }}'.replace(':id', id),
                    type: 'GET',
                    success: function (data) {
                        console.log(data);
                        // Populate the form fields with the fetched data
                        $('#edit_experience_id').val(data.id);
                        $('#edit_user_id').val(data.user_id);
                        $('#edit_user_id option').each(function () {
                            if ($(this).val() == data.user_id) {
                                $(this).prop('selected', true);
                            } else {
                                $(this).prop('selected', false);
                            }
                        });
                        $('#edit_company_name').val(data.company_name);
                        $('#edit_designation').val(data.designation);
                        $('#edit_date').val(data.date);
                        $('#edit_key_responsibilities').val(data.key_responsibilities);
                        // editorInstance.setData(data.key_responsibilities); // Set CKEditor data

                        // console.log(temp);
                        // Show the modal
                        $('#editJobExperienceModal').modal('show');
                    },
                    error: function (err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to fetch data for editing.',
                        });
                    }
                });
            });
            $('#saveJobExperienceChanges').on('click', function () {
                // Get form data
                var formData = $('#editJobExperienceForm').serialize();
                var experienceId = $('#edit_experience_id').val(); // Get experience ID
                console.log(formData)
                $.ajax({
                    url: '{{ route("job-experience.update", ":id") }}'.replace(':id', experienceId), // Replace :id with actual experience ID
                    type: 'PUT',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Set the CSRF token
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: response.message,
                            timer: 2000
                        });
                        // Optionally reload the job experience table or refresh the page
                        JobExperienceTable.ajax.reload(); // Adjust according to your table instance
                        $('#editJobExperienceModal').modal('hide'); // Hide modal
                    },
                    error: function (xhr) {
                        // Handle error response
                        var errors = xhr.responseJSON.errors;
                        var errorMsg = '';
                        if (errors) {
                            $.each(errors, function (key, value) {
                                errorMsg += value[0] + '\n'; // Concatenate error messages
                            });
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMsg || 'Unable to update job experience!'
                        });
                    }
                });
            });
            $('#jobExperienceTable').on('click', '.job_experiences-delete', function () {
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
                            url: '{{ route("job-experience.delete", ":id") }}'.replace(':id', id),
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Set the CSRF token
                            },
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message,
                                    timer: 2000
                                });
                                JobExperienceTable.ajax.reload();
                            },
                            error: function (err) {
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
    <script>
        $(document).ready(function () {
            // Add skill fields dynamically
            $('#addSkill').on('click', function () {
                const skillEntry = `
            <div class="professional-skill-entry row mb-3">
                <div class="col-md-10">
                    <label for="skill_name">Skill Name:</label>
                    <input type="text" class="form-control" name="skill_name[]" placeholder="Skill Name" required>
                </div>
                <div class="col-md-12 mt-2">
                    <label for="description">Description:</label>
                    <textarea class="form-control" name="description[]" placeholder="Skill Description (optional)"></textarea>
                </div>
                <div class="col-md-2 mt-4">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-danger removeSkill">Remove</button>
                </div>
            </div>`;
                $('#professionalSkillsFields').append(skillEntry);
            });

            // Remove skill entry
            $(document).on('click', '.removeSkill', function () {
                $(this).closest('.professional-skill-entry').remove();
            });

            // Initialize DataTable
            const professionalSkillsTable = $('#professionalSkillsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('professional-skill.index') }}", // Replace with your route name
                    type: 'GET'
                },
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'skill_name', name: 'skill_name' },
                    { data: 'description', name: 'description' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ]
            });

            // Handle form submission via AJAX
            $('#professionalSkillsForm').on('submit', function (e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    url: '{{ route("professional-skill.store") }}', // Use your route here
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        if (response.message) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Skills added successfully!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                $('#professionalSkillsForm')[0].reset();
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
                    error: function () {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'Try Again'
                        });
                    }
                });
            });

            // Edit skill functionality
            $(document).on('click', '.professional_skills-edit', function () {
                const id = $(this).data('id');

                $.ajax({
                    url: '{{ route("professional-skill.edit", ":id") }}'.replace(':id', id), // Your API route
                    type: 'GET',
                    success: function (data) {
                        $('#edit_responsibilities_id').val(data.id);
                        $('#edit_skill_name').val(data.skill_name);
                        $('#edit_skill_description').val(data.description);
                        // $('#edit_user_id').val(data.user_id).change();
                        $('#edit_user_id option').each(function () {
                            if ($(this).val() == data.user_id) {
                                $(this).prop('selected', true);
                            } else {
                                $(this).prop('selected', false);
                            }
                        });
                        $('#edit_user_id').val(data.user_id);

                        $('#editProfessionalSkillModal').modal('show');
                    }
                });
            });

            // Update skill functionality
            $(document).on('submit', '#editProfessionalSkillForm', function (e) {
                e.preventDefault();
                const formData = $(this).serialize();
                const id = $('#edit_responsibilities_id').val();

                $.ajax({
                    url: '{{ route("professional-skill.update", ":id") }}'.replace(':id', id),
                    type: 'PUT',
                    data: formData,
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.success,
                            confirmButtonText: 'OK',
                        }).then(() => {
                            $('#editProfessionalSkillModal').modal('hide');
                            professionalSkillsTable.ajax.reload();
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

            // Delete skill functionality
            $(document).on('click', '.professional_skills-delete', function () {
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
                            url: '{{ route("professional-skill.delete", ":id") }}'.replace(':id', id),
                            type: 'DELETE',
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.success,
                                    timer: 2000
                                });
                                professionalSkillsTable.ajax.reload();
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
    <script>
        $(document).ready(function () {
            // alert('loaded inter personal');
            // Add skill fields dynamically
            $('.addSkill').on('click', function () {
                // alert('click');
                const skillEntry = `
            <div class="interpersonal-skill-entry row mb-3">
                <div class="col-md-10">
                    <label for="skill_name">Skill Name:</label>
                    <input type="text" class="form-control" name="skill_name[]" placeholder="Skill Name" required>
                </div>
                <div class="col-md-12 mt-2">
                    <label for="description">Description:</label>
                    <textarea class="form-control" name="description[]" placeholder="Skill Description (optional)"></textarea>
                </div>
                <div class="col-md-2 mt-4">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-danger removeSkill">Remove</button>
                </div>
            </div>`;
                $('#interpersonalSkillsFields').append(skillEntry);
            });

            // Remove skill entry
            $(document).on('click', '.removeSkill', function () {
                $(this).closest('.interpersonal-skill-entry').remove();
            });

            // Initialize DataTable
            const interpersonalSkillsTable = $('#interpersonalSkillsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('interpersonal-skill.index') }}", // Replace with your route name
                    type: 'GET'
                },
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'skill_name', name: 'skill_name' },
                    { data: 'description', name: 'description' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ]
            });

            // Handle form submission via AJAX
            $('#interpersonalSkillsForm').on('submit', function (e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    url: '{{ route("interpersonal-skill.store") }}', // Use your route here
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        if (response.message) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Skills added successfully!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                $('#interpersonalSkillsForm')[0].reset();
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
                    error: function () {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'Try Again'
                        });
                    }
                });
            });

            // Edit skill functionality
            $(document).on('click', '.interpersonal_skills-edit', function () {
                const id = $(this).data('id');

                $.ajax({
                    url: '{{ route("interpersonal-skill.edit", ":id") }}'.replace(':id', id), // Your API route
                    type: 'GET',
                    success: function (data) {
                        console.log(data);
                         $('.edit_interpersonal_skills_id').val(data.id);
                         // alert(skillId);
                        $('.edit_skill_name').val(data.skill_name);
                        $('.edit_skill_description').val(data.description);
                        $('.edit_user_id option').each(function () {
                            $(this).prop('selected', $(this).val() == data.user_id);
                        });
                        $('#edit_user_id').val(data.user_id);

                        $('#editInterpersonalSkillModal').modal('show');
                    }
                });
            });

            // Update skill functionality
            $(document).on('submit', '#editInterpersonalSkillForm', function (e) {
                e.preventDefault();
                const formData = $(this).serialize();
                const id = $('.edit_interpersonal_skills_id').val();

                $.ajax({
                    url: '{{ route("interpersonal-skill.update", ":id") }}'.replace(':id', id),
                    type: 'PUT',
                    data: formData,
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.success,
                            confirmButtonText: 'OK',
                        }).then(() => {
                            $('#editInterpersonalSkillModal').modal('hide');
                            interpersonalSkillsTable.ajax.reload();
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

            // Delete skill functionality
            $(document).on('click', '.interpersonal_skills-delete', function () {
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
                            url: '{{ route("interpersonal-skill.delete", ":id") }}'.replace(':id', id),
                            type: 'DELETE',
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.success,
                                    timer: 2000
                                });
                                interpersonalSkillsTable.ajax.reload();
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
@endsection
