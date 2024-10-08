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