<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registe New Account</title>
    <link rel="stylesheet" href="src/css/main.css">
    <link rel="stylesheet" href="src/css/register/main.css">

</head>

<body>
    <div class="wrapper">
        <div class="nav">
            <img src="assets/svgs/logoWide.svg" class="logo" alt="">
        </div>
        <form enctype="multipart/form-data" action="/postregister" method="post" onsubmit="return validateForm()">
            <div class="formTitle break">Register Your New Account</div>

            <input type="text" name="firstname" id="firstname" class="input" placeholder="First Name" required />
            <input type="text" name="lastname" id="lastname" class="input" placeholder="Last Name" required />
            <input onkeyup="validateUsername(this)" type="text" name="username" id="username" class="input" placeholder="Username" required />

            <div class="takespace"></div>
            <input type="email" name="email" id="email" class="input" placeholder="email" required />
            <input type="number" name="phone" id="phone" class="input" placeholder="phone" required />
            <div class="takespace"></div>

            <input type="text" name="address" id="address" class="input takespace" placeholder="Address" required />

            <input type="file" id="profilePicInput" name="profilePic" accept="image/*" hidden>
            <div class="profilePic">
                <img id="previewImage" src="#" alt="Preview" style="display: none; max-width: 100px; max-height: 100px;">
                <button class="btn" onclick="document.getElementById('profilePicInput').click(); return false;">Select Profile Picture</button>
            </div>

            <div class="takespace"></div>
            <input type="password" name="password" id="password" class="input pass" placeholder="Password" required />
            <div class="break">
                <div id="vlength">❌ At Least 8 character</div><br>
                <div id="vsymbol">❌ At Least 1 symbol</div><br>
                <div id="vnumber">❌ At Least 1 number</div><br>
                <div id="vcapital">❌ At Least 1 capital letter</div>
            </div>
            <input type="password" id="confirmPassword" class="input" placeholder="Confirm Password" required />
            <button class="btn-primary takespace" type="submit">Register</button>
        </form>
    </div>
    <script src="/src/js/main.js"></script>
    <script>
        function validateUsername(e) {
            sendRequest("POST", "api/validateUsername?username=" + e.value).then((res) => {
                if (res == "matched") {
                    document.getElementById("username").classList.add("invalid");
                } else {
                    document.getElementById("username").classList.remove("invalid");
                }
            }).catch(err => console.log(err));
        }
        document.getElementById("password").addEventListener("input", function() {
            var password = document.getElementById("password").value;
            var vLength = document.getElementById("vlength");
            var vSymbol = document.getElementById("vsymbol");
            var vNumber = document.getElementById("vnumber");
            var vCapital = document.getElementById("vcapital");

            // Regular expressions to check for symbol, number, and capital letter
            var symbolRegex = /[$&+,:;=?@#|'<>.^*()%!-]/;
            var numberRegex = /[0-9]/;
            var capitalRegex = /[A-Z]/;

            var hasSymbol = symbolRegex.test(password);
            var hasNumber = numberRegex.test(password);
            var hasCapital = capitalRegex.test(password);

            // Update labels based on password strength
            vLength.innerText = password.length >= 8 ? "🟢 At Least 8 characters" : "❌ At Least 8 characters";
            vSymbol.innerText = hasSymbol ? "🟢 At Least 1 symbol" : "❌ At Least 1 symbol";
            vNumber.innerText = hasNumber ? "🟢 At Least 1 number" : "❌ At Least 1 number";
            vCapital.innerText = hasCapital ? "🟢 At Least 1 capital letter" : "❌ At Least 1 capital letter";
        });

        function validateForm() {
            var password = document.getElementById("password").value;
            var phone = document.getElementById("phone").value;

            var confirmPassword = document.getElementById("confirmPassword").value;

            if (password != confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }
            if (phone.length < 8) {
                alert("Please provide a valid Phone Number!");
                return false;
            }
            return true;
        }

        // Function to handle file input change
        document.getElementById('profilePicInput').addEventListener('change', function(event) {
            var file = event.target.files[0];
            var reader = new FileReader();

            reader.onload = function(e) {
                var previewImage = document.getElementById('previewImage');
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
            };

            reader.readAsDataURL(file);
        });
    </script>
</body>

</html>