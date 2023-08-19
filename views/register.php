<?php 
$errors = json_decode($errors,true) ?? null;
include($this->includePath("inc\header")); 
?>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <a href="<?= APP_URL_F ?>"><?= $AppNameLogo ?></a>
  </div>
  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg"><?= _("Register a new membership") ?></p>
      <form action="" id="registerForm" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="last_name" placeholder="<?= _("Last name") ?>" value="<?= $_POST['last_name'] ?? "" ?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
          <?php if(isset($errors['last_name'])): ?> <span id="last_name-error" style="display:block" class="error invalid-feedback"><?= $errors['last_name']; ?></span> <?php endif; ?>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="first_name" placeholder="<?= _("First name") ?>" value="<?= $_POST['first_name'] ?? "" ?>" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-user"></span></div>
          </div>
          <?php if(isset($errors['first_name'])): ?> <span id="first_name-error" style="display:block" class="error invalid-feedback"><?= $errors['first_name']; ?></span> <?php endif; ?>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="middle_name" placeholder="<?= _("Middle name") ?>" value="<?= $_POST['middle_name'] ?? "" ?>" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-user"></span></div>
          </div>
          <?php if(isset($errors['middle_name'])): ?> <span id="middle_name-error" style="display:block" class="error invalid-feedback"><?= $errors['middle_name']; ?></span> <?php endif; ?>
        </div>
        <div class="input-group mb-3">
          <input type="email" class="form-control" name="email" placeholder="<?= _("Email") ?>" value="<?= $_POST['email'] ?? "" ?>" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
          <?php if(isset($errors['email'])): ?> <span id="email-error" style="display:block" class="error invalid-feedback"><?= $errors['email']; ?></span> <?php endif; ?>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="<?= _("Password")?>" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="icheck-primary mb-3">
              <input class="" type="checkbox" name="terms" value="agree" id="agreeTerms" aria-describedby="terms-error" required>
              <label for="agreeTerms">
              <?= _('I agree to the <a href="#">terms</a>') ?>
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block"><?= _("Register") ?></button>
            <input type="hidden" name="submit" value="register"></input>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <div class="social-auth-links text-center">
        <p>- <?= _("OR")?> -</p>
        <a href="#" class="btn btn-block btn-primary disabled">
          <i class="fab fa-facebook mr-2"></i>
          <?= _("Sign up using Facebook") ?>
        </a>
        <a href="#" class="btn btn-block btn-danger disabled">
          <i class="fab fa-google mr-2"></i>
          <?= _("Sign up using Google")?>
        </a>
      </div>

      <a href="<?= APP_URL_F ?>" class="text-center"><?= _("I already have an account")?></a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- ./wrapper -->
<!-- REQUIRED SCRIPTS -->
<?php $this->getPlugins("footer"); ?>
</body>
</html>