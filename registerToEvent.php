<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<?php require 'classes/db1.php';
$id = $_GET['id'];

?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>cems</title>
    <title></title>
    <?php require 'utils/styles.php'; ?><!--css links. file found in utils folder-->

</head>

<body>
    <?php require 'utils/header.php'; ?><!--header content. file found in utils folder-->

    <div class="content"><!--body content holder-->
        <div class="container">
            <div class="col-md-6 col-md-offset-3">
                <form action="registerToEvent.php" class="form-group" method="POST">


                    <div class="form-group">
                        <label for="usn"> Student USN: </label>
                        <input type="text" id="usn" name="usn" class="form-control">
                        <input type="hidden" name="id" value="<?php echo ($_GET['id']); ?>">
                    </div>
                    <button type="submit" name="submit" class="btn btn-default">Register</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>

<?php

if (isset($_POST["submit"])) {
    $usn = $_POST["usn"];
    $id = $_POST["id"];
    if (!empty($usn) && !empty($id)) {  // Use AND instead of OR to ensure both fields are filled

        include 'classes/db1.php';
        
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT rid FROM registered WHERE usn = ? AND event_id = ?");
        $stmt->bind_param("ss", $usn, $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            $stmt->close();

            $insert_stmt = $conn->prepare("INSERT INTO registered (usn, event_id) VALUES (?, ?)");
            $insert_stmt->bind_param("ss", $usn, $id);

            if ($insert_stmt->execute()) {
                echo "<script>
                        alert('Registered Successfully!');
                        window.location.href='usn.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Registration failed. Please try again.');
                        window.location.href='usn.php';
                      </script>";
            }
            $insert_stmt->close();
        } else {
            echo "<script>
                    alert('Already registered with this USN for the event.');
                    window.location.href='usn.php';
                  </script>";
        }
        
        $stmt->close();
        $conn->close();
    } else {
        echo "<script>
                alert('Please fill in both USN and Event ID.');
                window.location.href='usn.php';
              </script>";
    }
}



?>