<?php 
include '../Model/db.php'; 

if(isset($_POST['register'])) {
    $u = mysqli_real_escape_string($conn, $_POST['username']);
    $e = mysqli_real_escape_string($conn, $_POST['email']);
    $ph = mysqli_real_escape_string($conn, $_POST['phone']); 
    $p = mysqli_real_escape_string($conn, $_POST['password']);
    $cp = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    
    if($p !== $cp) {
        $error = "Passwords do not match!";
    } else {
        $image_name = $_FILES['profile_pic']['name'];
        $image_tmp = $_FILES['profile_pic']['tmp_name'];
        $final_image = "default.png"; 

        if(!empty($image_name)) {
            $ext = pathinfo($image_name, PATHINFO_EXTENSION);
            $final_image = time() . "_" . str_replace(' ', '_', $u) . "." . $ext;
            move_uploaded_file($image_tmp, "../uploads/" . $final_image);
        }
        
        $checkEmail = mysqli_query($conn, "SELECT email FROM users WHERE email = '$e'");
        if(mysqli_num_rows($checkEmail) > 0) {
            $error = "This email is already registered!";
        } else {
            $sql = "INSERT INTO users (username, email, phone, password, image, role) VALUES ('$u', '$e', '$ph', '$p', '$final_image', 'Customer')";
            if(mysqli_query($conn, $sql)) {
                echo "<script>alert('Registration Successful!'); window.location.href='login.php';</script>";
                exit();
            } else {
                $error = "Registration failed. Please try again."; 
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baking Valley - Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #fdf2f4;
        }

        .container {
            width: 900px;
            height: 650px;
            background: white;
            border-radius: 20px;
            display: flex;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        }

        .left {
            width: 45%;
            background: linear-gradient(135deg, #ff9eb5, #ff6b95);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            text-align: center;
            position: relative;
        }

        .brand-box {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            z-index: 1;
        }

        .brand-logo {
            width: 50px;
            height: 50px;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }

        .left h1 {
            font-size: 35px;
            font-weight: 700;
        }

        .left p {
            opacity: .9;
            margin-bottom: 30px;
            z-index: 1;
            font-size: 14px;
        }

        .login-switch-btn {
            padding: 12px 45px;
            background: white;
            color: #ff6b95;
            font-weight: 600;
            border-radius: 30px;
            text-decoration: none;
            transition: .3s;
            z-index: 1;
        }

        .right {
            width: 55%;
            padding: 30px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .right h2 {
            font-size: 26px;
            margin-bottom: 20px;
            color: #ff4d79;
            text-align: center;
        }

        .input-box {
            margin-bottom: 12px;
            position: relative;
        }

        .input-box i.main-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #b0b0b0;
        }

        .input-box input {
            width: 100%;
            padding: 12px 45px 12px 50px;
            border-radius: 30px;
            border: 1px solid #eee;
            background: #fdfdfd;
            outline: none;
            font-size: 14px;
            transition: .3s;
        }

        .input-box input:focus {
            border-color: #ffb6c1;
        }

        .toggle-password {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #b0b0b0;
        }

        .register-btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 30px;
            background: linear-gradient(135deg, #ff758c, #ff7eb3);
            color: white;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            box-shadow: 0 5px 15px rgba(255, 117, 140, 0.3);
        }

        .error-msg {
            background: #fff5f5;
            color: #e53e3e;
            padding: 8px;
            border-radius: 10px;
            font-size: 12px;
            margin-bottom: 10px;
            text-align: center;
            border: 1px solid #feb2b2;
        }

        .extra {
            margin-top: 15px;
            text-align: center;
            font-size: 13px;
            color: #777;
        }

        .extra a {
            color: #ff4d79;
            font-weight: 600;
            text-decoration: none;
        }

        @media(max-width: 768px) {
            .container {
                width: 95%;
                flex-direction: column;
                height: auto;
            }
            .left, .right {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="left">
        <div class="brand-box">
          
            <h1>Baking Valley</h1>
        </div>
        <p>Fresh cakes & sweet moments everyday 🍰</p>
        <a href="login.php" class="login-switch-btn">Login</a>
    </div>

    <div class="right">
        <h2>Create your account</h2>

        <?php if (isset($error)): ?>
            <div class="error-msg"><i class="fas fa-circle-exclamation"></i> <?php echo $error; ?></div>
        <?php endif; ?>
        
        <div id="js-error" class="error-msg" style="display:none;"></div>

        <form method="POST" enctype="multipart/form-data" onsubmit="return validateRegister()">
            <div class="input-box">
                <i class="far fa-user main-icon"></i>
                <input type="text" name="username" id="username" placeholder="Full Name">
            </div>

            <div class="input-box">
                <i class="far fa-envelope main-icon"></i>
                <input type="email" name="email" id="email" placeholder="Email Address">
            </div>

            <div class="input-box">
                <i class="fas fa-phone main-icon"></i>
                <input type="text" name="phone" id="phone" placeholder="Phone Number">
            </div>

            <div class="input-box">
                <i class="fas fa-image main-icon"></i>
                <input type="file" name="profile_pic" id="profile_pic" accept="image/*">
            </div>

            <div class="input-box">
                <i class="fas fa-lock main-icon"></i>
                <input type="password" name="password" id="password" placeholder="Password">
                <i class="far fa-eye toggle-password" onclick="togglePass('password', this)"></i>
            </div>

            <div class="input-box">
                <i class="fas fa-shield-alt main-icon"></i>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password">
                <i class="far fa-eye toggle-password" onclick="togglePass('confirm_password', this)"></i>
            </div>

            <button type="submit" name="register" class="register-btn">Register Now</button>
        </form>

        <div class="extra">
            Already have account? <a href="login.php">Login</a>
        </div>
    </div>
</div>

<script>
    function togglePass(inputId, icon) {
        const passInput = document.getElementById(inputId);
        if (passInput.type === "password") {
            passInput.type = "text";
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passInput.type = "password";
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    function validateRegister() {
        const u = document.getElementById('username').value.trim();
        const e = document.getElementById('email').value.trim();
        const ph = document.getElementById('phone').value.trim();
        const p = document.getElementById('password').value;
        const cp = document.getElementById('confirm_password').value;
        const err = document.getElementById('js-error');

        err.style.display = 'none';

        if (u === "" || e === "" || ph === "" || p === "" || cp === "") {
            err.style.display = 'block';
            err.innerHTML = "All fields are required!";
            return false;
        }
        if (p !== cp) {
            err.style.display = 'block';
            err.innerHTML = "Passwords do not match!";
            return false;
        }
        if (p.length < 6) {
            err.style.display = 'block';
            err.innerHTML = "Password must be at least 6 characters!";
            return false;
        }
        return true;
    }
</script>

</body>
</html>