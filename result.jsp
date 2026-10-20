<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%
    String candidateName = (String) request.getAttribute("candidateName");
    Object scoreObj = request.getAttribute("score");
    Object totalObj = request.getAttribute("totalQuestions");
    String percentage = (String) request.getAttribute("percentage");
    String status = (String) request.getAttribute("status");

    if (candidateName == null) candidateName = "Student";
    int score = (scoreObj != null) ? (Integer) scoreObj : 0;
    int total = (totalObj != null) ? (Integer) totalObj : 0;
    if (percentage == null) percentage = "0.00";
    if (status == null) status = "N/A";

    boolean isPass = "PASS".equalsIgnoreCase(status);
%>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Examination Result</title>
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; padding: 20px; }
        .result-card { background: white; border-radius: 12px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); width: 100%; max-width: 480px; text-align: center; }
        .status-badge { display: inline-block; padding: 8px 24px; border-radius: 30px; font-weight: bold; font-size: 18px; margin-bottom: 20px; text-transform: uppercase; }
        .pass { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .fail { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .score-box { background: #f8f9fa; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .score-num { font-size: 36px; font-weight: bold; color: #2c3e50; }
        .score-total { font-size: 18px; color: #7f8c8d; }
        .detail-row { display: flex; justify-content: space-between; margin: 12px 0; font-size: 15px; border-bottom: 1px dashed #eee; padding-bottom: 8px; }
        .btn-home { display: inline-block; background: #1e3c72; color: white; text-decoration: none; padding: 12px 28px; border-radius: 6px; font-weight: bold; margin-top: 20px; transition: background 0.3s; }
        .btn-home:hover { background: #2a5298; }
    </style>
</head>
<body>
    <div class="result-card">
        <h2>Examination Result</h2>
        <div class="status-badge <%= isPass ? "pass" : "fail" %>">
            Result: <%= status %>
        </div>

        <div class="score-box">
            <div class="score-num"><%= score %> <span class="score-total">/ <%= total %></span></div>
            <div style="color: #666; font-size: 14px; margin-top: 5px;">Your Total Score</div>
        </div>

        <div class="detail-row">
            <span>Candidate Name:</span>
            <strong><%= candidateName %></strong>
        </div>
        <div class="detail-row">
            <span>Percentage:</span>
            <strong><%= percentage %>%</strong>
        </div>
        <div class="detail-row">
            <span>Passing Marks:</span>
            <strong>50.00%</strong>
        </div>

        <a href="exam_login.jsp" class="btn-home">Take Another Test</a>
    </div>
</body>
</html>
