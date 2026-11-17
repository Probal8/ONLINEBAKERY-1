<?php
include '../Model/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = "";


$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if (isset($_POST['update_profile'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

 
    $image_name = $user['image']; 
    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "../uploads/";
        $image_name = time() . "_" . basename($_FILES['profile_pic']['name']);
        $target_file = $target_dir . $image_name;
        
        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
            // Success
        }
    }

    $sql = "UPDATE users SET username='$username', phone='$phone', image='$image_name'";

  
    if (!empty($new_pass)) {
        if ($new_pass !== $confirm_pass) {
            $msg = "<div class='alert error'>Passwords do not match!</div>";
        } elseif (strlen($new_pass) < 6) {
            $msg = "<div class='alert error'>Password must be at least 6 characters!</div>";
        } else {
            $safe_pass = mysqli_real_escape_string($conn, $new_pass);
            $sql .= ", password='$safe_pass'";
        }
    }

    $sql .= " WHERE id='$user_id'";

    if (empty($msg)) {
        if (mysqli_query($conn, $sql)) {
            $_SESSION['username'] = $username;
            $msg = "<div class='alert success'><i class='fas fa-check-circle'></i> Profile updated successfully!</div>";
            $result = mysqli_query($conn, $query);
            $user = mysqli_fetch_assoc($result);
        } else {
            $msg = "<div class='alert error'>Update failed. Please try again.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings | Baking Valley</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { 
            --primary: #7c3aed; 
            --primary-light: #f5f3ff;
            --bg: #f8fafc; 
            --text-main: #1e293b; 
            --text-sub: #64748b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        
        body { 
            background: var(--bg); 
            display: flex; 
            justify-content: center; 
            align-items: center;
            min-height: 100vh;
            padding: 40px 20px; 
        }
        
        .card { 
            background: white; 
            width: 100%; 
            max-width: 500px; 
            padding: 40px; 
            border-radius: 30px; 
            box-shadow: 0 20px 50px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.8);
        }

        .header { text-align: center; margin-bottom: 30px; }
        .header h2 { color: var(--text-main); font-size: 26px; font-weight: 800; letter-spacing: -0.5px; }
        .header p { color: var(--text-sub); font-size: 14px; margin-top: 5px; }
        
        /* প্রোফাইল পিকচার (আরও বড় - 160px) */
        .profile-section {
            text-align: center;
            margin-bottom: 35px;
        }
        .profile-img-container {
            position: relative;
            display: inline-block;
        }
        .profile-preview {
            width: 160px; /* সাইজ বাড়ানো হয়েছে */
            height: 160px; /* সাইজ বাড়ানো হয়েছে */
            border-radius: 50%;
            object-fit: cover;
            border: 6px solid var(--white);
            box-shadow: 0 8px 25px rgba(124, 58, 237, 0.25);
            background: white;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .profile-img-container:hover .profile-preview {
            transform: scale(1.03);
        }
        .upload-label {
            position: absolute;
            bottom: 8px;
            right: 8px;
            background: var(--primary);
            color: white;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 3px solid white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: 0.3s;
        }
        .upload-label:hover { background: #6d28d9; transform: rotate(15deg); }

        .form-group { margin-bottom: 20px; position: relative; }
        .form-group label { 
            display: block; 
            font-size: 12px; 
            font-weight: 800; 
            color: var(--text-main); 
            margin-bottom: 8px; 
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .form-input { 
            width: 100%; 
            padding: 14px 18px; 
            border: 2px solid #f1f5f9; 
            border-radius: 16px; 
            outline: none; 
            transition: 0.3s; 
            font-size: 15px; 
            background: #f8fafc;
            color: var(--text-main);
            font-weight: 600;
        }
        .form-input:focus { 
            border-color: var(--primary); 
            background: white;
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.08); 
        }
        
        .toggle-pass { 
            position: absolute; 
            right: 18px; 
            top: 42px; 
            color: #94a3b8; 
            cursor: pointer; 
            padding: 5px; 
        }

        .section-divider { 
            margin: 30px 0 20px; 
            display: flex; 
            align-items: center; 
            color: var(--primary); 
            font-size: 13px; 
            font-weight: 800; 
            text-transform: uppercase;
            gap: 10px;
        }
        .section-divider::after { content: ""; flex: 1; height: 1px; background: #e2e8f0; }
        
        .btn-update { 
            width: 100%; 
            background: var(--primary); 
            color: white; 
            padding: 16px; 
            border: none; 
            border-radius: 16px; 
            font-weight: 700; 
            font-size: 16px;
            cursor: pointer; 
            transition: 0.3s; 
            margin-top: 15px;
            box-shadow: 0 10px 20px rgba(124, 58, 237, 0.25);
        }
        .btn-update:hover { 
            background: #6d28d9;
            transform: translateY(-2px); 
            box-shadow: 0 15px 25px rgba(124, 58, 237, 0.3);
        }
        
        .alert { 
            padding: 15px; 
            border-radius: 14px; 
            margin-bottom: 25px; 
            font-size: 14px; 
            font-weight: 600;
            text-align: center; 
        }
        .success { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
        .error { background: #fef2f2; color: #dc2626; border: 1px solid #fee2e2; }

        .back-home { 
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 25px; 
            text-decoration: none; 
            color: var(--text-sub); 
            font-size: 14px; 
            font-weight: 700; 
            gap: 8px;
            transition: 0.3s; 
        }
        .back-home:hover { color: var(--primary); }
    </style>
</head>
<body>

<div class="card">
    <div class="header">
        <h2>My Profile</h2>
        <p>Manage your account information</p>
    </div>

    <?php echo $msg; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="profile-section">
            <div class="profile-img-container">
                <img id="preview" src="../uploads/<?php echo $user['image'] ?: 'default.png'; ?>" class="profile-preview" alt="Profile">
                <label for="profile_pic" class="upload-label">
                    <i class="fas fa-camera"></i>
                </label>
                <input type="file" name="profile_pic" id="profile_pic" style="display:none" onchange="previewImage(this)">
            </div>
        </div>

        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="username" class="form-input" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>

        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone" class="form-input" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="017XXXXXXXX">
        </div>

        <div class="section-divider">Security Update</div>

        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="new_password" id="pass1" class="form-input" placeholder="••••••••">
            <i class="fas fa-eye toggle-pass" onclick="togglePass('pass1', this)"></i>
        </div>

        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" id="pass2" class="form-input" placeholder="••••••••">
            <i class="fas fa-eye toggle-pass" onclick="togglePass('pass2', this)"></i>
        </div>

        <button type="submit" name="update_profile" class="btn-update">Update Profile</button>
    </form>

    <a href="../index.php" class="back-home"><i class="fas fa-chevron-left"></i> Back to Shop</a>
</div>

<script>
function togglePass(id, icon) {
    const field = document.getElementById(id);
    if (field.type === "password") {
        field.type = "text";
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        field.type = "password";
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

</body>
</html>