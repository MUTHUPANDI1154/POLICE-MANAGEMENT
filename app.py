from flask import Flask, render_template, request, redirect, url_for, session, flash
import mysql.connector
from werkzeug.security import generate_password_hash, check_password_hash

app = Flask(__name__, template_folder=".")
app.secret_key = "police_management_secret_key"


# =========================
# MYSQL CONFIGURATION
# =========================

DB_CONFIG = {
    "host": "localhost",
    "user": "root",
    "password": "YOUR_MYSQL_PASSWORD",
    "database": "police_db"
}


# =========================
# DATABASE CONNECTION
# =========================

def get_db_connection():
    return mysql.connector.connect(**DB_CONFIG)


# =========================
# CREATE DATABASE & TABLE
# =========================

def create_database():

    # Connect without database first
    config = {
        "host": "localhost",
        "user": "root",
        "password": "YOUR_MYSQL_PASSWORD"
    }

    conn = mysql.connector.connect(**config)
    cursor = conn.cursor()

    cursor.execute("CREATE DATABASE IF NOT EXISTS police_db")

    cursor.close()
    conn.close()

    # Connect to police_db
    conn = get_db_connection()
    cursor = conn.cursor()

    cursor.execute("""
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            fullname VARCHAR(100) NOT NULL,
            dob DATE,
            gender VARCHAR(20),
            mobile VARCHAR(20),
            email VARCHAR(100),
            address TEXT,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    """)

    conn.commit()

    cursor.close()
    conn.close()


# =========================
# LOGIN PAGE - FIRST PAGE
# =========================

@app.route("/")
def home():

    # If already logged in
    if "user_id" in session:
        return redirect(url_for("index"))

    return render_template("login.html")


# =========================
# LOGIN
# =========================

@app.route("/login", methods=["POST"])
def login():

    username = request.form.get("username")
    password = request.form.get("password")

    if not username or not password:
        return render_template(
            "login.html",
            error="Please enter username and password"
        )

    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)

    cursor.execute(
        "SELECT * FROM users WHERE username = %s",
        (username,)
    )

    user = cursor.fetchone()

    cursor.close()
    conn.close()

    if user and check_password_hash(user["password"], password):

        session["user_id"] = user["id"]
        session["username"] = user["username"]
        session["fullname"] = user["fullname"]

        return redirect(url_for("index"))

    return render_template(
        "login.html",
        error="Invalid username or password"
    )


# =========================
# REGISTER PAGE
# =========================

@app.route("/register")
def register():

    # Register page should come from login
    if "user_id" in session:
        return redirect(url_for("index"))

    return render_template("register.html")


# =========================
# REGISTER USER
# =========================

@app.route("/register", methods=["POST"])
def register_user():

    fullname = request.form.get("fullname")
    dob = request.form.get("dob")
    gender = request.form.get("gender")
    mobile = request.form.get("mobile")
    email = request.form.get("email")
    address = request.form.get("address")
    username = request.form.get("username")
    password = request.form.get("password")

    # Check empty fields
    if not fullname or not username or not password:
        return render_template(
            "register.html",
            error="Please fill all required fields"
        )

    conn = get_db_connection()
    cursor = conn.cursor()

    # Check username already exists
    cursor.execute(
        "SELECT id FROM users WHERE username = %s",
        (username,)
    )

    existing_user = cursor.fetchone()

    if existing_user:
        cursor.close()
        conn.close()

        return render_template(
            "register.html",
            error="Username already exists"
        )

    # Encrypt password
    hashed_password = generate_password_hash(password)

    cursor.execute("""
        INSERT INTO users
        (fullname, dob, gender, mobile, email, address, username, password)
        VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
    """, (
        fullname,
        dob,
        gender,
        mobile,
        email,
        address,
        username,
        hashed_password
    ))

    conn.commit()

    cursor.close()
    conn.close()

    return redirect(url_for("home"))


# =========================
# LOGOUT
# =========================

@app.route("/logout")
def logout():

    session.clear()

    return redirect(url_for("home"))


# =========================
# LOGIN REQUIRED
# =========================

def login_required():

    return "user_id" in session


# =========================
# MAIN HOME PAGE
# =========================

@app.route("/index.html")
@app.route("/index")
def index():

    if not login_required():
        return redirect(url_for("home"))

    return render_template(
        "index.html",
        fullname=session.get("fullname")
    )


# =========================
# OTHER PAGES
# =========================

