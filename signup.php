<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Signup</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-7">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Create Account</h3>
                                </div>
                                <div class="card-body">
                                    <?php
                                    include 'connection.php';

                                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                        $name = $_POST['name'];
                                        $email = $_POST['email'];
                                        $password = $_POST['password'];
                                        $confirmPassword = $_POST['confirmPassword'];

                                        if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
                                            echo "<script>alert('All fields are required.');</script>";
                                        } elseif ($password !== $confirmPassword) {
                                            echo "<script>alert('Passwords do not match.');</script>";
                                        } else {
                                            $hash_pw = password_hash($password, PASSWORD_DEFAULT);

                                            $sql = "INSERT INTO users(user_name, email, password) VALUES ('$name', '$email', '$hash_pw')";

                                            if ($conn->query($sql) === TRUE) {
                                                echo "<script>alert('You are registered successfully!');</script>";
                                                echo "<script>window.location.href='index.php';</script>";
                                            } else {
                                                echo "Error: " . $sql . "<br>" . $conn->error;
                                            }
                                        }
                                    }
                                    ?>
                                    <form action="" method="post" onsubmit="return validateForm()">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputName" name="name" type="text" placeholder="Enter your first name" />
                                            <label for="inputName">First name</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputEmail" name="email" type="email" placeholder="name@example.com" />
                                            <label for="inputEmail">Email address</label>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Create a password" />
                                                    <label for="inputPassword">Password</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control" id="inputPasswordConfirm" name="confirmPassword" type="password" placeholder="Confirm password" />
                                                    <label for="inputPasswordConfirm">Confirm Password</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 mb-0">
                                            <div class="d-grid">
                                                <button class="btn btn-primary btn-block" type="submit">Create Account</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small"><a href="index.php">Have an account? Go to login</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Direct Task 2024</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script>
        function validateForm() {
            var name = document.getElementById("inputName").value;
            var email = document.getElementById("inputEmail").value;
            var password = document.getElementById("inputPassword").value;
            var confirmPassword = document.getElementById("inputPasswordConfirm").value;

            // Check if name contains at least one letter
            var namePattern = /[a-zA-Z]/;
            if (!namePattern.test(name)) {
                alert("Name must contain at least one letter.");
                return false;
            }

            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }

            if (password.length < 6) {
                alert("Password should be at least 6 characters long.");
                return false;
            }

            // Validate email format using regular expression
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }

            return true;
        }
    </script>
</body>

</html>
