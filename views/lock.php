<?php $errors = json_decode($this->errors, true) ?? null; ?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link rel="icon" type="image/x-icon" href="<?= APP_ASSETS_FOLDER . "/images/favicon.ico"?>">
    <?php $this->getPlugins("header"); ?>
</head>
<body class="hold-transition lockscreen">
<!-- Automatic element centering -->
<div class="lockscreen-wrapper">
    <div class="lockscreen-logo">
        <a href="<?= APP_URL_F ?>"><?= $AppNameLogo ?></a>
    </div>
    <?php if (isset($userdata) && $userdata !== false): ?>
        <!-- User name -->
        <div class="lockscreen-name"><?= $userdata[0]['last_name'] . " " . $userdata[0]['first_name']; ?></div>
        <!-- START LOCK SCREEN ITEM -->
        <div class="lockscreen-item">
            <!-- lockscreen image -->
            <div class="lockscreen-image">
                <img src="<?= \Core\Services\User::avatar($userdata[0]['id']) ?>" alt="User Image">
            </div>
            <!-- /.lockscreen-image -->

            <!-- lockscreen credentials (contains the form) -->
            <form class="lockscreen-credentials" action="<?= APP_URL_F ?>/auth/login" method="post">
                <div class="input-group">

                    <input type="password" name="password" class="form-control"
                           placeholder="<?= mb_strtolower(_("Password")) ?>" required>
                    <input type="hidden" name="email" value="<?= $userdata[0]['email'] ?>" required>
                    <input type="hidden" name="submit" value="signin"></input>
                    <div class="input-group-append">
                        <button type="submit" class="btn">
                            <i class="fas fa-arrow-right text-muted"></i>
                        </button>
                    </div>
                </div>
            </form>
            <!-- /.lockscreen credentials -->

        </div>
        <!-- /.lockscreen-item -->
        <div class="help-block text-center">
            Enter your password to retrieve your session
        </div>
    <?php endif; ?>
    <?php if (isset($sent_new_password_form)): ?>
        <form class="" action="<?= APP_URL_F ?>/new_password_sent" method="post">
            <div class="input-group">
                <input type="email" name="email" class="form-control" placeholder="<?= mb_strtolower(_("Email")) ?>"
                       required>
                <div class="input-group-append">
                    <button type="submit" name="reset_password" class="btn">
                        <i class="fas fa-arrow-right text-muted"></i>
                    </button>
                </div>
            </div>
        </form>
        <div class="help-block text-center">
            <?= _("Enter your email to reset password") ?>
        </div>
    <?php endif; ?>
    <?php if(isset($sent_new_password_was_form)): ?>
    <div class="help-block text-center">
        <?= _("Email was sent") ?>
    </div>
    <?php endif; ?>
    <div class="text-center">
        <a href="<?= APP_URL_F ?>"><?= _("Or sign in as a different user") ?></a>
    </div>
    <div class="lockscreen-footer text-center">
        <b><a href="<?= APP_URL_F ?>" class="text-black"><?= _("Login form") ?></a></b><br>
    </div>
</div>
<!-- /.center -->
<!-- REQUIRED SCRIPTS -->
<?php $this->getPlugins("footer"); ?>
</body>
</html>