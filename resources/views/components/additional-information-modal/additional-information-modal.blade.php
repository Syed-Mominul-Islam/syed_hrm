<!-- Modal for Editing Additional Information -->
<div class="modal fade" id="editAdditionalInfoModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Additional Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editAdditionalInfoForm">
                    @csrf  <!-- Add CSRF Token -->
                    <input type="hidden" name="id" id="editId"> <!-- Hidden field for ID -->
                    <div class="form-group">
                        <label for="edit_user_id">Name:</label>
                        <select class="form-control edit_user_id" name="user_id" id="edit_user_id" required>
                            <option value="">Select Name</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editLanguagesKnown" class="form-label">Languages Known</label>
                        <input type="text" class="form-control" id="editLanguagesKnown" name="languages_known" required>
                    </div>
                    <div class="mb-3">
                        <label for="editHobbies" class="form-label">Hobbies</label>
                        <input type="text" class="form-control" id="editHobbies" name="hobbies" required>
                    </div>
                    <div class="mb-3">
                        <label for="editVolunteerWork" class="form-label">Volunteer Work</label>
                        <input type="text" class="form-control" id="editVolunteerWork" name="volunteer_work" required>
                    </div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
