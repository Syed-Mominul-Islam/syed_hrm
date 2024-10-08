
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Job Experiences</h2>
            </div>
            <div class="card-body">
                <form id="jobExperienceForm">
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

                    <div id="jobExperienceFields">
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
                        </div>
                    </div>

                    <button type="button" id="addJobExperience" class="btn btn-success">Add Another Job Experience</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Job Experiences</h2>
            </div>
            <div class="card-body">
                <table id="jobExperienceTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Company Name</th>
                            <th>Designation</th>
                            <th>Date</th>
                            <th>Key Responsibilities</th>
                            <th>Actions</th>
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


