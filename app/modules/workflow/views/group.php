<?php

use Core\Services\Auth\Auth;
use Core\Services\Students;
use Core\Services\Study;
use Core\Services\Timetable;
use Core\Services\User;
use Core\Services\Groups;

$U = new User();
$timetable = new Timetable();
$students = new Students();
$study = new Study();
$G = new Groups();

?>
<div class="row">
    <?php
    if (isset($_GET['lesson'])):
    // Поточний урок для користувача
        $lesson = $timetable->getLesson(Auth::userID(), $_GET['lesson']);
        if ($lesson) {
            $start = strtotime($lesson['start']);
            $end = strtotime($lesson['end']);
            ?>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header"><?= _("Grade book") ?></div>
                    <div class="card-body p-0">
                        <table class="table projects" id="<?= $lesson['id'] ?>">
                            <?php foreach ($students->getGroupStudentsList($lesson['gid'], $lesson['id']) as $student): ?>
                                <?php
                                $outOfList = $students->outOfListCheck($student["enrolled"], $student["expelled"], $lesson['start']);

                                $missing = "";

                                if (isset($student['mark']) && $student['mark'] == 0) {
                                    $student['mark'] = "Н";
                                    $markColor = "danger";
                                    $missing = "active";
                                } elseif ((int)$student['mark'] > 0) {
                                    $markColor = "green";
                                } else {
                                    $markColor = "gray";
                                }
                                ?>
                                <tr id="<?= $student['id'] ?>" class="<?php echo $outOfList ? "bg-gray" : "" ?>">
                                    <td><input type="checkbox"></td>
                                    <td>
                                        <a type="button" href="<?= APP_URL_F ?>/profile?s=<?= $student['id'] ?>"><img alt=""
                                                    class="table-avatar img-bordered border-<?= $markColor ?>"
                                                    src="<?php echo is_null($student['photo']) ? "public/storage/img/ava_" . $student['gender'] . "_user.jpg" : $student['photo'] ?>"></a>
                                    </td>
                                    <td>
                                        <?php echo $students->isBirthsday($student['bdate']) ? "<i class='fa fa-gift text-danger'></i> " : "" ?><?= $student['name'] ?>
                                        <br><small><?= $student['nick_name'] ?></small>
                                    </td>
                                    <td>
                                        <?php if (!$outOfList): ?>
                                            <div class="btn-group btn-group-sm user-mark">
                                                <button type="button" data-mark="0"
                                                        class="btn btn-block btn-outline-danger btn-sm mark missing <?= $missing ?>">
                                                    Н
                                                </button>
                                                <button type="button"
                                                        class="btn btn-default dropdown-toggle dropdown-icon"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    <span class="mark-place"><?php echo $student['mark'] ?: "&nbsp;-&nbsp;" ?></span>
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <button data-mark="-1" class="dropdown-item mark">&nbsp;-&nbsp;
                                                    </button>
                                                    <?php
                                                    for ($i = 1; $i <= 12; $i++)
                                                        printf("<button class='dropdown-item mark' data-mark='%s'>%s</button>", $i, $i);
                                                    ?>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <?= $outOfList ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                    <div class="overlay" style="display: none"><i class="fas fa-3x fa-sync-alt fa-spin"></i></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="callout callout-info">
                    <span class="text-bold"><?= $lesson["name"] ?></span>
                </div>
                <div class="callout callout-warning">
                    <h5><?= sprintf($lesson['mask'], $G->getGroupsList($lesson['open_date'])) . " " . mb_strtolower(_("Group")); ?></h5>
                    <dl>
                        <dt><?= _("Master") ?></dt>
                        <dd><?= $lesson["m"] ?> <?php if ($lesson["master"] > 0): ?><a
                                href="<?= APP_URL_F . "/profile?u=" . $lesson["master"] ?>"
                                class="btn btn-xs btn-outline-info"><i class="fa fa-user"></i>
                                </a><?php endif; ?></dd>
                        <dt><?= _("Class teacher") ?></dt>
                        <dd><?= $lesson["c"] ?> <?php if ($lesson["curator"] > 0): ?><a
                                href="<?= APP_URL_F . "/profile?u=" . $lesson["curator"] ?>"
                                class="btn btn-xs btn-outline-info"><i class="fa fa-user"></i>
                                </a><?php endif; ?></dd>
                    </dl>
                </div>
                <div class="card">
                    <div class="card-body bg-warning">
                        <dl class="row">
                            <dt class="col-sm-4"><?= _("Date") ?></dt>
                            <dd class="col-sm-8"><?= date('d.m.Y', $start) ?></dd>
                            <dt class="col-sm-7"><?= _("Start time") ?></dt>
                            <dd class="col-sm-5"><?= date('H:i', $start) ?></dd>
                            <dt class="col-sm-7"><?= _("End time") ?></dt>
                            <dd class="col-sm-5"><?= date('H:i', $end) ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
            <?php
        } else {
            echo _("There are no scheduled lessons for today");
        }

    elseif (isset($_GET["grade_book"]) && isset($_GET["sid"])):
    $gid = (int)$_GET["grade_book"];
    $sid = (int)$_GET["sid"];
    ?>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header"><?= _("Grade book") ?></div>
            <div class="card-body table-responsive p-2">
                <table class="table table-bordered table-head-fixed text-nowrap" id="studentsList">
                    <thead>
                    <tr>
                        <th><?= _("Education seeker") ?></th>
                        <th>AVG</th>
                        <?php
                        foreach ($timetable->getLessonDatesInStudyYear($gid, $sid, $study->getCurrentStudyPeriod()) as $lesson) {
                            $date = explode("-",$lesson['start']);
                            echo "<th>" . $date[1] . "<br><small>/" . $date[0] . "</small></th>";
                            $list[] = $lesson['id'];
                        }
                        ?>
                    </tr>
                    </thead>
                    <?php foreach ($G->getGroupGradeBookBySubject($gid, $sid, $study->getCurrentStudyPeriod()) as $student): ?>
                        <tr>
                            <td>
                                <?= $student['student_name'] ?>
                            </td>
                            <td><?php echo round(array_sum($student['lessons'])/count($student['lessons']),1) ?></td>
                            <?php
                                foreach ($list as $lesson){
                                    echo "<td>";
                                    echo isset($student['lessons'][$lesson]) ? $student['lessons'][$lesson]>0 ? $student['lessons'][$lesson] :"H" : "";
                                    echo "</td>";
                                }
                            ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
        <?php
        endif;

        ?>
    </div>