<?php
    include ("../config/db_carngren.php");
    
    $fullName = $email = "";
    $fullNameError = $emailError = $passwordError = $password2Error = "";
    $registrationSuccess = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['submit'])) {
            
            $fullName = trim($_POST['fullName']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $password2 = $_POST['password2'];

            $number = "/[0-9]/";

            if (ctype_space($fullName) || empty($fullName)) {
                $fullNameError = "First Name cannot be blank!";
            } else {
                if (!preg_match("/^([A-Z]){1}([a-z]){1,}$/", $fullName)) {
                    $fullNameError = "First character must be uppercase followed by lowercase letters.";
                }
                if (preg_match($number, $fullName)) {
                    $fullNameError = "First name cannot contain numbers.";
                }
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailError = "Invalid email format!";
            }

            if ($password2 !== $password) {
                $password2Error = "Confirm password does not match!";
            }

            if (empty($fullNameError) && empty($emailError) && empty($passwordError) && empty($password2Error)) {
                // Hash password before storing
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Ensure addAccount function exists in your db config file
                if (function_exists('addAccount')) {
                    addAccount($fullName, $email, $hashedPassword);
                }

                $registrationSuccess = true;
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arngren | Registration</title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/logo.PNG">
    <link rel="stylesheet" href="../../assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="../../assets/images/logo.PNG" alt="logo">
        </div>
        <div class="logotext">
            <h1>ARNGREN</h1>
        </div>
        <div class="logintext"> 
            <h1>Registration</h1>
        </div>
    </div>

    <div class="context">
        <div class="contextimg">
            <figure>
                <img src="../../assets/images/logo.PNG" alt="arngrenlogo">
                <figcaption><h3>ARNGREN</h3>Appliances and Gadgets Online Shopping Platform</figcaption>
            </figure>
        </div>

        <!-- Registration Form Container -->
        <div class="container" id="container" style="<?php echo $registrationSuccess ? 'display: none;' : 'display: block;'; ?>">
            <div class="formheader">
                <h3>Create Account</h3>
            </div>
            <form class="form" id="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" novalidate>
                
                <div class="innerform">
                    <input type="text" placeholder="Full Name" id="fullName" name="fullName" value="<?php echo htmlspecialchars($fullName); ?>" class="<?php echo !empty($fullNameError) ? 'error-input' : ''; ?>" required>
                    <div class="errorblock">
                        <small class="error"><?php echo $fullNameError; ?></small>
                    </div>
                </div>      

                <div class="innerform">
                    <input type="email" placeholder="Email Address" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="<?php echo !empty($emailError) ? 'error-input' : ''; ?>" required>
                    <div class="errorblock">
                        <small class="error"><?php echo $emailError; ?></small>
                    </div>
                </div>

                <div class="innerform">
                    <input type="password" placeholder="Password" id="password" name="password" class="<?php echo !empty($passwordError) ? 'error-input' : ''; ?>" required>
                    <div class="errorblock">
                        <small class="error"><?php echo $passwordError; ?></small>
                    </div>
                </div>

                <div class="innerform">
                    <input type="password" placeholder="Confirm Password" id="password2" name="password2" class="<?php echo !empty($password2Error) ? 'error-input' : ''; ?>" required>
                    <div class="errorblock">
                        <small class="error" id="matchError"><?php echo $password2Error; ?></small>
                    </div>
                </div>

                <div class="footerform">
                    <input type="submit" name="submit" value="Sign Up" class="submit">
                    <input type="reset" value="Clear Form" class="reset" onclick="resetErrors()">
                </div>
            </form>
            
            <div class="formfooter">
                <p>Already have an account? <a href="../auth/loginUser.php">Log In</a></p>
            </div>
        </div>

        <!-- Success View State Card -->
        <div class="after" id="after" style="<?php echo $registrationSuccess ? 'display: block;' : 'display: none;'; ?>">
            <div class="afterimg">
                <i class="fa fa-check-circle" style="font-size: 50px; color: #28a745; margin-bottom: 10px;"></i>
            </div>
            <div class="aftertext">Congratulations, your account has been successfully created!</div>
            <div class="afterinput">
                <input type="button" value="Log In Now" onclick="location.href='../auth/loginUser.php';">
                <input type="button" value="Back to Homepage" onclick="location.href='../auth/index.php';">
            </div>
        </div>
    </div>

    <div class="footer">
        <br>
        <hr>
        <p>&copy; 2026 ARNGREN. ALL RIGHTS RESERVED</p>
    </div>

    <!-- Interactive Client-side validation enhancement script -->
    <script>
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password2');
        const matchError = document.getElementById('matchError');

        function validatePasswordMatch() {
            if (confirmPassword.value && password.value !== confirmPassword.value) {
                matchError.textContent = "* Passwords do not match";
                confirmPassword.classList.add('error-input');
            } else {
                matchError.textContent = "";
                confirmPassword.classList.remove('error-input');
            }
        }

        confirmPassword.addEventListener('input', validatePasswordMatch);
        password.addEventListener('input', validatePasswordMatch);

        function resetErrors() {
            document.querySelectorAll('.error-input').forEach(el => el.classList.remove('error-input'));
            document.querySelectorAll('.error').forEach(el => el.textContent = '');
        }
    </script>
</body>
</html>