@app.route("/citizen.html")
def citizen():

    if not login_required():
        return redirect(url_for("home"))

    return render_template("citizen.html")


@app.route("/complaint.html")
def complaint():
    from flask import send_from_directory
    return send_from_directory(".", "complaint.html")

@app.route("/submit_complaint", methods=["POST"])
def submit_complaint():
    from flask import jsonify
    import random
    csr_no = f"CSR-TN-2026-{random.randint(1000, 9999)}"
    return jsonify({"success": True, "csr_no": csr_no, "message": "Complaint recorded successfully"})


@app.route("/criminal.html")
def criminal():

    if not login_required():
        return redirect(url_for("home"))

    return render_template("criminal.html")


@app.route("/dashboard.html")
def dashboard():

    if not login_required():
        return redirect(url_for("home"))

    return render_template("dashboard.html")


@app.route("/fir.html")
def fir():

    if not login_required():
        return redirect(url_for("home"))

    return render_template("fir.html")


@app.route("/missing.html")
def missing():

    if not login_required():
        return redirect(url_for("home"))

    return render_template("missing.html")


@app.route("/officer.html")
def officer():

    if not login_required():
        return redirect(url_for("home"))

    return render_template("officer.html")


@app.route("/officer_registration_form.html")
def officer_registration_form():
    from flask import send_from_directory
    return send_from_directory(".", "officer_registration_form.html")


@app.route("/officer_login_form.html")
def officer_login_form():
    from flask import send_from_directory
    return send_from_directory(".", "officer_login_form.html")


@app.route("/OfficerRegisterServlet", methods=["GET", "POST"])
@app.route("/WT_LAB/OfficerRegisterServlet", methods=["GET", "POST"])
def officer_register_servlet_route():
    if request.method == "GET":
        return redirect("/officer_registration_form.html")

    officer_name = request.form.get("officerName", "N/A")
    police_id = request.form.get("policeId", "N/A")
    rank = request.form.get("rank", "N/A")
    station = request.form.get("station", "N/A")

    return f"""<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Police Officer Registration Status</title>
    <style>
        body {{ font-family: Arial, sans-serif; background: linear-gradient(120deg,#082b55,#07559a); color: white; min-height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; }}
        .box {{ width: 450px; background: rgba(255,255,255,0.13); border: 1px solid #6ca5d4; border-radius: 18px; padding: 30px 26px; box-shadow: 0 10px 30px #001d3d; text-align: center; }}
        h1 {{ color: #ffd900; margin-bottom: 5px; }}
        .badge {{ font-size: 40px; margin-bottom: 10px; }}
        table {{ width: 100%; border-collapse: collapse; margin-top: 20px; text-align: left; }}
        th, td {{ padding: 10px; border-bottom: 1px solid rgba(255,255,255,0.2); }}
        th {{ color: #ffd900; }}
        .status-msg {{ background: #2ecc71; color: #082b55; padding: 10px; border-radius: 8px; font-weight: bold; margin-top: 20px; }}
        a {{ color: #ffd900; text-decoration: none; font-weight: bold; display: inline-block; margin-top: 20px; }}
        a:hover {{ text-decoration: underline; }}
    </style>
</head>
<body>
    <div class='box'>
        <div class='badge'>👮‍♂️</div>
        <h1>Tamil Nadu Police</h1>
        <h2>Officer Registration Confirmation</h2>
        <div class='status-msg'>✅ Officer Details Registered Successfully!</div>
        <table>
            <tr><th>Officer Name</th><td>{officer_name}</td></tr>
            <tr><th>Police ID</th><td>{police_id}</td></tr>
            <tr><th>Rank / Designation</th><td>{rank}</td></tr>
            <tr><th>Police Station</th><td>{station}</td></tr>
        </table>
        <a href='/officer_registration_form.html'>← Back to Registration Form</a> | 
        <a href='/officer.html'>Officer Dashboard</a>
    </div>
</body>
</html>"""


