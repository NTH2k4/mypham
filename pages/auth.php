<?php

include '../partials/header.php';
include '../includes/func.php';
if(isLoggedIn() == true){
    header('Location: ' . BASE_URL . 'index.php');
}

?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/auth.css">
<div class="auth-wrapper" style="background-color: #F2F6FA; padding: 50px 0;">
    <div class="container">
        <div class="form-card shadow-lg rounded" style="background-color: white; padding: 30px; max-width: 900px; margin: auto;">
            <div class="text-center mb-4">
                <h1 style="color: rgb(48,120,156); font-weight: 700;">Welcome to Products Paradise!</h1>
                <p style="color: #6c757d;">Soft, adorable and shiny, perfect cosmetics are waiting for you!</p>
            </div>
            <div class="toggle-buttons text-center mb-5">
                <button id="loginBtn" class="btn me-2" style="background-color: rgb(48,120,156); color: white; padding: 10px 30px; border-radius: 30px;">Login</button>
                <button id="registerBtn" class="btn ms-2" style="background-color: #f8f9fa; color: rgb(48,120,156); padding: 10px 30px; border-radius: 30px;">Register</button>
            </div>

            
            <form id="loginForm" class="form active" action="<?php echo BASE_URL; ?>controllers/auth.php" method="POST" style="display: block;">
                <h3 class="text-center mb-4" style="color: rgb(48,120,156);">Login to Your Account</h3>
                <div class="mb-3">
                    <input type="text" name="emailOrPhone" class="form-control form-control-lg" placeholder="Email, Username, or Phone Number" required style="border-radius: 20px;">
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required style="border-radius: 20px;">
                </div>
                <input type="hidden" name="action" value="login">
                <button type="submit" class="btn w-100" style="background-color: rgb(48,120,156); color: white; padding: 12px; border-radius: 30px;">Login</button>
            </form>

            
            <form id="registerForm" class="form" action="<?php echo BASE_URL; ?>controllers/register.php" method="POST" style="display: none;">
                <h3 class="text-center mb-4" style="color: rgb(48,120,156);">Create Your Account</h3>
                <div class="mb-3">
                    <input type="text" name="username" class="form-control form-control-lg" placeholder="Username" required style="border-radius: 20px;">
                </div>
                <div class="mb-3">
                    <input type="text" name="fullname" class="form-control form-control-lg" placeholder="Full Name" required style="border-radius: 20px;">
                </div>
                <div class="mb-3">
                    <input type="email" name="email" class="form-control form-control-lg" placeholder="Email" required style="border-radius: 20px;">
                </div>
                <div class="mb-3">
                    <input type="tel" name="phone" class="form-control form-control-lg" placeholder="Phone Number" required style="border-radius: 20px;">
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required style="border-radius: 20px;">
                </div>
                <button type="submit" class="btn w-100" style="background-color: rgb(48,120,156); color: white; padding: 12px; border-radius: 30px;">Register</button>
            </form>
        </div>
    </div>
</div>

<?php

include '../partials/footer.php';

?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/auth.js"></script>
<script>
    // Ensure the script doesn't redeclare variables
    window.onload = function() {
        const loginBtn = document.getElementById('loginBtn');
        const registerBtn = document.getElementById('registerBtn');
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');

        // Initial setup: Show login form, hide register form
        loginForm.style.display = 'block';
        registerForm.style.display = 'none';
        loginBtn.style.backgroundColor = 'rgb(48,120,156)';
        loginBtn.style.color = 'white';
        registerBtn.style.backgroundColor = '#f8f9fa';
        registerBtn.style.color = 'rgb(48,120,156)';

        // Login button click event
        loginBtn.addEventListener('click', () => {
            loginForm.style.display = 'block';
            registerForm.style.display = 'none';
            loginBtn.style.backgroundColor = 'rgb(48,120,156)';
            loginBtn.style.color = 'white';
            registerBtn.style.backgroundColor = '#f8f9fa';
            registerBtn.style.color = 'rgb(48,120,156)';
        });

        // Register button click event
        registerBtn.addEventListener('click', () => {
            loginForm.style.display = 'none';
            registerForm.style.display = 'block';
            registerBtn.style.backgroundColor = 'rgb(48,120,156)';
            registerBtn.style.color = 'white';
            loginBtn.style.backgroundColor = '#f8f9fa';
            loginBtn.style.color = 'rgb(48,120,156)';
        });
    };
</script>


</body>

</html>
