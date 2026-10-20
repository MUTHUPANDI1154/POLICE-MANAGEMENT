<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="java.sql.*" %>
<%@ page import="java.util.*" %>
<%@ page import="DBConnection" %>
<%
    String candidateName = request.getParameter("candidateName");
    if (candidateName != null && !candidateName.trim().isEmpty()) {
        session.setAttribute("candidateName", candidateName);
    } else {
        candidateName = (String) session.getAttribute("candidateName");
        if (candidateName == null) candidateName = "Student";
    }
%>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Online Examination Paper</title>
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f4f7f6; margin: 0; padding: 20px; }
        .header { background: #1e3c72; color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .header h2 { margin: 0; }
        .container { max-width: 800px; margin: 0 auto; }
        .q-card { background: white; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 5px solid #1e3c72; }
        .q-title { font-weight: 600; font-size: 16px; color: #2c3e50; margin-bottom: 15px; }
        .option-group { margin-bottom: 10px; }
        .option-group label { margin-left: 8px; cursor: pointer; color: #444; }
        .btn-submit { background: #27ae60; color: white; border: none; padding: 14px 28px; font-size: 16px; font-weight: bold; border-radius: 6px; cursor: pointer; display: block; width: 100%; transition: background 0.3s; }
        .btn-submit:hover { background: #219150; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Online Examination System</h2>
            <div>Candidate: <strong><%= candidateName %></strong></div>
        </div>

        <form action="ExamServlet.php" method="post">
            <input type="hidden" name="candidateName" value="<%= candidateName %>">
            <%
                boolean loadedFromDB = false;
                int qCount = 0;

                try {
                    Connection conn = DBConnection.getConnection();
                    if (conn != null) {
                        Statement stmt = conn.createStatement();
                        ResultSet rs = stmt.executeQuery("SELECT * FROM questions ORDER BY id ASC");

                        while (rs.next()) {
                            qCount++;
                            int id = rs.getInt("id");
                            String qText = rs.getString("question_text");
                            String opt1 = rs.getString("option1");
                            String opt2 = rs.getString("option2");
                            String opt3 = rs.getString("option3");
                            String opt4 = rs.getString("option4");
            %>
                            <div class="q-card">
                                <div class="q-title">Q<%= qCount %>. <%= qText %></div>
                                <div class="option-group">
                                    <input type="radio" id="q_<%= id %>_1" name="q_<%= id %>" value="1" required>
                                    <label for="q_<%= id %>_1"><%= opt1 %></label>
                                </div>
                                <div class="option-group">
                                    <input type="radio" id="q_<%= id %>_2" name="q_<%= id %>" value="2">
                                    <label for="q_<%= id %>_2"><%= opt2 %></label>
                                </div>
                                <div class="option-group">
                                    <input type="radio" id="q_<%= id %>_3" name="q_<%= id %>" value="3">
                                    <label for="q_<%= id %>_3"><%= opt3 %></label>
                                </div>
                                <div class="option-group">
                                    <input type="radio" id="q_<%= id %>_4" name="q_<%= id %>" value="4">
                                    <label for="q_<%= id %>_4"><%= opt4 %></label>
                                </div>
                            </div>
            <%
                        }
                        rs.close();
                        stmt.close();
                        conn.close();
                        if (qCount > 0) loadedFromDB = true;
                    }
                } catch (Exception e) {
                    loadedFromDB = false;
                }

                // Fallback Question Set if DB is offline
                if (!loadedFromDB) {
                    String[][] defaultQuestions = {
                        {"1", "Which of the following is used to create a dynamic web page in Java?", "HTML", "JSP", "CSS", "SQL", "2"},
                        {"2", "What is the scope of a Java Bean created with scope='session'?", "Single Request", "Entire Application", "User Session", "Page Level", "3"},
                        {"3", "Which implicit object in JSP is used for session management?", "response", "session", "application", "request", "2"},
                        {"4", "Which package contains JDBC classes for database interaction?", "java.io", "java.util", "java.sql", "javax.servlet", "3"},
                        {"5", "In Three-Tier Architecture, which tier handles business logic?", "Presentation Tier", "Application / Logic Tier", "Database Tier", "Client Tier", "2"}
                    };
                    
                    for (int i = 0; i < defaultQuestions.length; i++) {
                        String[] q = defaultQuestions[i];
            %>
                        <div class="q-card">
                            <div class="q-title">Q<%= (i+1) %>. <%= q[1] %></div>
                            <input type="hidden" name="ans_<%= (i+1) %>" value="<%= q[6] %>">
                            <div class="option-group">
                                <input type="radio" id="fq_<%= (i+1) %>_1" name="q_<%= (i+1) %>" value="1" required>
                                <label for="fq_<%= (i+1) %>_1"><%= q[2] %></label>
                            </div>
                            <div class="option-group">
                                <input type="radio" id="fq_<%= (i+1) %>_2" name="q_<%= (i+1) %>" value="2">
                                <label for="fq_<%= (i+1) %>_2"><%= q[3] %></label>
                            </div>
                            <div class="option-group">
                                <input type="radio" id="fq_<%= (i+1) %>_3" name="q_<%= (i+1) %>" value="3">
                                <label for="fq_<%= (i+1) %>_3"><%= q[4] %></label>
                            </div>
                            <div class="option-group">
                                <input type="radio" id="fq_<%= (i+1) %>_4" name="q_<%= (i+1) %>" value="4">
                                <label for="fq_<%= (i+1) %>_4"><%= q[5] %></label>
                            </div>
                        </div>
            <%
                    }
            %>
                    <input type="hidden" name="totalQuestions" value="<%= defaultQuestions.length %>">
            <%
                }
            %>
            
            <button type="submit" class="btn-submit">Submit Examination</button>
        </form>
    </div>
</body>
</html>
