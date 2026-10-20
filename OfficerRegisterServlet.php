<?php
// OfficerRegisterServlet.php - PHP Backend for Officer Registration Form
header('Content-Type: text/html; charset=UTF-8');

$officerName = isset($_POST['officerName']) ? htmlspecialchars($_POST['officerName']) : 'N/A';
$policeId = isset($_POST['policeId']) ? htmlspecialchars($_POST['policeId']) : 'N/A';
$rank = isset($_POST['rank']) ? htmlspecialchars($_POST['rank']) : 'N/A';
$station = isset($_POST['station']) ? htmlspecialchars($_POST['station']) : 'N/A';
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Police Officer Registration Status</title>
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
        <div class='badge'>👮‍♂️</div>
        <h1>Tamil Nadu Police</h1>
        <h2>Officer Registration Confirmation</h2>
        <div class='status-msg'>✅ Officer Details Registered Successfully!</div>
        <table>
            <tr><th>Officer Name</th><td><?php echo $officerName; ?></td></tr>
            <tr><th>Police ID</th><td><?php echo $policeId; ?></td></tr>
            <tr><th>Rank / Designation</th><td><?php echo $rank; ?></td></tr>
            <tr><th>Police Station</th><td><?php echo $station; ?></td></tr>
        </table>
        <a href='officer.html'>← Back to Officer Dashboard</a>
    </div>
</body>
</html>
