<?php
// OfficerLoginServlet.php - PHP Session Tracking Backend for Officer Login Form
session_start();
header('Content-Type: text/html; charset=UTF-8');

$officerName = isset($_POST['officerName']) ? htmlspecialchars($_POST['officerName']) : 'Officer';
$policeId = isset($_POST['policeId']) ? htmlspecialchars($_POST['policeId']) : 'TNP-0000';

if (!isset($_SESSION['visit_count'])) {
    $_SESSION['visit_count'] = 1;
} else {
    $_SESSION['visit_count']++;
}
$visitCount = $_SESSION['visit_count'];
$sessionId = session_id();
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Officer Session Tracking</title>
    <style>
        body { font-family: Arial, sans-serif; background: linear-gradient(120deg, #082b55, #07559a); color: white; min-height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; }
        .box { width: 450px; background: rgba(255,255,255,0.13); border: 1px solid #6ca5d4; border-radius: 18px; padding: 30px 26px; box-shadow: 0 10px 30px #001d3d; text-align: center; }
        h1 { color: #ffd900; margin-bottom: 5px; }
        .badge { font-size: 40px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; text-align: left; }
        th, td { padding: 10px; border-bottom: 1px solid rgba(255,255,255,0.2); }
        th { color: #ffd900; }
        .status-msg { background: #2ecc71; color: #082b55; padding: 10px; border-radius: 8px; font-weight: bold; margin-top: 20px; }
        a { color: #ffd900; text-decoration: none; font-weight: bold; display: inline-block; margin-top: 20px; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class='box'>
        <div class='badge'>🚔</div>
        <h1>Tamil Nadu Police</h1>
        <h2>Officer Session Tracking</h2>
        <div class='status-msg'>✅ Logged in & Session Tracked Successfully!</div>
        <table>
            <tr><th>Officer Name</th><td><?php echo $officerName; ?></td></tr>
            <tr><th>Police ID</th><td><?php echo $policeId; ?></td></tr>
            <tr><th>Session ID</th><td><small><?php echo $sessionId; ?></small></td></tr>
            <tr><th>Number of Visits</th><td><strong><?php echo $visitCount; ?></strong></td></tr>
        </table>
        <a href='officer.html'>← Back to Officer Dashboard</a>
    </div>
</body>
</html>
