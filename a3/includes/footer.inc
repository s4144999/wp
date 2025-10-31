<footer class="text-light py-3" style="
  background: linear-gradient(90deg, #cd4f07 0%, #f6978e 100%);
  font-family: 'Ysabeau SC', sans-serif;
  font-size: 0.9rem;
">
  <div class="container d-flex justify-content-between align-items-center">
    <div>
      <?php
// --- open a short-lived connection just for the footer ---
$__mainConn = $conn ?? null;                   // remember the page's $conn (if any)
require __DIR__ . '/db_connect.inc';           // this creates a fresh $conn
$__footConn = $conn;                           // keep a reference to the footer connection
$conn = $__mainConn;                           // restore the page's $conn so we don't break anything

$APP_BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

$links = [];
if ($__footConn instanceof mysqli) {
    if ($rs = $__footConn->query("SELECT username FROM users ORDER BY username")) {
        while ($row = $rs->fetch_assoc()) {
            $u   = (string)$row['username'];
            $url = $APP_BASE . '/instructor.php?name=' . urlencode($u);
            $links[] = '<a href="' . $url . '" class="text-light text-decoration-none me-2">'
                     . htmlspecialchars($u) . '</a>';
        }
        $rs->free();
    }
    $__footConn->close();                      // close ONLY the footer connection
}

echo $links ? implode("\n      ", $links)
            : '<span class="opacity-75">No instructors yet</span>';
?>

    </div>

    <p class="mb-0 text-end">
      © 2025 <span style="font-weight:600;">SkillSwap</span>. All rights reserved. Sumaj Maharjan
    </p>
  </div>
</footer>
