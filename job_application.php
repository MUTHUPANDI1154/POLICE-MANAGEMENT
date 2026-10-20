<?php
// job_application.php - Police Examination Application Form
session_start();

// Retrieve or generate default Application Number (matching JP2025104055 format)
if (!isset($_SESSION['app_number'])) {
    $_SESSION['app_number'] = 'JP2025104055';
}
$appNumber = $_SESSION['app_number'];

// Retrieve or generate default Applicant Name
$candidateName = isset($_SESSION['candidate_name']) ? $_SESSION['candidate_name'] : 'JobPortal Applicant';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Police Examination - Police Examination</title>
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
            padding: 30px 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            width: 100%;
            max-width: 580px;
            padding: 30px 35px;
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

        .page-title {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 16px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
            color: #334155;
            background-color: #ffffff;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .q-card {
            background-color: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 8px;
            padding: 16px 20px;
            margin-bottom: 16px;
        }

        .q-title {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
        }

        .option-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 14px;
            color: #334155;
            cursor: pointer;
        }

        .option-item:last-child {
            margin-bottom: 0;
        }

        .option-item input[type="radio"] {
            margin-right: 10px;
            accent-color: #2563eb;
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .option-item label {
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            background-color: #2563eb;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            padding: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background-color: #1d4ed8;
        }

        .nav-link {
            display: block;
            text-align: center;
            margin-top: 16px;
            color: #2563eb;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }

        .nav-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="card">
        <!-- Header -->
        <div class="header">
            <h1 class="brand-title">Police Examination</h1>
            <p class="brand-subtitle">Online Police Examination</p>
        </div>

        <h2 class="page-title">Police Examination Application</h2>

        <form action="job_result.php" method="POST">
            <!-- Application Number -->
            <div class="form-group">
                <label class="form-label" for="app_number">Application Number</label>
                <input type="text" id="app_number" name="app_number" class="form-input" value="<?php echo htmlspecialchars($appNumber); ?>" required>
            </div>

            <!-- Applicant Name -->
            <div class="form-group">
                <label class="form-label" for="candidate_name">Applicant Name</label>
                <input type="text" id="candidate_name" name="candidate_name" class="form-input" value="<?php echo htmlspecialchars($candidateName); ?>" placeholder="Enter applicant name" required>
            </div>

            <!-- Question 1 -->
            <div class="q-card">
                <div class="q-title">1. What is the primary duty of a police officer?</div>
                <div class="option-item">
                    <input type="radio" id="q1_opt1" name="q1" value="1" required checked>
                    <label for="q1_opt1">Law Enforcement & Public Safety</label>
                </div>
                <div class="option-item">
                    <input type="radio" id="q1_opt2" name="q1" value="2">
                    <label for="q1_opt2">Cooking</label>
                </div>
                <div class="option-item">
                    <input type="radio" id="q1_opt3" name="q1" value="3">
                    <label for="q1_opt3">Driving Taxi</label>
                </div>
            </div>

            <!-- Question 2 -->
            <div class="q-card">
                <div class="q-title">2. What does 'FIR' stand for in police procedure?</div>
                <div class="option-item">
                    <input type="radio" id="q2_opt1" name="q2" value="1" required checked>
                    <label for="q2_opt1">First Information Report</label>
                </div>
                <div class="option-item">
                    <input type="radio" id="q2_opt2" name="q2" value="2">
                    <label for="q2_opt2">Fast Incident Receipt</label>
                </div>
                <div class="option-item">
                    <input type="radio" id="q2_opt3" name="q2" value="3">
                    <label for="q2_opt3">Final Investigation Record</label>
                </div>
            </div>

            <!-- Question 3 -->
            <div class="q-card">
                <div class="q-title">3. Which official document is submitted to court after completing an investigation?</div>
                <div class="option-item">
                    <input type="radio" id="q3_opt1" name="q3" value="1" required checked>
                    <label for="q3_opt1">Charge Sheet</label>
                </div>
                <div class="option-item">
                    <input type="radio" id="q3_opt2" name="q3" value="2">
                    <label for="q3_opt2">Passport</label>
                </div>
                <div class="option-item">
                    <input type="radio" id="q3_opt3" name="q3" value="3">
                    <label for="q3_opt3">Movie Ticket</label>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-submit">Submit Application</button>
        </form>

        <a href="mark_list.php" class="nav-link">📊 View Police Examination Mark List</a>
    </div>

</body>
</html>
