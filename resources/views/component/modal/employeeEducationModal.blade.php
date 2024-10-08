<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Education Background</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editEducationForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_degree" class="form-label">Degree</label>
                        <input type="text" class="form-control" id="edit_degree" name="degree" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_university" class="form-label">University</label>
                        <input type="text" class="form-control" id="edit_university" name="university" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_graduation_year" class="form-label">Graduation Year</label>
                        <input type="date" class="form-control" id="edit_graduation_year" name="graduation_year" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_major" class="form-label">Major</label>
                        <input type="text" class="form-control" id="edit_major" name="major" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>
