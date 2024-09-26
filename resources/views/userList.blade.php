@extends('main')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('content')
<div class="container">
    <h1>User List</h1>
    <button class="btn btn-success" id="createUserBtn">Create User</button>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>User Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->user_type }}</td>
                    <td>
                        <!-- Edit Button -->
                        <button class="btn btn-primary editUserBtn" data-id="{{ $user->id }}">Edit</button>

                        <!-- Delete Button -->
                        <form action="javascript:void(0);" method="POST">
                        @csrf
                            <button type="button" class="btn btn-danger" onclick="deleteUser({{ $user->id }})">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        {{ $users->links() }}
    </div>
</div>

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="createUserForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModalLabel">Create User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="createName">Name</label>
                        <input type="text" class="form-control" id="createName" name="name" required>
                        <span class="text-danger" id="nameError"></span>
                    </div>
                    <div class="form-group">
                        <label for="createEmail">Email</label>
                        <input type="email" class="form-control" id="createEmail" name="email" required>
                        <span class="text-danger" id="createEmailError"></span>
                    </div>
                    <div class="form-group">
                        <label for="createPassword">Password</label>
                        <input type="password" class="form-control" id="createPassword" name="password" required>
                        <span class="text-danger" id="createPasswordError"></span>
                    </div>
                    <div class="form-group">
                        <label for="createRole">User Role</label>
                        <select class="form-control" id="createRole" name="user_role">
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->user_type }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editUserForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="editUserId" name="id">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editName">Name</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                        <span class="text-danger" id="editNameError"></span>
                    </div>
                    <div class="form-group">
                        <label for="editEmail">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                        <span class="text-danger" id="editEmailError"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="editRole">User Role</label>
                        <select class="form-control" id="editRole" name="user_role">
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->user_type }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
<script>
$(document).ready(function() {
        // Open Create User Modal
        $('#createUserBtn').on('click', function() {
            $('#createUserModal').modal('show');
        });

        // Submit Create User Form via AJAX
        $('#createUserForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('users.store') }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#createUserModal').modal('hide');
                    if (response.success) {
                        Swal.fire(
                            'Create!',
                            response.message,
                            'success'
                        ).then(() => {
                            location.reload(); // Reload the page to reflect the changes
                        });
                    }
                  //  location.reload(); // Reload the page to show updated user list
                },
                error: function(response) {
                    let errors = response.responseJSON.errors;
                        $('#nameError').text(errors.name ? errors.name[0] : '');
                        $('#createEmailError').text(errors.email ? errors.email[0] : '');
                        $('#createPasswordError').text(errors.password ? errors.password[0] : '');
                }
            });
        });

        // Open Edit User Modal
        $('.editUserBtn').on('click', function() {
            var userId = $(this).data('id');
            $.ajax({
                url: '/users/' + userId + '/edit',
                method: 'GET',
                success: function(response) {
                    // Populate the form fields with response data
                    $('#editUserId').val(response.id);
                    $('#editName').val(response.name);
                    $('#editEmail').val(response.email);
                    $('#editRole').val(response.user_role);

                    $('#editUserModal').modal('show');
                },
                error: function() {
                    alert('Error loading user details');
                }
            });
        });

        $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

        // Submit Edit User Form via AJAX
        $('#editUserForm').on('submit', function(e) {
            e.preventDefault();
            var userId = $('#editUserId').val();
            $.ajax({
                url: '/user/upadte/' + userId,
                method: 'PUT',
                data: $(this).serialize(),
                success: function(response) {
                    $('#editUserModal').modal('hide');
                    if (response.success) {
                        Swal.fire(
                            'Update!',
                            response.message,
                            'success'
                        ).then(() => {
                            location.reload(); // Reload the page to reflect the changes
                        });
                    }
                   // location.reload(); // Reload the page to show updated user list
                },
                error: function(response) {
                    let errors = response.responseJSON.errors;
                    $('#editNameError').text(errors.name ? errors.name[0] : '');
                    $('#editEmailError').text(errors.email ? errors.email[0] : '');
                }
            });
        });
    });
    function deleteUser(userId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/user/delete/' + userId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}' // Include CSRF token
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire(
                            'Deleted!',
                            response.message,
                            'success'
                        ).then(() => {
                            location.reload(); // Reload the page to reflect the changes
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            response.message,
                            'error'
                        );
                    }
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error!',
                        'An error occurred while deleting the user.',
                        'error'
                    );
                }
            });
        }
    });
}
</script>
