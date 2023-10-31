<?php

use Core\Services\Students;
use App\Helper\Calculator;

$student = new Students();
$calc = new Calculator();
$studentId = $_GET["id"] ?? null;
$studentData = $student->getStudentInfo($studentId);
$AS = $calc->calculateAcademicSuccess($studentData[0]['lessonsCount'], $studentData[0]['average'], $studentData[0]['NA']);
if (count($studentData) > 0):
    echo "<pre>";
    print_r($studentData);
    echo "</pre>";
    ?>
    <div class="card card-widget widget-user-2 shadow">
        <!-- Add the bg color to the header using any of the bg-* classes -->
        <div class="widget-user-header bg-warning">
            <div class="widget-user-image">
                <img class="img-circle elevation-2"
                     src="<?php echo is_null($studentData[0]['photo']) ? "public/storage/img/ava_" . $studentData[0]['gender'] . "_user.jpg" : $studentData[0]['photo'] ?>"
                     alt="User Avatar">
            </div>
            <!-- /.widget-user-image -->
            <h3 class="widget-user-username"><?= $studentData[0]['last_name'] ?></h3>
            <h5 class="widget-user-desc"><?= join(" ", [$studentData[0]['first_name'], $studentData[0]['middle_name']]) ?></h5>
        </div>
        <div class="card-footer">
            <?= _("Academic success") ?><i style="padding-left: 5px;cursor: pointer" data-toggle="modal"
                                           data-target="#modal-default" class="fa fa-question-circle text-info"></i>
            <div class="progress mb-3">
                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="40" aria-valuemin="0"
                     aria-valuemax="100" style="width: <?= $AS ?>%">
                    <span class="sr-only"><?= $AS ?>%</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4 col-xl-2">
            <div class="info-box bg-info">
                <span class="info-box-icon"><i class="far fa-bookmark"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">GPA</span>
                    <span class="info-box-number">4,4</span>

                    <div class="progress">
                        <div class="progress-bar" style="width: 70%"></div>
                    </div>
                    <span class="progress-description">
                  70%
                </span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-sm-4 col-xl-2">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="far fa-bookmark"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text"><?= _("Average rating") ?></span>
                    <span class="info-box-number"><?= round($studentData[0]['average'],1) ?></span>
                    <?php $avg = round($studentData[0]['average']/12*100,0) ?>
                    <div class="progress">
                        <div class="progress-bar" style="width: <?= $avg ?>%"></div>
                    </div>
                    <span class="progress-description"><?= $avg ?>%
                </span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">

        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <?= _("Students") ?>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <?= _("No data!"); ?>
<?php endif; ?>
<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?= _("Academic success calculation") ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p></p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= _("Close") ?></button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
