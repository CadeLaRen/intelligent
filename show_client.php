<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();
include 'templates/header.php';
include("config/config.php");
$connection = mysqli_connect($host,$db_user,$db_password);
mysqli_select_db($connection,$db_name) or die(mysqli_error($connection));
if(!$connection->set_charset("utf8"))
	printf("Error loading character set utf8: %s\n", mysqli_error($connection));

if (mysqli_connect_errno())
	echo "Failed to connect to MySQL: " . mysqli_connect_error();


if(!isset($_SESSION['username']))
{
	header("Location: login.php");
	$_SESSION['failure_message'] = 'باید وارد شوید!';
	die();
}

if(isset($_SESSION['failure_message']) and $_SESSION['failure_message'] != "")
{
	?>
	<div class="alert alert-error message">
		<?php echo $_SESSION['failure_message']; ?>
	</div>
	<?
	$_SESSION['failure_message'] = "";
  //header("Location: inde.php");
  //die();
}

if (isset($_SESSSION['success_message']) and $_SESSION['success_message'] != "")
{
	?>
	<div class="alert alert-success message">
		<?php echo $_SESSION['success_message']; ?>
	</div>
	<?
	$_SESSION['success_message'] = "";
}

$user_name = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = '$user_name'";
$result = mysqli_query($connection,$sql) or die(mysqli_error($connection));
$user = mysqli_fetch_assoc($result);

if(!isset($_GET['client_username']))
{
	$_SESSION['failure_message'] = 'کاربر یافت نشد.';
	header("Location: adviser.php");
	die();
}

$client_username = $_GET['client_username'];
$code = $user['code'];
$sql = "SELECT * FROM users where username = '$client_username' and adviser_code = '$code'";
$result = mysqli_query($connection,$sql) or die(mysqli_error($connection));
$client = mysqli_fetch_assoc($result);
if(count($client) == 0)
{
	$_SESSION['failure_message'] = 'کاربر یافت نشد.';
	header("Location: adviser.php");
	die();
}

?>

<br><br><br>
<div class="profile_info">
	<div class="row">
		<div class="span1">
		</div>
		<div class="span4">
			<ul class="thumbnails">
				<li class="span3">
					<a href="#" class="thumbnail">
						<img src="img/avatar.png" alt="">
					</a>
					<p class="username">
						<? echo $user['username']; ?>
					</p>
					<ul class="non_list profile_info_detail">
						<li class="items">	
							<i class="fa fa-question-circle"></i>
							<? echo $user['name']; ?>
							<? echo $user['family_name']; ?>
						</li>
						<li class="items">
							<i class="fa fa-envelope"></i>
							<? echo $user['email']; ?>
						</li>
						<li class="items">
							<i class="fa fa-phone-square"></i>
							<? echo $user['phone_number']; ?>
						</li>
						<li class="items">
							<i class="fa fa-barcode"></i>
							<? echo $user['code']; ?>
						</li>
					</ul>
				</li>
			</ul>
		</div>		
	</div>
</div>
<?
include 'templates/footer.php';
?>