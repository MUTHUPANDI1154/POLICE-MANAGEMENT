// TN Police Management System - Global Client Script
document.addEventListener("DOMContentLoaded", function () {

    /* ===================================================
       1. CITIZEN REGISTRATION FORM (register.html)
       =================================================== */
    const registerForm = document.getElementById("registerForm");
    if (registerForm) {
        registerForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const nameInput = document.getElementById("name") || document.getElementById("fullname");
            const mobileInput = document.getElementById("mobile");
            const emailInput = document.getElementById("email");
            const usernameInput = document.getElementById("username");
            const passwordInput = document.getElementById("password");
            const confirmInput = document.getElementById("confirm") || document.getElementById("confirmPassword");
            const msgBox = document.getElementById("msg") || document.getElementById("successMessage");

            const name = nameInput ? nameInput.value.trim() : "";
            const mobile = mobileInput ? mobileInput.value.trim() : "";
            const email = emailInput ? emailInput.value.trim() : "";
            const username = usernameInput ? usernameInput.value.trim() : "";
            const password = passwordInput ? passwordInput.value : "";
            const confirm = confirmInput ? confirmInput.value : "";

            if (!name || !mobile || !email || !username || !password || !confirm) {
                alert("❌ Please fill in all required fields!");
                return;
            }

            if (password !== confirm) {
                if (msgBox) {
                    msgBox.innerText = "❌ Passwords do not match!";
                    msgBox.style.color = "#ff6b6b";
                } else {
                    alert("❌ Passwords do not match!");
                }
                return;
            }

            // Save user to localStorage
            let users = JSON.parse(localStorage.getItem("tnUsers")) || [];
            
            // Check if username already exists
            if (users.some(u => u.username.toLowerCase() === username.toLowerCase())) {
                if (msgBox) {
                    msgBox.innerText = "❌ Username already exists. Please pick another!";
                    msgBox.style.color = "#ff6b6b";
                } else {
                    alert("❌ Username already exists. Please pick another!");
                }
                return;
            }

            const now = new Date();
            users.push({
                name: name,
                mobile: mobile,
                email: email,
                username: username,
                password: password,
                date: now.toLocaleDateString("en-GB"),
                time: now.toLocaleTimeString("en-US", { hour: "2-digit", minute: "2-digit" })
            });

            localStorage.setItem("tnUsers", JSON.stringify(users));

            if (msgBox) {
                msgBox.innerText = "✅ Account Created Successfully! Redirecting...";
                msgBox.style.color = "#2ecc71";
            }

            setTimeout(function () {
                window.location.href = "users.html";
            }, 800);
        });
    }

    /* ===================================================
       2. CITIZEN LOGIN FORM (login.html)
       =================================================== */
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const usernameInput = document.getElementById("loginUsername");
            const passwordInput = document.getElementById("loginPassword");
            const errorBox = document.getElementById("loginError");
            const successBox = document.getElementById("loginSuccess");
            const welcomeMsg = document.getElementById("welcomeMsg");

            const username = usernameInput ? usernameInput.value.trim() : "";
            const password = passwordInput ? passwordInput.value : "";

            if (!username || !password) {
                alert("Please enter both username and password!");
                return;
            }

            let users = JSON.parse(localStorage.getItem("tnUsers")) || [];
            let matchedUser = users.find(u => u.username.toLowerCase() === username.toLowerCase() && u.password === password);

            if (!matchedUser && username.toLowerCase() === "admin" && password === "admin123") {
                matchedUser = { name: "System Administrator", username: "admin" };
            }

            if (matchedUser) {
                sessionStorage.setItem("tnCurrentUser", JSON.stringify(matchedUser));
                if (welcomeMsg) welcomeMsg.innerHTML = `Welcome, <strong>${matchedUser.name}</strong>!`;
                if (errorBox) errorBox.style.display = "none";
                if (successBox) successBox.style.display = "block";
                loginForm.style.display = "none";

                setTimeout(function () {
                    window.location.href = "index.html";
                }, 1000);
            } else {
                if (errorBox) {
                    errorBox.style.display = "block";
                } else {
                    alert("❌ Invalid username or password!");
                }
            }
        });
    }

    /* ===================================================
       3. OFFICER REGISTRATION (officer_registration_form.html)
       =================================================== */
    const officerRegForm = document.getElementById("officerRegForm");
    if (officerRegForm) {
        officerRegForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const name = (document.getElementById("officerName")?.value || "").trim();
            const policeId = (document.getElementById("policeId")?.value || "").trim();
            const rank = document.getElementById("rank")?.value || "";
            const station = (document.getElementById("station")?.value || "").trim();
            const now = new Date();
            const dateStr = now.toLocaleDateString("en-GB") + " " + now.toLocaleTimeString("en-US", { hour: "2-digit", minute: "2-digit" });

            let officers = JSON.parse(localStorage.getItem("tnOfficers")) || [];
            officers.push({
                id: policeId,
                name: name,
                rank: rank,
                station: station,
                date: dateStr
            });
            localStorage.setItem("tnOfficers", JSON.stringify(officers));

            const confBox = document.getElementById("officerConfirmation");
            if (confBox) {
                document.getElementById("resOfficerName").innerText = name;
                document.getElementById("resPoliceId").innerText = policeId;
                document.getElementById("resRank").innerText = rank;
                document.getElementById("resStation").innerText = station;
                document.getElementById("resDate").innerText = dateStr;

                officerRegForm.style.display = "none";
                confBox.style.display = "block";
            }
        });
    }

    /* ===================================================
       4. OFFICER LOGIN & SESSION (officer_login_form.html)
       =================================================== */
    const officerLoginForm = document.getElementById("officerLoginForm");
    if (officerLoginForm) {
        officerLoginForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const name = (document.getElementById("officerName")?.value || "").trim();
            const policeId = (document.getElementById("policeId")?.value || "").trim();

            let visits = parseInt(localStorage.getItem("visit_" + policeId) || "0") + 1;
            localStorage.setItem("visit_" + policeId, visits);

            let sessionId = sessionStorage.getItem("officer_session_id");
            if (!sessionId) {
                sessionId = "TNP-" + Math.random().toString(36).substring(2, 8).toUpperCase() + "-" + Date.now().toString().slice(-4);
                sessionStorage.setItem("officer_session_id", sessionId);
            }

            const resultCard = document.getElementById("sessionResult");
            if (resultCard) {
                document.getElementById("sessOfficerName").innerText = name;
                document.getElementById("sessPoliceId").innerText = policeId;
                document.getElementById("sessId").innerText = sessionId;
                document.getElementById("sessVisits").innerText = visits;

                officerLoginForm.style.display = "none";
                resultCard.style.display = "block";
            }
        });
    }

});