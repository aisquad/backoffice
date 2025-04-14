<?php require_once __DIR__ . "/Includes/head.php"; ?>
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
                    <h5 class="card-title text-center pb-0 fs-4">Crea't un Compte</h5>
                    <p class="text-center small">Inseriu la vostra informació personal per a la creació d'un compte</p>
                  </div>

                  <form id="register-form" class="row g-3 needs-validation" autocomplete="off" novalidate>
                    <div class="col-12">
                    <label for="real-name" class="form-label">
                      Nom i cognom(s)
                      <span style="display: inline-block;">
                        <div id="name-tooltip"
                           data-bs-toggle="tooltip"
                           data-bs-html="true"
                           data-bs-placement="right"
                           title="Inseriu un sol nom i un o dos cognoms"><i class="bi bi-info-circle bs-info" style="font-size: 0.6rem;"></i>
                        </div>
                      </span>
                    </label>
                      <input type="text" name="name" class="form-control" id="real-name" required>
                      <div class="invalid-feedback">Per favor, inserix el teu nom i el(s) teu(s) cognom(s) separats per espais!</div>
                    </div>

                    <div class="col-12">
                      <label for="email" class="form-label">Correu electrònic</label>
                      <input type="email" name="email" class="form-control" id="email" required>
                      <div class="invalid-feedback">Per favor, introdïx el teu correu electrònic.</div>
                    </div>

                    <div class="col-12">
                      <label for="username" class="form-label">Usuari
                      <span style="display: inline-block;">
                        <div id="username-tooltip"
                           data-bs-toggle="tooltip"
                           data-bs-html="true"
                           data-bs-placement="right"
                           title="Inseriu un nom d'usuari.
                                  - que continga entre 4 i 12 caràcters, els quals poden ser:
                                  &nbsp;&nbsp;&nbsp;- només lletres minúscules sense accents ni diacrítiques,
                                  &nbsp;&nbsp;&nbsp;- números 
                                  &nbsp;&nbsp;&nbsp;- els caràcters especials '_' (barra baixa) i '.' (punt)"><i class="bi bi-info-circle bs-info" style="font-size: 0.6rem;"></i>
                        </div>
                      </span>
                      </label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="input-group-prepend">@</span>
                        <input type="text" name="username" class="form-control" id="username" required>
                        <div class="invalid-feedback">Per favor, afegix un nom d'usuari.</div>
                      </div>
                      <div id="username-dropdown" class="dropdown-menu" style="display: none;"></div>
                    </div>                    

                    <div class="col-12">
                      <label for="passsword" class="form-label">Contrasenya</label>
                      <div class="input-group has-validation">
                        <input id="password" name="password" type="password" class="form-control" placeholder="Introduïu la vostra contrasenya" aria-label="pwd" aria-describedby="basic-addon1" required>
                        <span class="input-group-text" id="toggle-password"><i class="bi bi-eye"></i></span>
                        <div class="invalid-feedback">La contrasenya no és segura.</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" name="terms" type="checkbox" value="" id="accept-terms" required>
                        <label class="form-check-label" for="acceptTerms">He llegit i accepte <a href="#">els terminis i condicions.</a></label>
                        <div class="invalid-feedback">Ha d'acceptar les els terminis i condicions.</div>
                      </div>
                    </div>

                    <div class="col-12" id="submit-button-div">
                      <button class="btn btn-primary w-100" type="submit" id="submit-button" disabled>Crea el Compte</button>
                    </div>

                    <div class="col-12">
                      <p class="small mb-0">Ja tens un compte? <a href="/login"> Inicia sessió</a></p>
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
    import { RegisterFormValidator } from '/assets/js/validators/register.form.validator.js'

    new PasswordToggle()
    $(document).ready(() => {
      const registerForm = new RegisterFormValidator('#register-form')
      $("input:text:visible:first").focus()

      function isTouchDevice() {
        return 'ontouchstart' in window || navigator.maxTouchPoints
      }

      if (isTouchDevice()) {
        $('[data-bs-toggle="tooltip"]').tooltip('disable');
      }

      if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
          // Code pour les appareils mobiles
          new bootstrap.Tooltip($('#name-tooltip'))
          new bootstrap.Tooltip($('#username-tooltip'))
      }
    })
  </script>
</body>

</html>