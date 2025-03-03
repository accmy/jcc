
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration and Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 500px;
            margin: 40px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        $jsonFile = 'users.json';
        if (!file_exists($jsonFile)) {
            $fp = fopen($jsonFile, 'w');
            fwrite($fp, json_encode(array()));
            fclose($fp);
        }
        
        if (isset($_POST['register'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];
            
            if ($password === $confirmPassword) {
                $users = json_decode(file_get_contents($jsonFile), true);
                $users[] = array(
                    'username' => $username,
                    'email' => $email,
                    'password' => $password
                );
                file_put_contents($jsonFile, json_encode($users));
                echo '<div class="alert alert-success">Registration successful!</div>';
            } else {
                echo '<div class="alert alert-danger">Passwords do not match!</div>';
            }
        }
        
        if (isset($_POST['login'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $users = json_decode(file_get_contents($jsonFile), true);
            foreach ($users as $user) {
                if ($user['username'] === $username && $user['password'] === $password) {
                    echo '<div class="alert alert-success">Login successful!</div>';
                    // Start session and redirect to dashboard
                    session_start();
                    $_SESSION['username'] = $username;
                    header('Location: dashboard.php');
                    exit;
                }
            }
            echo '<div class="alert alert-danger">Invalid username or password!</div>';
        }
        
        if (isset($_SESSION['username'])) {
            // Show dashboard
            echo '<h2>Dashboard</h2>';
            echo '<p>Welcome, ' . $_SESSION['username'] . '!</p>';
            echo '<a href="logout.php">Logout</a>';
        } else {
            // Show registration and login forms
            echo '<h2>Registration and Login</h2>';
            echo '<form action="" method="post">
                <h3>Register</h3>
                <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password:</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-primary" name="register">Register</button>
            </form>';
            echo '<form action="" method="post">
                <h3>Login</h3>
                <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary" name="login">Login</button>
            </form>';
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>