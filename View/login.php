<?php 
include '../Model/db.php'; 
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: " . ($_SESSION['role'] == 'Admin' ? "admin.php" : "../index.php"));
    exit();
}

if (isset($_POST['login'])) {
    $login_input = mysqli_real_escape_string($conn, $_POST['login_input']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    if (filter_var($login_input, FILTER_VALIDATE_EMAIL)) {
        $query = "SELECT * FROM users WHERE email='$login_input' AND password='$password'";
    } else {
        $query = "SELECT * FROM users WHERE phone='$login_input' AND password='$password'";
    }
    
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id']; 
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email']; 
        $_SESSION['phone'] = $user['phone']; 
        $_SESSION['role'] = $user['role'];

        header("Location: " . ($user['role'] == 'Admin' ? "admin.php" : "../index.php"));
        exit();
    } else { 
        $error = "Invalid Login Details or Password!"; 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baking Valley - Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #fdf2f4;
        }

        .container {
            width: 900px;
            height: 520px;
            background: white;
            border-radius: 20px;
            display: flex;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        }

        /* Left Side */
        .left {
            width: 50%;
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

        /* Brand Styling with Logo */
        .brand-box {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
            z-index: 1;
        }

        .brand-logo {
            width: 60px; /* লোগোর সাইজ */
            height: 60px;
            object-fit: contain;
            filter: brightness(0) invert(1); /* লোগো সাদা করার জন্য */
        }

        .left h1 { font-size: 38px; font-weight: 700; }
        .left p { opacity: .9; margin-bottom: 35px; z-index: 1; }

        .reg-btn {
            padding: 12px 45px;
            background: white;
            color: #ff6b95;
            font-weight: 600;
            border-radius: 30px;
            text-decoration: none;
            transition: .3s;
            z-index: 1;
        }

        /* Right Side */
        .right {
            width: 50%;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .right h2 { font-size: 26px; margin-bottom: 30px; color: #ff4d79; text-align: center; }

        .input-box { margin-bottom: 20px; position: relative; }
        .input-box i.main-icon { position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: #b0b0b0; }

        .input-box input {
            width: 100%;
            padding: 14px 45px 14px 50px;
            border-radius: 30px;
            border: 1px solid #eee;
            background: #fdfdfd;
            outline: none;
            transition: .3s;
        }

        /* Password Toggle Icon */
        .toggle-password {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #b0b0b0;
            transition: .2s;
        }
        .toggle-password:hover { color: #ff6b95; }

        .login-btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 30px;
            background: linear-gradient(135deg, #ff758c, #ff7eb3);
            color: white;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(255, 117, 140, 0.3);
        }

        .error-msg {
            background: #fff5f5;
            color: #e53e3e;
            padding: 10px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 15px;
            text-align: center;
            border: 1px solid #feb2b2;
        }

        .extra { margin-top: 20px; text-align: center; font-size: 13px; color: #777; }
        .extra a { color: #ff4d79; font-weight: 600; text-decoration: none; }

        @media(max-width: 768px) {
            .container { width: 95%; flex-direction: column; height: auto; }
            .left, .right { width: 100%; }
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
        <a href="register.php" class="reg-btn">Register</a>
    </div>

    <div class="right">
        <h2>Login to your account</h2>

        <?php if (isset($error)): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-box">
                <i class="far fa-envelope main-icon"></i>
                <input type="text" name="login_input" placeholder="Email or Phone" required>
            </div>

            <div class="input-box">
                <i class="fas fa-lock main-icon"></i>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <i class="far fa-eye toggle-password" id="togglePassword"></i>
            </div>

            <button type="submit" name="login" class="login-btn">Login</button>
        </form>

        <div class="extra">
            Don't have account? <a href="register.php">Create now</a>
        </div>
    </div>
</div>

<script>
    // Password Show/Hide Script
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function (e) {
        // Toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        // Toggle the eye icon
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
</script>

</body>
</html>