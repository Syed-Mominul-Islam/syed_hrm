@include('component.modal.employeeEducationModal')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Education Background</h2>
            </div>
            <div class="card-body">
                <form id="educationBackgroundForm">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <select class="form-control" name="user_id" id="user_id">
                            <option value="">Select Name</option>
                            @foreach($users as $user)
                                <option value="{{$user->id}}">{{$user->name }}</option>
                            @endforeach
                          </select>
                      
                    </div>
                    <div class="form-group">
                        <label for="degree">Degree:</label>
                        <input type="text" class="form-control" id="degree" name="degree" required>
                    </div>
                    <div class="form-group">
                        <label for="university">University:</label>
                        <input type="text" class="form-control" id="university" name="university" required>
                    </div>
                     <div class="form-group">
                        <label for="graduation_year">Graduation Year:</label>
                        <input type="date" class="form-control" id="graduation_year" name="graduation_year" required>
                    </div>
                    <div class="form-group">
                        <label for="major">Major:</label>
                        <input type="text" class="form-control" id="major" name="major" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
                <div id="message" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Education-background</h2>

            </div>
            <div class="card-body">
                 <!-- Table for displaying education backgrounds -->
                <table id="educationTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            {{-- <th>ID</th> --}}
                            <th>Name</th> <!-- Name from users -->
                            <th>Degree</th>
                            <th>University</th>
                            <th>Graduation Year</th>
                            <th>Major</th>
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