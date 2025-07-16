<?php require_once('includes/init.php'); ?>

<?php
$errors = array();
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if (isset($_POST['submit'])):

  // Validasi
  if (!$username) {
    $errors[] = 'Username tidak boleh kosong';
  }
  if (!$password) {
    $errors[] = 'Password tidak boleh kosong';
  }

  if (empty($errors)):
    $query = mysqli_query($koneksi, "SELECT * FROM admin WHERE username = '$username'");
    $cek = mysqli_num_rows($query);
    $data = mysqli_fetch_array($query);

    if ($cek > 0) {
      $hashed_password = sha1($password);
      if ($data['password'] === $hashed_password) {
        $_SESSION["user_id"] = $data["id_admin"];
        $_SESSION["username"] = $data["username"];
        $_SESSION["role"] = $data["role"];
        redirect_to("dashboard.php");
      } else {
        $errors[] = 'Username atau password salah!';
      }
    } else {
      $errors[] = 'Username atau password salah!';
    }

  endif;

endif;

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Login - SPK Beasiswa (Metode MABAC)</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      height: 100vh;
      margin: 0;
      background: url('assets/img/Screenshot\ 2025-07-12\ 064442.png') no-repeat center center fixed;
      background-size: cover;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-card {
      width: 100%;
      max-width: 400px;
      background-color: rgba(255, 255, 255, 0.95);
      border-radius: 16px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
      padding: 2rem;
    }

    .login-card h4 {
      font-weight: bold;
      color: #333;
    }

    .title-sub {
      font-size: 0.9rem;
      color: #555;
      margin-top: -10px;
      margin-bottom: 20px;
    }
  </style>
</head>

<body>
  <div class="login-card">
    <h4 class="text-center">Sistem Pendukung Keputusan</h4>
    <p class="title-sub text-center">Penentuan Penerima Beasiswa - Metode MABAC</p>

    <?php if (!empty($errors)): ?>
      <?php foreach ($errors as $error): ?>
        <div class="alert alert-danger text-center"><?php echo $error; ?></div>
      <?php endforeach; ?>
    <?php endif; ?>

    <form class="user" action="login.php" method="post">
      <div class="form-group">
        <input required autocomplete="off" type="text" value="<?php echo htmlentities($username); ?>" class="form-control form-control-user" id="exampleInputUser" placeholder="Username" name="username" />
      </div>
      <br>
      <div class="form-group">
        <input required autocomplete="off" type="password" class="form-control form-control-user" id="exampleInputPassword" name="password" placeholder="Password" />
      </div>
      <br>
      <div class="form-group text-start d-flex justify-content-end">
        <button name="submit" type="submit" class="btn btn-primary btn-user"> Masuk</button> &nbsp;
        <button name="reset" type="reset" class="btn btn-danger btn-user"> Batal</button>
      </div>

  </div>
  </form>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>