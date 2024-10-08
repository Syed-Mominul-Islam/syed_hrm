<!-- Edit Learning Interest Modal -->
<div class="modal fade" id="editLearningInterestModal" tabindex="-1" role="dialog" aria-labelledby="editLearningInterestModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editLearningInterestForm">
                @csrf
                <input type="hidden" id="edit_interest_id" name="edit_interest_id"> <!-- Hidden field for ID -->

                <div class="modal-header">
                    <h5 class="modal-title" id="editLearningInterestModalLabel">Edit Learning Interest</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

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

                    <!-- Interest Field -->
                    <div class="form-group">
                        <label for="edit_interest">Interest</label>
                        <textarea id="edit_interest" name="interest" class="form-control" rows="4" required></textarea>
                    </div>

                    <!-- Completed Course Field -->
                    <div class="form-group">
                        <label for="edit_completed_course">Completed Course</label>
                        <textarea id="edit_completed_course" name="completed_course" class="form-control" rows="4"></textarea>
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