@app.route("/OfficerLoginServlet", methods=["GET", "POST"])
@app.route("/WT_LAB/OfficerLoginServlet", methods=["GET", "POST"])
def officer_login_servlet_route():
    if request.method == "GET":
        return redirect("/officer_login_form.html")

    officer_name = request.form.get("officerName", "")
    police_id = request.form.get("policeId", "")

    import uuid
    session["officerName"] = officer_name
    session["policeId"] = police_id
    session_id = session.get("session_id", str(uuid.uuid4())[:18])
    session["session_id"] = session_id

    return f"""<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Police Officer Session Portal</title>
    <style>
        body {{ font-family: Arial, sans-serif; background: linear-gradient(120deg,#082b55,#07559a); color: white; min-height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; }}
        .box {{ width: 480px; background: rgba(255,255,255,0.13); border: 1px solid #6ca5d4; border-radius: 18px; padding: 30px 26px; box-shadow: 0 10px 30px #001d3d; text-align: center; }}
        h1 {{ color: #ffd900; margin-bottom: 5px; }}
        .badge {{ font-size: 40px; margin-bottom: 10px; }}
        .success-msg {{ background: #2ecc71; color: #082b55; padding: 12px; border-radius: 8px; font-weight: bold; margin: 15px 0; font-size: 16px; }}
        table {{ width: 100%; border-collapse: collapse; margin-top: 15px; text-align: left; }}
        th, td {{ padding: 10px; border-bottom: 1px solid rgba(255,255,255,0.2); word-break: break-all; }}
        th {{ color: #ffd900; width: 35%; }}
        a {{ color: #ffd900; text-decoration: none; font-weight: bold; display: inline-block; margin-top: 20px; }}
        a:hover {{ text-decoration: underline; }}
    </style>
</head>
<body>
    <div class='box'>
        <div class='badge'>🚔</div>
        <h1>TN Police Officer Portal</h1>
        <h2>Session Tracking Demo</h2>
        <div class='success-msg'>✅ Login Successful! Welcome Officer</div>
        <table>
            <tr><th>Officer Name</th><td>{officer_name}</td></tr>
            <tr><th>Police ID</th><td>{police_id}</td></tr>
            <tr><th>Session ID</th><td>{session_id}</td></tr>
            <tr><th>Session Status</th><td>Active (New Session: True)</td></tr>
        </table>
        <a href='/officer_login_form.html'>← Logout / Login Again</a> | 
        <a href='/officer.html'>Officer Dashboard</a>
    </div>
</body>
</html>"""



# =========================
# STATIC ASSETS & NEW PAGES
# =========================

@app.route("/style.css")
def style():
    from flask import send_from_directory
    return send_from_directory(".", "style.css")

@app.route("/script.js")
def script_file():
    from flask import send_from_directory
    return send_from_directory(".", "script.js")

@app.route("/complaints.html")
def complaints_page():
    from flask import send_from_directory
    return send_from_directory(".", "complaints.html")

@app.route("/officers.html")
def officers_page():
    from flask import send_from_directory
    return send_from_directory(".", "officers.html")

@app.route("/users.html")
def users_page():
    from flask import send_from_directory
    return send_from_directory(".", "users.html")

@app.route("/exam_online.html")
def exam_online_page():
    from flask import send_from_directory
    return send_from_directory(".", "exam_online.html")

@app.route("/exam_marks.html")
def exam_marks_page():
    from flask import send_from_directory
    return send_from_directory(".", "exam_marks.html")

@app.route("/police management.png")
def police_img():
    from flask import send_from_directory
    return send_from_directory(".", "police management.png")

@app.route("/police.xml")
def police_xml():
    from flask import send_from_directory
    return send_from_directory(".", "police.xml")

@app.route("/police.xsl")
def police_xsl():
    from flask import send_from_directory
    return send_from_directory(".", "police.xsl")

# =========================
# EX:6 ONLINE EXAMINATION SYSTEM
# =========================

@app.route("/exam_login.jsp")
@app.route("/exam_login")
@app.route("/WT_LAB/exam_login.jsp")
@app.route("/WT_LAB/exam_login")
def exam_login():
    from flask import Response
    try:
        with open("exam_login.jsp", "r", encoding="utf-8") as f:
            content = f.read()
        return Response(content, mimetype="text/html")
    except Exception as e:
        return f"Error loading page: {e}", 500


