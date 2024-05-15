<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])) {
    $userId = $_SESSION['user_id'];
    $userName = $_SESSION['user_name'];
} else {
    header("Location: index.php");
    exit();
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Todo List</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Todo List</a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <span class="navbar-text mr-3">Welcome, <?php echo $userName; ?></span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-5">
        <button class="btn btn-success mb-4" data-toggle="modal" data-target="#addModal">Add Todo</button>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Name</th>
                    <th scope="col">Detail</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody id="todolist">
                <?php
                include('connection.php');

                $sql = "SELECT * FROM todolist where createdBy = '$userId'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr id='row-" . $row["todo_id"] . "'>";
                        echo "<th scope='row'>" . $row["todo_id"] . "</th>";
                        echo "<td class='name'>" . $row["todo_name"] . "</td>";
                        echo "<td class='detail'>" . $row["todo_details"] . "</td>";
                        echo "<td>";
                        echo "<button class='btn btn-primary btn-sm edit' data-id='" . $row["todo_id"] . "'>Edit</button> ";
                        echo "<button class='btn btn-danger btn-sm delete' data-id='" . $row["todo_id"] . "'>Delete</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No records found</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add Todo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addForm">
                        <div class="form-group">
                            <label for="addName">Name</label>
                            <input type="text" class="form-control" id="addName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="addDetail">Detail</label>
                            <textarea class="form-control" id="addDetail" name="detail" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Todo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Todo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="editId" name="id">
                        <div class="form-group">
                            <label for="editName">Name</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="editDetail">Detail</label>
                            <textarea class="form-control" id="editDetail" name="detail" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            // Edit button click handler
            $(document).on('click', '.edit', function() {
                var id = $(this).data('id');
                var row = $('#row-' + id);
                var name = row.find('.name').text();
                var detail = row.find('.detail').text();

                $('#editId').val(id);
                $('#editName').val(name);
                $('#editDetail').val(detail);
                $('#editModal').modal('show');
            });

            // Delete button click handler
            $(document).on('click', '.delete', function() {
                if (confirm("Are you sure you want to delete this item?")) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: 'delete.php',
                        type: 'POST',
                        data: {
                            id: id
                        },
                        success: function(response) {
                            if (response == "success") {
                                $('#row-' + id).remove();
                            } else {
                                alert('Error deleting record.');
                            }
                        }
                    });
                }
            });

            // Edit form submit handler
            $('#editForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: 'edit.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response == "success") {
                            var id = $('#editId').val();
                            var name = $('#editName').val();
                            var detail = $('#editDetail').val();
                            var row = $('#row-' + id);
                            row.find('.name').text(name);
                            row.find('.detail').text(detail);
                            $('#editModal').modal('hide');
                        } else {
                            alert('Error updating record.');
                        }
                    }
                });
            });

            // Add form submit handler
            $('#addForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: 'add.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.id) {
                            var newRow = "<tr id='row-" + response.id + "'>";
                            newRow += "<th scope='row'>" + response.id + "</th>";
                            newRow += "<td class='name'>" + response.name + "</td>";
                            newRow += "<td class='detail'>" + response.detail + "</td>";
                            newRow += "<td>";
                            newRow += "<button class='btn btn-primary btn-sm edit' data-id='" + response.id + "'>Edit</button> ";
                            newRow += "<button class='btn btn-danger btn-sm delete' data-id='" + response.id + "'>Delete</button>";
                            newRow += "</td>";
                            newRow += "</tr>";
                            $('#todolist').append(newRow);
                            $('#addModal').modal('hide');
                            $('#addName').val('');
                            $('#addDetail').val('');
                        } else {
                            alert('Error adding record.');
                        }
                    },
                    dataType: "json"
                });
            });
        });
    </script>
</body>

</html>