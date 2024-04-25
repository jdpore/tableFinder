<?php
include 'search.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Finder</title>
    <link rel="stylesheet" href="bootstrap5/css/bootstrap.css">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <style>
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }

        .custom-button {
            width: 40px;
            /* Set your desired width */
            height: 30px;
            /* Set your desired height */
        }
    </style>
</head>

<body class="bg-light row justify-content-center d-flex align-items-center justify-content-center"
    style="height: 100vh; width: 100vw">
    <div class="row h-75 w-75 p-0 m-0 border rounded-3 overflow-hidden shadow-lg">
        <div class="col-3 h-100 bg-secondary d-flex align-items-center justify-content-center">
            <form class="col-10" action="" onsubmit="return isvalid()" method="POST">
                <div class="mb-4">
                    <label for="name" class="form-label text-white">Name</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter name..." />
                </div>
                <div class="mb-4">
                    <label for="tableNumber" class="form-label text-white">Table Number</label>
                    <input type="number" id="tableNumber" name="tableNumber" class="form-control"
                        placeholder="Enter Table Number..." />
                </div>
                <div class="row">
                    <div class="col-4 pe-1">
                        <a href="index.php" class="btn btn-success btn-block w-100">
                            <i class="fa-solid fa-arrows-rotate"></i>
                        </a>
                    </div>

                    <div class="col-8 ps-1">
                        <button id="search" name="search" type="submit"
                            class="btn btn-primary btn-block w-100">Search</button>
                    </div>
                </div>
                <button onclick="exportTableToExcel('attended', 'table')"
                    class="btn btn-info btn-block w-100 mt-4">Export
                    to Excel</button>
            </form>
        </div>
        <div class="col-9 h-100 bg-light p-5">
            <div class="container h-100 overflow-auto">
                <table class="table table-striped">
                    <thead class="bg-light sticky-top sticky">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Table Number</th>
                            <th scope="col">Status</th>
                            <th scope="col">Button</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $row) {
                            ?>
                            <tr>
                                <th>
                                    <?php
                                    $id = $row['id'];
                                    $name = $row['name'];
                                    echo $row['name']; ?>
                                </th>
                                <td>
                                    <?php echo $row['tableNumber']; ?>
                                </td>
                                <td>
                                    <?php
                                    $status = $row['status'];
                                    echo $status;
                                    ?>
                                </td>
                                <td>
                                    <div class="button-container" style="display: flex; gap: 5px;">
                                        <button
                                            onclick="updateStatus('<?php echo $row['name']; ?>', '<?php echo $status; ?>')"
                                            class="btn <?php echo $status == 'Attended' ? 'btn-danger' : 'btn-success'; ?> btn-lg custom-button"
                                            type="button"
                                            style="display: flex; align-items: center; justify-content: center;">
                                            <?php echo $status == 'Attended' ? '<i class="fa-solid fa-x"></i>' : '<i class="fa-solid fa-check"></i>'; ?>
                                        </button>
                                        <button
                                            onclick="openEditModal('<?php echo $row['id']; ?>', '<?php echo $row['name']; ?>')"
                                            class="btn btn-primary btn-lg custom-button" type="button"
                                            style="display: flex; align-items: center; justify-content: center;">
                                            <i class="fa-solid fa-pencil"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <table id="attended" class="table table-striped d-none">
                    <thead class="bg-light sticky-top sticky">
                        <tr>
                            <th scope="col">Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result1 as $row1) {
                            ?>
                            <tr>
                                <th>
                                    <?php
                                    echo $row1['name']; ?>
                                </th>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal fade" id="editNameModal" tabindex="-1" aria-labelledby="editNameModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editNameModalLabel">Edit Name</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editNameForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="editedName" class="form-label">New Name:</label>
                                <input type="text" class="form-control" id="editedName" name="editedName"
                                    placeholder="Enter new name">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="bootstrap5/js/bootstrap.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        function updateStatus(name, status) {
            var newStatus = status === 'Attended' ? 'Absent' : 'Attended'; // Toggle status
            $.ajax({
                url: "update.php",
                type: "POST",
                data: { name: name, status: newStatus }, // Send both name and status
                success: function (response) {
                    location.reload();
                },
                error: function (xhr, name, error) {
                }
            });
        }
    </script>
    <script>
        function exportTableToExcel(tableID, filename = '') {
            var downloadLink;
            var dataType = 'application/vnd.ms-excel';
            var tableSelect = document.getElementById(tableID);
            var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

            // Specify file name
            filename = filename ? filename + '.xls' : 'excel_data.xls';

            // Create download link element
            downloadLink = document.createElement("a");

            document.body.appendChild(downloadLink);

            if (navigator.msSaveOrOpenBlob) {
                var blob = new Blob(['\ufeff', tableHTML], {
                    type: dataType
                });
                navigator.msSaveOrOpenBlob(blob, filename);
            } else {
                // Create a link to the file
                downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

                // Setting the file name
                downloadLink.download = filename;

                //triggering the function
                downloadLink.click();
            }
        }
    </script>
    <script>
        function openEditModal(id, currentName) {
            $('#editedName').val(currentName); // Set current name in the modal input field
            $('#editNameForm').data('id', id); // Store the id in the form data
            $('#editNameModal').modal('show'); // Show the modal
        }

        $('#editNameForm').submit(function (event) {
            event.preventDefault(); // Prevent default form submission
            var newName = $('#editedName').val();
            var id = $(this).data('id'); // Get the ID from form data

            // Send AJAX request to edit_name.php
            $.ajax({
                url: "update.php",
                type: "POST",
                data: { id: id, editedName: newName },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        // Name updated successfully, you may want to update the name in the table dynamically
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        // Error occurred while updating name
                        alert('Error: ' + response.message);
                    }
                },
                error: function (xhr, status, error) {
                    // Handle AJAX errors
                    alert('AJAX Error: ' + error);
                }
            });

            $('#editNameModal').modal('hide'); // Hide the modal after submission
        });

    </script>
</body>

</html>