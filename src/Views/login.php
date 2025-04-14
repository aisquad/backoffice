<?php 
require_once __DIR__ . "/Includes/head.php"; 
?>
<body>

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <img src="/assets/img/logo.png" alt="">
                  <span class="d-none d-lg-block">Backoffice</span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                    <p class="text-center small">Enter your username & password to login</p>
                  </div>

                  <form id="login-form" class="row g-3 needs-validation" method="POST" novalidate>

                    <div class="col-12">
                      <label for="username" class="form-label">Username</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="text" name="username" class="form-control" id="username" required>
                        <div class="invalid-feedback">Please enter your username.</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="password" class="form-label">Contrasenya</label>
                      <div class="input-group has-validation">
                        <input id="password" 
                               name="password" 
                               type="password" 
                               class="form-control" 
                               placeholder="Introduïu la vostra contrasenya" 
                               aria-label="pwd" 
                               aria-describedby="basic-addon1" 
                               required>
                        <span class="input-group-text" id="toggle-password"><i class="bi bi-eye"></i></span>
                        <div class="invalid-feedback">La contrasenya no és segura.</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                      </div>
                    </div>
                    <div class="col-12">
                      <button id="login" class="btn btn-primary w-100" type="submit">Login</button>
                    </div>
                    <div class="col-12">
                      <p class="small mb-0">Don't have account? <a href="/register">Create an account</a></p>
                    </div>
                  </form>

                </div>
              </div>

              <div class="credits">
                Designed by Copyl&#x259;ft
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

  <?php require_once __DIR__ . "/Includes/footer-min.php"; ?>
  <script type="module">
    import { PasswordToggle } from "/assets/js/buttons/password.toggle.js"
    import { StorageManager } from "/assets/js/storage/storage.manager.js"
    import { authService } from "/assets/js/api/auth.service.js"

    new PasswordToggle();
    $(document).ready( () => {
      $('#login-form').on('submit', async (evt) => {
        evt.preventDefault()

        const $form = $(evt.target)
        const formData = {
          username: $form.find('#username').val().trim(),
          password: $form.find('#password').val(),
        }

        try {
          const response = await authService.login(formData)
          if (response.token) {
            window.location.href = '/'
          }
        } catch (error) {
          console.error('Login error:', error)
          console.error('Login failed:', error.message)
        }

      })
    })
  </script>

</body>

</html>