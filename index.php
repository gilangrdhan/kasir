<?php
session_start();
session_regenerate_id();
require_once "config/koneksi.php";

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $selectLogin = mysqli_query($koneksi, "SELECT * FROM user WHERE email = '$email'");
    if (mysqli_num_rows($selectLogin) > 0) {
        $row = mysqli_fetch_assoc($selectLogin);

        if ($row['email'] == $email && $row['password'] == $pass) {
            $_SESSION['EMAILNYABRO'] = $row['email'];
            $_SESSION['NAMALENGKAPNYA'] = $row['nama_lengkap'];
            header("location: kasir.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.min.css">
    <title>Cashier</title>
</head>

<body>
    <div class="container justify-content-center">
        <div class="row">
            <div class="col-4"></div>
            <div class="col-4">
                <div class="card" style="margin-top: 200px;">
                    <div class=" card-body">
                        <div class="card-title text-center">
                            <h2 class="fw-bold text-primary" style="letter-spacing: -2px;">
                                <img src="./twitter.png" alt=""
                                    style="width: 35px; margin-right: 10px; margin-bottom: 10px">Lawson
                            </h2>
                            <p>Silahkan masuk dengan akun anda</p>
                        </div>
                        <form action="" method="post">
                            <div class="form-group mb-3">
                                <label for="" class="form-label">
                                    Email
                                </label>
                                <input type="email" class="form-control" name="email" placeholder="Masukan email anda"
                                    required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="" class="form-label">
                                    Password
                                </label>
                                <input type="password" class="form-control" name="password"
                                    placeholder="Masukkan Password" required>
                            </div>
                            <div class="form-goup mb-3">
                                <div class="d-grid">
                                    <button type="submit" name="login" class="btn btn-primary">Login</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <footer class="text-center border-top border-dark border-2 fixed-bottom p-3 bg-white">
            Copyright &copy; 2024 Lawson - Jakarta Pusat.
        </footer>
    </div>


    <link rel="stylesheet" href="bootstrap/dist/js/bootstrap.bundle.min.js">

</body>


</html>