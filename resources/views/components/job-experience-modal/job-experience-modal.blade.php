<!-- Edit Job Experience Modal -->
<div class="modal fade" id="editJobExperienceModal" tabindex="-1" aria-labelledby="editJobExperienceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editJobExperienceModalLabel">Edit Job Experience</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editJobExperienceForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_experience_id" name="experience_id">

                    <div class="form-group">
                        <label for="edit_user_id">Name:</label>
                        <select class="form-control" name="user_id" id="edit_user_id" required>
                            <option value="">Select Name</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="edit_company_name">Company Name:</label>
                            <input type="text" class="form-control" id="edit_company_name" name="company_name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_designation">Designation:</label>
                            <input type="text" class="form-control" id="edit_designation" name="designation" required>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label for="edit_date">Date:</label>
                            <input type="date" class="form-control" id="edit_date" name="date" required>
                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <label for="edit_key_responsibilities">Key Responsibilities:</label>
                        <textarea class="form-control" id="edit_key_responsibilities" name="key_responsibilities" rows="4" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveJobExperienceChanges">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Include CKEditor CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let editorInstance;

        // Function to initialize CKEditor
        function initializeCKEditor() {
            ClassicEditor
                .create(document.querySelector('#edit_key_responsibilities'))
                .then(editor => {
                    editorInstance = editor;
                })
                .catch(error => {
                    console.error(error);
                });
        }

        // Initialize CKEditor when the modal is shown
        $('#editJobExperienceModal').on('shown.bs.modal', function () {
            initializeCKEditor();
        });

        // Destroy CKEditor instance when the modal is hidden
        $('#editJobExperienceModal').on('hidden.bs.modal', function () {
            if (editorInstance) {
                editorInstance.destroy()
                    .then(() => {
                        editorInstance = null;
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        });
    });
</script>
{{--end modal--}}
