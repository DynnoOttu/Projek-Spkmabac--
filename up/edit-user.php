<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$errors = array();
$sukses = false;

require 'vendor/autoload.php';
 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\Exception;

$ada_error = false;
$result = '';

$id_user = (isset($_GET['id'])) ? trim($_GET['id']) : '';

if(isset($_POST['submit'])):
	$username = $_POST['username'];
	$password = $_POST['password'];
	$password2 = $_POST['password2'];
	$nama = $_POST['nama'];
	$email = $_POST['email'];
	$role = $_POST['role'];
	
	if(!$username) {
		$errors[] = 'Username tidak boleh kosong';
	}
	if(!$nama) {
		$errors[] = 'Nama tidak boleh kosong';
	}		
	
	if(!$email) {
		$errors[] = 'Email tidak boleh kosong';
	}
	
	if(!$role) {
		$errors[] = 'Role tidak boleh kosong';
	}
	
	if(!$id_user) {
		$errors[] = 'Id User salah';
	}
	
	if($password && ($password != $password2)) {
		$errors[] = 'Password harus sama keduanya';
	}
	
	
	if(empty($errors)):
		$update = mysqli_query($koneksi,"UPDATE user SET username = '$username', nama = '$nama', email = '$email', role = '$role' WHERE id_user = '$id_user'");
		
		if($password) {
			$pass = sha1($password);
			$update = mysqli_query($koneksi,"UPDATE user SET username = '$username', nama = '$nama',  password = '$pass', email = '$email', role = '$role' WHERE id_user = '$id_user'");
		}		
		if (($update)){
			$email_pengirim = 'mariamugirato@gmail.com';// email pengirim
			$nama_pengirim = 'Nursila Mugirato';// nama pengirim
			$email_penerima = $_POST['email'];// email penerima
			$subject = 'Registrasi Pengguna Baru';
			$pesan = 'Data Berhasil Diubah, username anda: '.$username.' dengan password: '.$password.'';
		
			$mail = new PHPMailer;
			$mail->isSMTP();
		
			$mail->Host = 'smtp.gmail.com';
			$mail->Username = $email_pengirim;
			$mail->Password = 'vyjwwhpgqqswctxi'; 
			$mail->Port = 465;
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = 'ssl';
			$mail->SMTPDebug = 2;
		
			$mail->setFrom($email_pengirim, $nama_pengirim);
			$mail->addAddress($email_penerima);
			$mail->isHTML(true);
			$mail->Subject = $subject;
			$mail->Body = $pesan;
		
			$send = $mail->send();
		
			if($send){
				echo "<h1>Email Berhasil Dikirim</h1><br/><a href='list-user.php'></a>";
			}else{
				echo "<h1>Email Gagal Dikirim</h1><br/><a href='list-user.php'></a>";
			}
			echo "<script>alert('Data berhasil diubah, email berhasil dikirim')</script>";
			echo "<script type ='text/javascript'>document.location = 'list-user.php?page=data_user';</script>";
		
		}
	
	endif;

endif;
?>

<?php
$page = "User";
require_once('template/header.php');
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-users-cog"></i> Data User</h1>

	<a href="list-user.php" class="btn btn-secondary btn-icon-split"><span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
		<span class="text">Kembali</span>
	</a>
</div>

<?php if(!empty($errors)): ?>
	<div class="alert alert-info">
			<?php foreach($errors as $error): ?>
				<?php echo $error; ?>
			<?php endforeach; ?>
	</div>
<?php endif; ?>

<form action="edit-user.php?id=<?php echo $id_user; ?>" method="post">
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-info"><i class="fas fa-fw fa-edit"></i> Edit Data User</h6>
		</div>
		<?php
		if(!$id_user) {
		?>
		<div class="card-body">
			<div class="alert alert-danger">Data tidak ada</div>
		</div>
		<?php
		}else{
		$data = mysqli_query($koneksi,"SELECT * FROM user WHERE id_user='$id_user'");
		$cek = mysqli_num_rows($data);
		if($cek <= 0) {
		?>
		<div class="card-body">
			<div class="alert alert-danger">Data tidak ada</div>
		</div>
		<?php
		}else{
			while($d = mysqli_fetch_array($data)){
		?>
		<div class="card-body">
			<div class="row text-dark">
				<div class="form-group col-md-6">
					<label class="font-weight-bold">Username</label>
					<input autocomplete="off" type="text" name="username" required value="<?php echo $d['username']; ?>" class="form-control"/>
				</div>
				
				<div class="form-group col-md-6">
					<label class="font-weight-bold">Password</sub></label>
					<input autocomplete="off" type="password" name="password" class="form-control"/>
				</div>
				
				<div class="form-group col-md-6">
					<label class="font-weight-bold">Ulangi Password</label>
					<input autocomplete="off" type="password" name="password2" class="form-control"/>
				</div>
				
				<div class="form-group col-md-6">
					<label class="font-weight-bold">Nama</label>
					<input autocomplete="off" type="text" name="nama" required value="<?php echo $d['nama']; ?>" class="form-control"/>
				</div>
				
				<div class="form-group col-md-6">
					<label class="font-weight-bold">E-Mail</label>
					<input autocomplete="off" type="email" name="email" required value="<?php echo $d['email']; ?>" class="form-control"/>
				</div>
				
				<div class="form-group col-md-6">
					<label class="font-weight-bold">Level</label>
					<select name="role" required class="form-control">
						<option value="">--Pilih--</option>
						<option value="1" <?php if($d['role']==1) {echo "selected";} ?>>Administrator</option>
						<option value="2" <?php if($d['role']==2) {echo "selected";} ?>>User 1</option>
						<option value="3" <?php if($d['role']==3) {echo "selected";} ?>>User 2</option>
					</select>
				</div>
			</div>
		</div>
		<div class="card-footer text-right">
            <button name="submit" value="submit" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Ubah</button>
            <button type="reset" class="btn btn-info"><i class="fa fa-sync-alt"></i> Batal</button>
        </div>
	</div>
	<?php
		}
		}
		}
	?>
</form>

<?php
require_once('template/footer.php');
?>