(function () {
    "use strict;"

    /*
        Utility method for API calls
    */
    function apiRequest(data) {
        return new Promise((resolve, reject) => {
            let xmlHttp = new XMLHttpRequest();
            xmlHttp.open("POST", "/api/v1.php", true); // true for asynchronous
            xmlHttp.setRequestHeader("Content-Type", "application/json");
            xmlHttp.send(JSON.stringify(data));
            xmlHttp.onreadystatechange = () => {
                if (xmlHttp.readyState === 4 && xmlHttp.status === 200) {
                    try {
                        let jsonResponse = JSON.parse(xmlHttp.response);
                        resolve(jsonResponse);
                    } catch (e) {
                        reject("Malformed JSON response.");
                    }
                } else if (xmlHttp.readyState === 4 && xmlHttp.status != 200) {
                    reject("Server error. " + xmlHttp.status + ": " + xmlHttp.statusText);
                }
            }
        });
    }

    /*
        Login
    */
    function login(email, password) {
        apiRequest({
            "function": "verify_user",
            "email": email,
            "password": password
        }).then((response) => {
            if (response["status"]) {
                document.querySelector("#loginModal > div > div > div.modal-header > button").click();

                document.querySelector("#navLoggedOut").style.display = "none";
                document.querySelector("#navLoggedIn").style.display = "block";
            }
            document.querySelector("#loginModal .alert").style.display = response["status"] ? "none" : "block";
        }).catch((reason) => {
            console.log(reason);
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

        if (password !== password2) {
            return;
        }

        apiRequest({
            "function": "create_user",
            "email": email,
            "displayname": displayname,
            "password": password
        }).then((response) => {
            if (response["status"]) {
                document.querySelector("#registerModal > div > div > div.modal-header > button").click();
                login(email, password);
            }
            document.querySelector("#registerModal .alert").style.display = response["status"] ? "none" : "block";
        }).catch((reason) => {
            console.log(reason);
        });
    }

    /*
        Logout
    */
    function logout() {
        apiRequest({
            "function": "logout_user"
        }).then((response) => {
            if (response["status"]) {
                document.querySelector("#navLoggedIn").style.display = "none";
                document.querySelector("#navLoggedOut").style.display = "block";
            }
        }).catch((reason) => {
            console.log(reason);
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

        apiRequest({
            "function": "create_comment",
            "content": content
        }).then((response) => {
            if (response["status"]) {
                document.querySelector("#commentModal > div > div > div.modal-header > button").click();
                fetchComments();
            }
            document.querySelector("#commentModal .alert").style.display = response["status"] ? "none" : "block";
        }).catch((reason) => {
            console.log(reason);
        });
    }

    /*
        Fetch comments
    */
    function fetchComments(num, page) {
        apiRequest({
            "function": "fetch_comments",
            "num": num,
            "page": page
        }).then((response) => {
            let container = document.getElementById("commentsContainer");
            container.innerHTML = "";
            let comment_html = "";

            response.forEach((comment) => {
                comment_html += getCommentCardHtml(comment);
            });

            container.innerHTML += comment_html;
        }).catch((reason) => {
            console.log(reason);
        });
    }

    /*
        Format comment cards
    */
    function getCommentCardHtml(comment) {
        return "<tweeter-comment-card id='" + comment["id"] + "' displayname='" + comment["displayname"] + "' user_id='" + comment["user_id"] + "' date='" + comment["date"] + "' content='" + comment["content"] + "' votes='" + comment["votes"] + "'></tweeter-comment-card>";
    }

    /*
        Define comment-card element
     */
    customElements.define("tweeter-comment-card",
        class extends HTMLElement {
            constructor() {
                super();
            }

            connectedCallback() {
                let template = document.getElementById("comment-card-template").content;
                this.appendChild(template.cloneNode(true));
                this.querySelector("[name='displayname']").innerHTML = this.getAttribute("displayname");
                this.querySelector("[name='user_id']").innerHTML = this.getAttribute("user_id");
                this.querySelector("[name='date']").innerHTML = this.getAttribute("date");
                this.querySelector("[name='content']").innerHTML = this.getAttribute("content");
                this.querySelector("[name='votes']").innerHTML = this.getAttribute("votes");
                let id = this.getAttribute("id");

                this.querySelector("button").onclick = () => {
                    apiRequest({"function": "create_vote", "comment_id": id})
                        .then((response) => {
                            if (response["status"]) {
                                this.querySelector("[name='votes']").innerHTML = Number(this.querySelector("[name='votes']").innerHTML) + 1;
                            }
                        });
                };
            }
        });

    /*
        OnLoad
    */
    window.onload = () => {
        setInterval(() => {
            fetchComments(10, 0);
        }, 10000);
        fetchComments(10, 0);
    }
})();