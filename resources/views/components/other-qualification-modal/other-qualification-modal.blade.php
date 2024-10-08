<div class="modal fade" id="editOtherQualificationModal" tabindex="-1" role="dialog" aria-labelledby="editOtherQualificationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editOtherQualificationForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editOtherQualificationModalLabel">Edit Other Qualification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_qualification_id" name="edit_qualification_id">

                    <!-- User Dropdown -->
                    <div class="form-group">
                        <label for="edit_user_id">User Name</label>
                        <select id="edit_user_id" name="user_id" class="form-control" required>
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $user->id == old('user_id', '') ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Qualification Name -->
                    <div class="form-group">
                        <label for="edit_qualification_name">Qualification Name</label>
                        <input type="text" id="edit_qualification_name" name="qualification_name" class="form-control" placeholder="Enter qualification name" required>
                    </div>

                    <!-- Passing Year -->
                    <div class="form-group">
                        <label for="edit_passing_year">Passing Year</label>
                        <input type="date" id="edit_passing_year" name="passing_year" class="form-control">
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
