<?php 
$errors = json_decode($errors,true) ?? null;
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link rel="icon" type="image/x-icon" href="<?= APP_ASSETS_FOLDER . "/images/favicon.ico"?>">
    <?php $this->getPlugins("header"); ?>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="<?= APP_URL_F ?>"><b>Teacher</b>Assistant</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg"><?= _("Sign in to start your session"); ?></p>
      <form action="<?= APP_URL_F ?>/auth/login" id="loginForm" method="post">
      <div class="input-group mb-3">
        <input type="email" name="email" class="form-control" placeholder="<?= _("Email") ?>" required>
        <div class="input-group-append">
          <div class="input-group-text">
            <span class="fas fa-envelope"></span>
          </div>
        </div>
      </div>
      <div class="input-group mb-3">
        <input type="password" name="password" class="form-control" placeholder="<?= _("Password"); ?>" required>
        <div class="input-group-append">
          <div class="input-group-text">
           <span class="fas fa-lock"></span>
          </div>
        </div>
      </div>
      <?php if(!is_null($errors)): ?>
        <div class="text-danger"><?= $errors ?></div>
      <?php endif; ?>
      <div class="row">
        <div class="col-8">
          <div class="icheck-primary">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">
            <?= _("Remember Me") ?>
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-4">
          <button type="submit" class="btn btn-primary btn-block"><?= _("Sign In") ?></button>
          <input type="hidden" name="submit" value="signin"></input>
        </div>
        <!-- /.col -->
      </div>
      </form>

      <div class="social-auth-links text-center mb-3">
        <p>- <?= _("OR") ?> -</p>
        <a href="#" class="btn btn-block btn-primary disabled">
          <i class="fab fa-facebook mr-2"></i> <?= _("Sign in using Facebook") ?>
        </a>
        <a href="#" class="btn btn-block btn-danger disabled">
          <i class="fab fa-google mr-2"></i> <?= _("Sign in using Google") ?>
        </a>
      </div>
      <!-- /.social-auth-links -->

      <p class="mb-1">
        <a href="<?= APP_URL_F ?>/new_password"><?= _("I forgot my password") ?></a>
      </p>
      <p class="mb-0">
        <a href="<?= APP_URL_F ?>/auth/register" class="text-center"><?= _("Register a new membership") ?></a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->
<!-- ./wrapper -->
<!-- REQUIRED SCRIPTS -->
<?php $this->getPlugins("footer"); ?>
</body>
</html>