@app.route("/exam.jsp", methods=["GET", "POST"])
@app.route("/exam", methods=["GET", "POST"])
@app.route("/WT_LAB/exam.jsp", methods=["GET", "POST"])
@app.route("/WT_LAB/exam", methods=["GET", "POST"])
def exam_page():
    candidate_name = request.form.get("candidateName") or request.args.get("candidateName") or "Student"
    session["candidateName"] = candidate_name

    questions_list = []
    try:
        conn = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="online_exam"
        )
        cursor = conn.cursor(dictionary=True)
        cursor.execute("SELECT * FROM questions ORDER BY id ASC")
        questions_list = cursor.fetchall()
        cursor.close()
        conn.close()
    except Exception as e:
        print("MySQL online_exam fetch fallback:", e)

    if not questions_list:
        questions_list = [
            {"id": 1, "question_text": "Which of the following is used to create a dynamic web page in Java?", "option1": "HTML", "option2": "JSP", "option3": "CSS", "option4": "SQL", "correct_option": 2},
            {"id": 2, "question_text": "What is the scope of a Java Bean created with scope='session'?", "option1": "Single Request", "option2": "Entire Application", "option3": "User Session", "option4": "Page Level", "correct_option": 3},
            {"id": 3, "question_text": "Which implicit object in JSP is used for session management?", "option1": "response", "option2": "session", "option3": "application", "option4": "request", "correct_option": 2},
            {"id": 4, "question_text": "Which package contains JDBC classes for database interaction?", "option1": "java.io", "option2": "java.util", "option3": "java.sql", "option4": "javax.servlet", "correct_option": 3},
            {"id": 5, "question_text": "In Three-Tier Architecture, which tier handles business logic?", "option1": "Presentation Tier", "option2": "Application / Logic Tier", "option3": "Database Tier", "option4": "Client Tier", "correct_option": 2}
        ]

    q_cards = ""
    for idx, q in enumerate(questions_list, 1):
        q_cards += f"""
        <div class="q-card">
            <div class="q-title">Q{idx}. {q['question_text']}</div>
            <input type="hidden" name="ans_{idx}" value="{q.get('correct_option', 2)}">
            <div class="option-group">
                <input type="radio" id="q_{idx}_1" name="q_{q['id']}" value="1" required>
                <label for="q_{idx}_1">{q['option1']}</label>
            </div>
            <div class="option-group">
                <input type="radio" id="q_{idx}_2" name="q_{q['id']}" value="2">
                <label for="q_{idx}_2">{q['option2']}</label>
            </div>
            <div class="option-group">
                <input type="radio" id="q_{idx}_3" name="q_{q['id']}" value="3">
                <label for="q_{idx}_3">{q['option3']}</label>
            </div>
            <div class="option-group">
                <input type="radio" id="q_{idx}_4" name="q_{q['id']}" value="4">
                <label for="q_{idx}_4">{q['option4']}</label>
            </div>
        </div>
        """

    return f"""<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TN Police - Online Examination Paper</title>
    <link rel="stylesheet" href="/style.css">
    <style>
        .exam-header {{
            background: #003b63;
            color: white;
            padding: 20px 30px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }}
        .exam-header h2 {{
            color: white;
            margin: 0;
            font-size: 24px;
        }}
        .exam-container {{
            max-width: 850px;
            margin: 30px auto;
            padding: 0 20px;
        }}
        .q-card {{
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
            border-left: 6px solid #0077a8;
        }}
        .q-title {{
            font-weight: bold;
            font-size: 17px;
            color: #003b63;
            margin-bottom: 15px;
        }}
        .option-group {{
            margin-bottom: 12px;
            padding: 8px 12px;
            border-radius: 6px;
            background: #f8fbff;
            border: 1px solid #e1eeff;
        }}
        .option-group:hover {{
            background: #eaf4ff;
        }}
        .option-group label {{
            margin-left: 8px;
            cursor: pointer;
            color: #222;
            font-size: 15px;
        }}
        .btn-submit-exam {{
            background: #003b63;
            color: white;
            border: none;
            padding: 16px 32px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 30px;
            cursor: pointer;
            display: block;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            margin-top: 30px;
        }}
        .btn-submit-exam:hover {{
            background: #ffd700;
            color: #003b63;
            transform: translateY(-2px);
        }}
    </style>
</head>
<body>
    <div class="exam-container">
        <div class="exam-header">
            <h2>📝 TN Police Online Examination</h2>
            <div>Candidate: <strong>{candidate_name}</strong></div>
        </div>
        <form action="/ExamServlet" method="post">
            <input type="hidden" name="candidateName" value="{candidate_name}">
            <input type="hidden" name="totalQuestions" value="{len(questions_list)}">
            {q_cards}
            <button type="submit" class="btn-submit-exam">Submit Examination</button>
        </form>
    </div>
</body>
</html>"""


