<?php

use App\Helper\Calculator;
use App\Helper\Designer;
use Core\Services\Auth\Auth;
use Core\Services\Auth\Permission;
use Core\Services\User;

$U = new User();
$calc = new Calculator();
$permission = new Permission();
$designer = new Designer();

$uid = isset($_GET['u']) && $_GET['u'] > 0 ? (int)$_GET['u'] : Auth::userID();
$user = $U->getUserById($uid);
$contacts = json_decode($user['contacts'],true);
$changes = json_decode($user['changes'],true);
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <?php if ($user['user_status_level'] < 9):
                        $o = $user['user_status_options'] != "" ? json_decode($user['user_status_options'], true) : ["bg" => "primary"]; ?>
                        <div class="ribbon-wrapper ribbon-lg">
                            <div class="ribbon bg-<?= $o['bg'] ?> text-lg">
                                <?= $user['user_status']; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" src="<?= $U::avatar($uid) ?>"
                             alt="User profile picture">
                    </div>

                    <h3 class="profile-username text-center"><?= JOIN(" ", [$user['last_name'], $user['first_name'], $user['middle_name']]) ?></h3>
                    <?php if(isset($changes['last_name'])){
                        echo "<div class='text-center text-muted'>(" . implode(",",$changes['last_name']) . ")</div>";
                    }  ?>
                    <p class="text-muted text-center"><?php echo $user['postFullName'] != "" ? $user['postFullName'] : $user['postShortName'] ?></p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b><?= _("Teaching experience") ?></b> <a class="float-right"><?php echo is_null($user['exp']) ? "" : $calc->getDateDiff($user['exp'],null,_("%yy. %mm."))?></a>
                        </li>
                    </ul>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><?= _("About Me") ?></h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <strong><i class="fas fa-book mr-1"></i> <?= _("Education") ?></strong>
                    <p class="text-muted">
                    </p>
                    <hr>
                    <strong><i class="fa fa-share-square"></i> <?= _("Social networks") ?></strong>
                    <p class="text-muted">
                    <?php
                        if(isset($contacts['social'])){
                            foreach ($contacts['social'] as $soc=>$link)
                                echo $designer->socialLink($soc,$link,true) . " | ";
                        }
                    ?>
                    </p>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#attestation" data-toggle="tab"><?= _("Attestation") ?></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab"><?= _("Timeline") ?></a></li>
                        <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab"><?= _("Settings") ?></a></li>
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="attestation">
                            <?php
                            if($permission::actionAcess("getUserCertificates") || $uid==Auth::userID()):
                            ?>
                                <button class="btn tbn-xs btn-default" id="getUserCertificates">import</button>
                            <?php endif; ?>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="timeline">
                            timeline
                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="settings">
                            settings
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div><!-- /.card-body -->
                <div class="overlay" style="display: none"><i class="fas fa-3x fa-sync-alt fa-spin"></i></div>
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div>