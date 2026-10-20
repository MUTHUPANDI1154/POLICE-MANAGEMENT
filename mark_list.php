<?php
// mark_list.php - Presentation & Data Tier for Displaying Police Examination Mark List
session_start();
require_once 'db.php';

// Fetch mark list from Database Server (Tier 3)
$markList = [];
$conn = getDbConnection();

if ($conn) {
    $result = $conn->query("SELECT app_number, candidate_name, category, score, total_questions FROM police_exam_results ORDER BY id DESC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $markList[] = $row;
        }
    }
}

// Fallback logic to show submitted candidate details if DB is offline or empty
if (empty($markList)) {
    $submittedApp = isset($_SESSION['app_number']) ? $_SESSION['app_number'] : 'JP2025104055';
    $submittedName = isset($_SESSION['candidate_name']) ? $_SESSION['candidate_name'] : 'JobPortal Applicant';
    $submittedScore = isset($_SESSION['last_submission']['score']) ? $_SESSION['last_submission']['score'] : 3;

    $markList = [
        [
            'app_number' => $submittedApp,
            'candidate_name' => $submittedName,
            'category' => 'Software / Professional',
            'score' => $submittedScore
        ]
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Police Examination Mark List - Police Examination</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f8fafc;
            color: #1e293b;
            padding: 40px 15px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 750px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            padding: 35px;
            border: 1px solid #e2e8f0;
        }

        .header {
            text-align: center;
            margin-bottom: 24px;
        }

        .brand-title {
            color: #2563eb;
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .brand-subtitle {
            color: #64748b;
            font-size: 14px;
            margin-top: 4px;
        }

        .title-banner {
            background-color: #e0f2fe;
            border-radius: 8px;
            padding: 16px;
            text-align: center;
            margin-bottom: 24px;
            border: 1px solid #bae6fd;
        }

        .title-banner h2 {
            color: #1e3a8a;
            font-size: 22px;
            font-weight: 800;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            border-radius: 8px;
            border: 1px solid #dbeafe;
        }

        .mark-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            background-color: #ffffff;
        }

        .mark-table th {
            background-color: #dbeafe;
            color: #1e3a8a;
            font-size: 14px;
            font-weight: 700;
            padding: 14px 12px;
            border-bottom: 1px solid #bfdbfe;
        }

        .mark-table td {
            padding: 14px 12px;
            font-size: 14px;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }

        .mark-table tr:last-child td {
            border-bottom: none;
        }

        .mark-table tr:hover {
            background-color: #f8fafc;
        }

        .actions {
            text-align: center;
            margin-top: 28px;
        }

        .btn-link {
            display: inline-block;
            background-color: #2563eb;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 24px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            transition: background-color 0.2s;
        }

        .btn-link:hover {
            background-color: #1d4ed8;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1 class="brand-title">Police Examination</h1>
            <p class="brand-subtitle">Online Police Examination</p>
        </div>

        <!-- Banner -->
        <div class="title-banner">
            <h2>Police Examination Mark List</h2>
        </div>

        <!-- Mark List Table -->
        <div class="table-responsive">
            <table class="mark-table">
                <thead>
                    <tr>
                        <th>Application Number</th>
                        <th>Applicant Name</th>
                        <th>Exam Category</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($markList as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['app_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['candidate_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['category']); ?></td>
                            <td><?php echo htmlspecialchars($row['score']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Action Link -->
        <div class="actions">
            <a href="job_application.php" class="btn-link">Take Police Exam</a>
        </div>
    </div>

</body>
</html>
