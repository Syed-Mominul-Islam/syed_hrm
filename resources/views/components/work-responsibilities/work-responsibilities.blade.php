<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Work Responsibilities</h2>
            </div>
            <div class="card-body">
                <form id="responsibilitiesForm">
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
                    <div class="form-group">
                        <label for="responsibilities">Responsibilities:</label>
                        <textarea class="form-control" id="responsibilities" name="responsibilities" rows="4" required></textarea>
                    </div>
  
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
                <h2>Responsibilities at Tizara</h2>
            </div>
            <div class="card-body">
                <table id="responsibilitiesTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Work Responsibilities</th>
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
