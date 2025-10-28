<?php
/* /wp/a3/login.php */
session_start();
include __DIR__ . '/includes/db_connect.inc';

$message = "";

// Handle flash message from register.php
if (isset($_SESSION['flash'])) {
    $message = "<div class='alert alert-success'>" . $_SESSION['flash'] . "</div>";
    unset($_SESSION['flash']);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $message = "<div class='alert alert-warning'>⚠️ Please fill in all fields.</div>";
    } else {
        // Find user by email
        $stmt = $conn->prepare("SELECT user_id, username, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Login successful → set session
                $_SESSION['user_id']  = (int)$user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['flash'] = "✅ Welcome back, " . htmlspecialchars($user['username']) . "!";
                header("Location: index.php");
                exit;
            } else {
                $message = "<div class='alert alert-danger'> Incorrect password.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'> No account found with that email.</div>";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'includes/header.inc'; ?>
<body>
<?php include 'includes/nav.inc'; ?>

<main class="container py-5" style="max-width:600px;">
    <h1 class="mb-4 text-center">Login</h1>

    <!-- Flash message -->
    <?php if (!empty($message)) echo $message; ?>

    <form method="POST" action="login.php" novalidate>
        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">Email address *</label>
            <input type="email" class="form-control" id="email" name="email"
                   value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Password *</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <!-- Button -->
<button type="submit" class="w-100" 
style="background-color:#c84c0c; color:white; border:none; padding:10px; font-weight:600; border-radius:4px;">
  Register
</button>

        <p class="mt-3 text-center">
            Don’t have an account? <a href="register.php">Register here</a>.
        </p>
    </form>
</main>

<?php include 'includes/footer.inc'; ?>
</body>
</html>
