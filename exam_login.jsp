<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Three-Tier Online Examination Portal</title>
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { 
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin: 0; 
        }
        .card { 
            background: white; 
            padding: 40px; 
            border-radius: 12px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.3); 
            width: 100%; 
            max-width: 450px; 
            text-align: center; 
        }
        h2 { color: #1e3c72; margin-bottom: 8px; }
        p { color: #555; font-size: 14px; margin-bottom: 25px; }
        .form-group { text-align: left; margin-bottom: 20px; }
        label { display: block; margin-bottom: 6px; font-weight: 600; color: #333; }
        input[type="text"] { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #ccc; 
            border-radius: 6px; 
            font-size: 15px; 
        }
        input[type="text"]:focus { 
            border-color: #1e3c72; 
            outline: none; 
            box-shadow: 0 0 5px rgba(30,60,114,0.4); 
        }
        .btn-submit { 
            width: 100%; 
            padding: 14px; 
            background: #1e3c72; 
            color: white; 
            border: none; 
            border-radius: 6px; 
            font-size: 16px; 
            font-weight: bold; 
            cursor: pointer; 
            transition: background 0.3s; 
        }
        .btn-submit:hover { background: #2a5298; }
        .badge { 
            display: inline-block; 
            background: #e8effd; 
            color: #1e3c72; 
            padding: 5px 12px; 
            border-radius: 20px; 
            font-size: 12px; 
            font-weight: bold; 
            margin-bottom: 15px; 
        }
    </style>
</head>
<body>
    <div class="card">
        <span class="badge">Web Technology Lab Ex: 6.i</span>
        <h2>Online Examination Portal</h2>
        <p>Enter your registration details to start the exam</p>
        
        <form action="exam.jsp" method="post">
            <div class="form-group">
                <label for="candidateName">Student / Candidate Name:</label>
                <input type="text" id="candidateName" name="candidateName" placeholder="e.g. John Doe" required>
            </div>
            <div class="form-group">
                <label for="regNo">Register Number:</label>
                <input type="text" id="regNo" name="regNo" placeholder="e.g. 710021104001" required>
            </div>
            <button type="submit" class="btn-submit">Start Examination</button>
        </form>

        <div style="margin-top: 15px;">
            <a href="exam_online.html" style="color: #1e3c72; font-weight: bold; font-size: 13px; text-decoration: none;">📝 Direct HTML Examination Link</a> |
            <a href="index.html" style="color: #666; font-size: 13px; text-decoration: none;">Home</a>
        </div>
    </div>

    <script>
    document.querySelector("form").addEventListener("submit", function(e) {
        // If loaded directly or on live server without JSP compiler, route to exam_online.html
        if (window.location.protocol === "file:" || window.location.port === "5500" || window.location.port === "8080") {
            e.preventDefault();
            const cand = document.getElementById("candidateName").value;
            const reg = document.getElementById("regNo").value;
            sessionStorage.setItem("exam_candidate", cand);
            sessionStorage.setItem("exam_reg", reg);
            window.location.href = "exam_online.html";
        }
    });
    </script>
</body>
</html>
