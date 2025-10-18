<?php
/* /wp/a3/register.php */
session_start();
include __DIR__ . '/includes/db_connect.inc';

// Initialize variables
$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];

    // Validate inputs
    if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
        $message = "<div class='alert alert-warning'>⚠️ Please fill in all fields.</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='alert alert-warning'>⚠️ Invalid email address.</div>";
    } elseif ($password !== $confirm) {
        $message = "<div class='alert alert-danger'>❌ Passwords do not match.</div>";
    } elseif (strlen($password) < 6) {
        $message = "<div class='alert alert-warning'>⚠️ Password must be at least 6 characters long.</div>";
    } else {
        // Check if email already exists
        $check = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "<div class='alert alert-danger'>❌ Email already registered. Please login instead.</div>";
        } else {
            // Hash the password
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, created_at)
                                    VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("sss", $username, $email, $hashed);

            if ($stmt->execute()) {
                $_SESSION['flash'] = "✅ Registration successful! Please login.";
                header("Location: login.php");
                exit;
            } else {
                $message = "<div class='alert alert-danger'>❌ Database error: " . htmlspecialchars($stmt->error) . "</div>";
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'includes/header.inc'; ?>

<body>
<?php include 'includes/nav.inc'; ?>

<main class="container py-5" style="max-width:600px;">
    <h1 class="mb-4 text-center">Create an Account</h1>

    <!-- Flash message -->
    <?php if (!empty($message)) echo $message; ?>

    <form method="POST" action="register.php" novalidate>
        <!-- Username -->
        <div class="mb-3">
            <label for="username" class="form-label">Username *</label>
            <input type="text" class="form-control" id="username" name="username"
                   value="<?= isset($username) ? htmlspecialchars($username) : '' ?>" required>
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">Email address *</label>
            <input type="email" class="form-control" id="email" name="email"
                   value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Password *</label>
            <input type="password" class="form-control" id="password" name="password"
                   placeholder="At least 6 characters" required>
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="confirm" class="form-label">Confirm Password *</label>
            <input type="password" class="form-control" id="confirm" name="confirm" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Register</button>

        <p class="mt-3 text-center">
            Already have an account? <a href="login.php">Login here</a>.
        </p>
    </form>
</main>

<?php include 'includes/footer.inc'; ?>
</body>
</html>