@app.route("/ExamServlet", methods=["GET", "POST"])
@app.route("/WT_LAB/ExamServlet", methods=["GET", "POST"])
def exam_servlet_route():
    candidate_name = request.form.get("candidateName") or session.get("candidateName") or "Student"
    total_q = int(request.form.get("totalQuestions", 5))
    score = 0

    for i in range(1, total_q + 1):
        user_ans = request.form.get(f"q_{i}")
        correct_ans = request.form.get(f"ans_{i}")
        if user_ans and correct_ans and user_ans == correct_ans:
            score += 1
        elif user_ans:
            try:
                conn = mysql.connector.connect(host="localhost", user="root", password="", database="online_exam")
                cursor = conn.cursor(dictionary=True)
                cursor.execute("SELECT correct_option FROM questions WHERE id = %s", (i,))
                row = cursor.fetchone()
                if row and str(row['correct_option']) == str(user_ans):
                    score += 1
                cursor.close()
                conn.close()
            except Exception:
                pass

    percentage = round((score / total_q) * 100, 2) if total_q > 0 else 0
    status = "PASS" if percentage >= 50 else "FAIL"
    is_pass = (status == "PASS")

    try:
        conn = mysql.connector.connect(host="localhost", user="root", password="", database="online_exam")
        cursor = conn.cursor()
        cursor.execute("CREATE TABLE IF NOT EXISTS exam_results (id INT AUTO_INCREMENT PRIMARY KEY, candidate_name VARCHAR(100), score INT, total_questions INT, percentage DOUBLE, status VARCHAR(20))")
        cursor.execute("INSERT INTO exam_results (candidate_name, score, total_questions, percentage, status) VALUES (%s, %s, %s, %s, %s)",
                       (candidate_name, score, total_q, percentage, status))
        conn.commit()
        cursor.close()
        conn.close()
    except Exception as e:
        print("MySQL result save notice:", e)

    return f"""<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TN Police - Examination Result</title>
    <link rel="stylesheet" href="/style.css">
    <style>
        .result-container {{
            width: 100%;
            max-width: 520px;
            margin: 60px auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            text-align: center;
        }}
        .status-badge {{
            display: inline-block;
            padding: 8px 24px;
            border-radius: 30px;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }}
        .pass {{ background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }}
        .fail {{ background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }}
        .score-box {{ background: #f0f7ff; border-radius: 10px; padding: 20px; margin: 20px 0; border: 2px dashed #0077a8; }}
        .score-num {{ font-size: 38px; font-weight: bold; color: #003b63; }}
        .score-total {{ font-size: 18px; color: #7f8c8d; }}
        .detail-row {{ display: flex; justify-content: space-between; margin: 12px 0; font-size: 16px; border-bottom: 1px dashed #eee; padding-bottom: 8px; }}
        .btn-action {{ display: inline-block; background: #003b63; color: white; text-decoration: none; padding: 12px 24px; border-radius: 25px; font-weight: bold; margin: 10px 5px; transition: all 0.3s ease; }}
        .btn-action:hover {{ background: #ffd700; color: #003b63; }}
    </style>
</head>
<body class="login-page">
    <div class="result-container">
        <div style="font-size: 45px; margin-bottom: 10px;">📊</div>
        <h1 style="color: #003b63; font-size: 26px; margin-bottom: 15px;">TN Police Portal</h1>
        <h2>Examination Result</h2>

        <div class="status-badge {'pass' if is_pass else 'fail'}">
            Result: {status}
        </div>

        <div class="score-box">
            <div class="score-num">{score} <span class="score-total">/ {total_q}</span></div>
            <div style="color: #003b63; font-size: 14px; margin-top: 5px; font-weight: bold;">Total Score</div>
        </div>

        <div class="detail-row">
            <span>Candidate Name:</span>
            <strong>{candidate_name}</strong>
        </div>
        <div class="detail-row">
            <span>Percentage:</span>
            <strong>{percentage}%</strong>
        </div>
        <div class="detail-row">
            <span>Passing Criteria:</span>
            <strong>50.00%</strong>
        </div>

        <div style="margin-top: 25px;">
            <a href="/exam_login.jsp" class="btn-action">Retake Test</a>
            <a href="/index.html" class="btn-action" style="background: #0077a8;">Back to Home</a>
        </div>
    </div>
</body>
</html>"""


# =========================
# START SERVER
# =========================

if __name__ == "__main__":

    try:
        create_database()
    except Exception as e:
        print("Database skip:", e)

    app.run(
        host="0.0.0.0",
        port=8080,
        debug=True
    )