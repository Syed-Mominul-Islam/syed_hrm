<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Other Qualifications</h2>
            </div>
            <div class="card-body">
                <form id="otherQualificationForm">
                    @csrf
                    <div class="form-group">
                        <label for="user_id">Name:</label>
                        <select class="form-control" name="user_id" id="user_id" required>
                            <option value="">Select Name</option>
                            @foreach($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="qualificationFields">
                        <div class="qualification-entry row mb-3">
                            <div class="col-md-5">
                                <label for="qualification_name">Qualification Name:</label>
                                <input type="text" class="form-control" name="qualification_name[]" placeholder="Qualification Name" required>
                            </div>
                            <div class="col-md-5">
                                <label for="passing_year">Passing Year:</label>
                                <input type="date" class="form-control" name="passing_year[]" required>
                            </div>
                            <div class="col-md-2 mt-4">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-danger removeQualification">Remove</button>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="addQualification" class="btn btn-success">Add Another Qualification</button>
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
                <h2>Other Qualifications</h2>
            </div>
            <div class="card-body">
                <table id="otherQalification" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Qualification Name</th>
                            <th>Passing Year</th>
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
