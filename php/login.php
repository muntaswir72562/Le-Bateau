<?php
include('connection.php');
session_start();


if (isset($_session['fName'])) {
    header('location:dashboard.php');
} else {

    $email = $_POST['email'];
    $pass = $_POST['password'];

    $sql = "select * from registration where email = '$email' and password = '$pass'";//check for password match in the database
    $rs = mysqli_query($conn, $sql);


    if (mysqli_num_rows($rs) < 1) {
        header("location:../login-form.php?status=invalid");
    } else {
        while ($row = $rs->fetch_assoc()) {
            $_SESSION['registrationId'] = $row['registrationId']; 
            $_SESSION['fName'] = $row['firstName'];
            $_SESSION['lName'] = $row['lastName'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['city'] = $row['city']; 
            $_SESSION['country'] = $row['country']; 
        }
        if ($_SESSION['role'] == 'student') {
            $registrationId = $_SESSION['registrationId']; 
            $sql = "select studentId from student WHERE registrationId = '$registrationId'; "; //retrieving studentid for enrollement
            $result = mysqli_query($conn, $sql); 
            $studentId = mysqli_fetch_all($result, MYSQLI_ASSOC); 

            $_SESSION['studentId'] = $studentId[0]['studentId']; 
            header('location:dashboard.php');
        } else {
            header('location:../admin/admin.php');
        }
    }
}

mysqli_close($conn); 