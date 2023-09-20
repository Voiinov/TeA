<?php
$db = \Core\Database::getInstance();
$groups = new \Core\Services\Groups();
$sbj = new \Core\Services\Subjects();
$user = new \Core\Services\User();
$subonly = isset($_GET['subonly']);

$subject = $sbj->getSubjectInfo($_GET['sid'], $subonly);
$cover = is_null($subject[0]['cover']) ? "public/assets/images/subjects/education-pic_orig.jpg" : $subject[0]['cover'];
?>
<div class="row">
    <div class="col-md-12">
        <!-- Widget: user widget style 1 -->
        <div class="card card-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header text-white" style="background: url('<?= $cover ?>') center center;">
                <h3 class="widget-user-username text-right"><?= $subject[0]['name'] ?></h3>
                <h5 class="widget-user-desc text-right"><?= $subject[0]['shortname'] ?></h5>
            </div>
        </div>
        <!-- /.widget-user -->
    </div>
</div>
<div class="row">
<?php foreach ($sbj->subjectUsersList($_GET['sid']) as $educator): ?>
    <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
        <div class="card bg-light d-flex flex-fill">
            <div class="card-header text-muted border-bottom-0">
                <?= $educator['post'] ?>
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-7">
                        <h2 class="lead"><b><?= $educator['username'] ?></b></h2>
                        <ul class="ml-4 mb-0 fa-ul text-muted">
                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-at"></i></span> <?= $educator['email'] ?></li>
                        </ul>
                    </div>
                    <div class="col-5 text-center">
                        <img src="<?= $user::avatar($educator['id']) ?>" alt="user-avatar" class="img-circle img-fluid">
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="text-right">
                    <a href="#" class="btn btn-sm bg-teal disabled" style="pointer-events: auto">
                        <i class="fas fa-comments"></i>
                    </a>
                    <a href="<?= APP_URL_F . "/profile=" . $educator['id'] ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-user"></i> <?= _("View Profile") ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>
<?php if (\Core\Services\Auth\Permission::roleAccess("subjectEdit")): ?>
<?php endif; ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
    </div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <?= _("Groups in which the subject is taught") ?>
            </div>
            <div class="card-body">
                <?php
                $list = $groups->getGroupsShortListBySubject($_GET["sid"]);
                ?>
                <table class="table table-striped projects table-responsive">
                    <thead>
                    <tr>
                        <td style="width: 1%"><?= _("Group") ?></td>
                        <td><?= _("Group name") ?></td>
                        <td></td>
                        <td></td>
                    </thead>
                    <?php
                    if(count($list)>0):
                    foreach ($list as $gid => $group):
                    ?>
                        <tr>
                            <td><?= $group['index'] ?></td>
                            <td>
                                <?= implode("; ",$group['code']); ?><br>
                                <small><?= implode(";", array_keys($group['code'])); ?></small>
                            </td>
                            <td class="project_progress">
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-green" role="progressbar" aria-valuenow="<?= $group['hours']['prc'] ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $group['hours']['prc'] ?>%">
                                    </div>
                                </div>
                                <small>
                                    <?= round($group['hours']['prc'],0) ?>% <?= _("Complete") ?>
                                </small>
                            </td>
                            <td><a class="btn btn-info btn-sm" href="<?= APP_URL_F . "/workflow?p=groups&gid={$gid}"; ?>"><?= _("Info") ?></a></td>
                        </tr>
                    <?php
                    endforeach;
                    endif;
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>