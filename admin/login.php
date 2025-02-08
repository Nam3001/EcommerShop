<?php
include '../configs/config.php';
include '../configs/database.php';
include '../databases/DBHelper.php';
include '../databases/UserDAO.php';

session_start();

$userInfo = null;
$errorMessage = "";
if(isset($_POST['submit'])) {
    $username = isset($_POST['username']) ? $_POST['username'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    if($username && $password) {
        $password = md5($password);
        $userDao = new UserDAO();
        $userInfo = $userDao->loginAdmin($username, $password);
        if(!$userInfo) {
            $errorMessage = "Tên đăng nhập hoặc mật khẩu không chính xác!";
        } else {
            $_SESSION['user'] = $userInfo;
        }

    }
}


if($userInfo) {
    header("Location: " . BASE_URL . '/admin');
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Đăng nhập</title>
        <link href="assets/css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Đăng nhập Admin</h3></div>
                                    <?php
                                    if(strlen($errorMessage) > 0) {
                                        $element = '<div class="d-flex justify-content-center mt-2">
                                        <p class="text-danger m-0">' . $errorMessage . '</p>
                                    </div>';
                                        echo $element;
                                    }
                                    ?>
                                    <div class="card-body">
                                        <form method="POST">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" name="username" id="username" type="text" placeholder="Tên đăng nhập" required />
                                                <label for="username">Tên đăng nhập</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Mật khẩu" required />
                                                <label for="inputPassword">Mật khẩu</label>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
<!--                                                <a class="small" href="password.php">Forgot Password?</a>-->
                                                <input type="submit" name="submit" class="w-100 btn btn-primary" value="Đăng nhập">
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center py-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="assets/js/scripts.js"></script>
    </body>
</html>
