<?php
$errors = json_decode($errors, true) ?? null;
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->pageTitle() ?></title>
    <?php $this->getPlugins("header"); ?>
</head>
<body class="sidebar-mini layout-fixed">
<?php include($this->includePath("inc/navbar")) ?>
<?php include($this->includePath("inc/sidebar")) ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= $title ?></h1>
                </div>
                <?php if (isset($breadcrumbs)): ?>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Invoice</li>
                        </ol>
                    </div>
                <?php endif; ?>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">

            <?= $module->getContent(); ?>

        </div>
    </section>
</div>
<?php include($this->includePath("inc/footer")) ?>
<!-- REQUIRED SCRIPTS -->
<?php $this->getPlugins("footer"); ?>
</body>
</html>
