<div class="modal fade" id="editProfessionalSkillModal" tabindex="-1" role="dialog" aria-labelledby="editProfessionalSkillModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editProfessionalSkillForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfessionalSkillModalLabel">Edit Professional Skill</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_skill_id" name="edit_skill_id">

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

                    <!-- Skill Name -->
                    <div class="form-group">
                        <label for="edit_skill_name">Skill Name</label>
                        <input type="text" id="edit_skill_name" name="skill_name" class="form-control" required>
                    </div>

                    <!-- Skill Description -->
                    <div class="form-group">
                        <label for="edit_skill_description">Skill Description</label>
                        <textarea id="edit_skill_description" name="description" class="form-control" rows="4" required></textarea>
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
