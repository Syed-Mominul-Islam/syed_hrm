<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Interpersonal Skills</h2>
            </div>
            <div class="card-body">
                <form id="interpersonalSkillsForm">
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

                    <div id="interpersonalSkillsFields">
                        <div class="interpersonal-skill-entry row mb-3">
                            <div class="col-md-10">
                                <label for="skill_name">Skill Name:</label>
                                <input type="text" class="form-control" name="skill_name[]" placeholder="Skill Name" required>
                            </div>
                            <div class="col-md-12 mt-2">
                                <label for="description">Description:</label>
                                <textarea class="form-control" name="description[]" placeholder="Skill Description (optional)"></textarea>
                            </div>
{{--                            <div class="col-md-2 mt-4">--}}
{{--                                <label>&nbsp;</label>--}}
{{--                                <button type="button" class="btn btn-danger removeSkill">Remove</button>--}}
{{--                            </div>--}}
                        </div>
                    </div>

                    <button type="button" id="addSkill" class="btn btn-success mt-3 addSkill">Add Another Skill</button>
                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Interpersonal Skills List</h2>
            </div>
            <div class="card-body">
                <table id="interpersonalSkillsTable" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Skill Name</th>
                        <th>Description</th>
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
