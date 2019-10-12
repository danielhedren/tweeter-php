(function() {
    "use strict;"

    /*
        Utility method for API calls
    */
    function postJson(data, callback) {
        let xmlHttp = new XMLHttpRequest();
        xmlHttp.open("POST", "/api/v1.php", true); // true for asynchronous
        xmlHttp.setRequestHeader("Content-Type", "application/json");
        xmlHttp.send(JSON.stringify(data));
        xmlHttp.onreadystatechange = () => {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                let jsonResponse;
                // Break if response is unparseable
                try {
                    jsonResponse = JSON.parse(xmlHttp.response);
                } catch (e) {
                    return;
                }

                callback(jsonResponse);
            }
        }
    }

    /*
        Login
    */
    function login(email, password) {
        postJson({
            "function": "verify_user",
            "email": email,
            "password": password
        }, (response) => {
            if (response["status"]) {
                document.querySelector("#loginModal > div > div > div.modal-header > button").click();

                document.querySelector("#navLoggedOut").style.display = "none";
                document.querySelector("#navLoggedIn").style.display = "block";
            }
            document.querySelector("#loginModal .alert").style.display = response["status"] ? "none" : "block";
        });
    }

    document.querySelector("#loginButton").onclick = () => {
        if (!document.querySelector("#loginForm").reportValidity()) return;

        let email = document.querySelector("#loginEmail").value;
        let password = document.querySelector("#loginPassword").value;

        login(email, password);
    }

    /*
        Register
    */
    document.querySelector("#registerButton").onclick = () => {
        if (!document.querySelector("#registerForm").reportValidity()) return;

        let email = document.querySelector("#registerEmail").value;
        let displayname = document.querySelector("#registerDisplayname").value;
        let password = document.querySelector("#registerPassword1").value;
        let password2 = document.querySelector("#registerPassword2").value;

        if (password != password2) {
            return;
        }

        postJson({
            "function": "create_user",
            "email": email,
            "displayname": displayname,
            "password": password
        }, (response) => {
            if (response["status"]) {
                document.querySelector("#registerModal > div > div > div.modal-header > button").click();
                login(email, password);
            }
            document.querySelector("#registerModal .alert").style.display = response["status"] ? "none" : "block";
        });
    }

    /*
        Logout
    */
    function logout() {
        postJson({
            "function": "logout_user"
        }, (response) => {
            if (response["status"]) {
                document.querySelector("#navLoggedIn").style.display = "none";
                document.querySelector("#navLoggedOut").style.display = "block";
            }
        });
    }

    document.querySelector("#logoutButton").onclick = () => {
        logout();
    }

    /*
        Post comment
    */
    document.querySelector("#commentButton").onclick = () => {
        let content = document.querySelector("#commentText").value;

        postJson({
            "function": "create_comment",
            "content": content
        }, (response) => {
            if (response["status"]) {
                document.querySelector("#commentModal > div > div > div.modal-header > button").click();
                fetchComments();
            }
            document.querySelector("#commentModal .alert").style.display = response["status"] ? "none" : "block";
        });
    }

    /*
        Fetch comments
    */
    function fetchComments(num, page) {
        postJson({
            "function": "fetch_comments"
        }, (response) => {
            let container = document.getElementById("commentsContainer");
            container.innerHTML = "";

            response.forEach((comment) => {
                container.innerHTML += getCommentCardHtml(comment);
            });
        });
    }

    /*
        Format comment cards
    */
    function getCommentCardHtml(comment) {
        let html = '<div class="card mt-3"><div class="card-header"><h5 class="card-title">';
        html += comment["displayname"];
        html += '</h5><h6 class="card-subtitle mb-2 text-muted">';
        html += comment["date"];
        html += '</h6></div><div class="card-body"><p class="card-text">';
        html += comment["content"];
        html += '</p></div></div>';
        return html;
    }
})();