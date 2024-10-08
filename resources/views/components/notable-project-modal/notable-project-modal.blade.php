<!-- Edit Notable Project Modal -->
<div class="modal fade" id="editNotableProjectModal" tabindex="-1" role="dialog" aria-labelledby="editNotableProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editNotableProjectForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editNotableProjectModalLabel">Edit Notable Project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_project_id" name="edit_project_id">

                    <!-- User Dropdown -->
                    <div class="form-group">
                        <label for="edit_user_id">User Name</label>
                        <select id="edit_user_id" name="user_id" class="form-control edit_user_id" required>
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $user->id == old('user_id','') ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Project Name -->
                    <div class="form-group">
                        <label for="edit_project_name">Project Name</label>
                        <input type="text" id="edit_project_name" name="notable_project_name" class="form-control" required>
                    </div>

                    <!-- Project Description -->
                    <div class="form-group">
                        <label for="edit_project_description">Project Description</label>
                        <textarea id="edit_project_description" name="notable_project_description" class="form-control" rows="4"></textarea>
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
