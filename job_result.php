<?php
// job_result.php - Logic Tier & Presentation Tier for Police Examination Result
session_start();
require_once 'db.php';

// Reset application number if requested
if (isset($_GET['reset']) && $_GET['reset'] == '1') {
    unset($_SESSION['app_number']);
    unset($_SESSION['candidate_name']);
    unset($_SESSION['last_submission']);
    header('Location: job_application.php');
    exit;
}

// Retrieve Application Details from POST form
$appNumber = isset($_POST['app_number']) && !empty($_POST['app_number']) ? trim($_POST['app_number']) : (isset($_SESSION['app_number']) ? $_SESSION['app_number'] : 'JP2025104055');
$candidateName = isset($_POST['candidate_name']) && !empty($_POST['candidate_name']) ? trim($_POST['candidate_name']) : (isset($_SESSION['candidate_name']) ? $_SESSION['candidate_name'] : 'JobPortal Applicant');
$category = 'Police Constable / SI';

// Save in Session for persistence across pages
$_SESSION['app_number'] = $appNumber;
$_SESSION['candidate_name'] = $candidateName;

// Police Exam Answers Evaluation (Logic Tier)
$correctAnswers = [
    'q1' => '1', // Law Enforcement & Public Safety
    'q2' => '1', // First Information Report
    'q3' => '1'  // Charge Sheet
];

$score = 0;
$totalQuestions = 3;

foreach ($correctAnswers as $qKey => $correctVal) {
    if (isset($_POST[$qKey]) && $_POST[$qKey] === $correctVal) {
        $score++;
    }
}

// Save recent submission details into Session
$_SESSION['last_submission'] = [
    'app_number' => $appNumber,
    'candidate_name' => $candidateName,
    'category' => $category,
    'score' => $score,
    'total_questions' => $totalQuestions
];

// Save result to Database Tier if MySQL is connected
$conn = getDbConnection();
if ($conn) {
    $stmt = $conn->prepare("INSERT INTO police_exam_results (app_number, candidate_name, category, score, total_questions) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sssii", $appNumber, $candidateName, $category, $score, $totalQuestions);
        $stmt->execute();
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Police Examination Submitted - Police Examination</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #ffffff;
            color: #1e293b;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 650px;
            text-align: center;
            padding: 20px;
        }

        .success-title {
            color: #16a34a;
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .app-subheading {
            color: #1e3a8a;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .app-number-text {
            color: #1e3a8a;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 28px;
        }

        .score-card {
            background-color: #e6f4ea;
            border-radius: 12px;
            padding: 24px;
            max-width: 480px;
            margin: 0 auto 28px auto;
        }

        .score-label {
            color: #1f2937;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .score-value {
            color: #16a34a;
            font-size: 48px;
            font-weight: 800;
            line-height: 1.2;
        }

        .confirmation-message {
            color: #16a34a;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 32px;
        }

        .btn-group {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-action {
            display: inline-block;
            background-color: #2563eb;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            transition: background-color 0.2s;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2);
        }

        .btn-action:hover {
            background-color: #1d4ed8;
        }

        .btn-secondary {
            background-color: #0284c7;
        }

        .btn-secondary:hover {
            background-color: #0369a1;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Main Heading -->
        <h1 class="success-title">Police Examination Submitted</h1>

        <!-- Subheading -->
        <h2 class="app-subheading">Police Examination Online Application</h2>

        <!-- Application Number -->
        <p class="app-number-text">Application Number: <?php echo htmlspecialchars($appNumber); ?></p>

        <!-- Exam Score Card -->
        <div class="score-card">
            <div class="score-label">Your Exam Score (Mark):</div>
            <div class="score-value"><?php echo $score; ?> / <?php echo $totalQuestions; ?></div>
        </div>

        <!-- Confirmation Message -->
        <p class="confirmation-message">Your police examination has been successfully submitted.</p>

        <!-- Buttons -->
        <div class="btn-group">
            <a href="job_result.php?reset=1" class="btn-action">Apply for Another Exam</a>
            <a href="mark_list.php" class="btn-action btn-secondary">View Police Mark List</a>
        </div>
    </div>

</body>
</html>
