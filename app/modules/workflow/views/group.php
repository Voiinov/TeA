<?php
$U = new \Core\Services\User();
$timetable = new \Core\Services\Timetable();
$students = new \Core\Services\Students();
$G = new \Core\Services\Groups();
?>
<div class="row">
    <?php
    if (isset($_GET['lesson'])):
        // Поточний урок для користувача
        $lesson = $timetable->getLesson(\Core\Services\Auth\Auth::userID(), $_GET['lesson']);
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
                                $outOfList = $students->outOfListCheck($student["enrolled"],$student["expelled"],$lesson['start']);

                                $missing = "";

                                if ($student['mark'] == -1) {
                                    $student['mark'] = "Н";
                                    $markColor = "danger";
                                    $missing = "active";
                                } elseif ((int)$student['mark'] > 0) {
                                    $markColor = "green";
                                } else {
                                    $markColor = "gray";
                                }
                                ?>
                                <tr id="<?= $student['id'] ?>" class="<?php echo $outOfList ? "bg-gray" :"" ?>">
                                    <td><input type="checkbox"></td>
                                    <td>
                                        <a type="button" href="<?= APP_URL_F ?>/profile?s=<?= $student['id'] ?>"><img
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
                                                <button type="button" data-mark="-1" class="btn btn-block btn-outline-danger btn-sm mark missing <?= $missing ?>">Н</button>
                                                <button type="button"
                                                        class="btn btn-default dropdown-toggle dropdown-icon"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    <span class="mark-place"><?php echo $student['mark'] ?: "&nbsp;-&nbsp;" ?></span>
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <button data-mark="null" class="dropdown-item mark">&nbsp;-&nbsp;</button>
                                                    <?php
                                                    for ($i = 1; $i <= 12; $i++)
                                                        echo "<button class='dropdown-item mark' data-mark=\"{$i}\">{$i}</button>";
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

    elseif(isset($_GET["grade_book"])):
        $gid = (int)$_GET["grade_book"];
        ?>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header"><?= _("Grade book") ?></div>
            <div class="card-body table-responsive p-0" style="height: 300px">
                <table class="table table-head-fixed text-nowrap">
            <thead>
            <tr>
                <th><?= _("Student") ?></th>
                <?php
                foreach ($G->getGroupSubjectsList($gid) as $subject){
                    echo "<th>" . $subject['name'] . "</th>";
                    $subjects[$subject['sid']]=$subject['sid'];
                }
                ?>
            </tr>
            </thead>
            <?php foreach ($G->getGroupGradeBook($_GET['grade_book']) as $student): ?>
                <tr>
                    <td><?= $student['name'] ?></td>
                    <?php
                        foreach ($subjects as $sid){
                            echo "<td>";
                            echo $student['marks'][$sid] ?? "";
                            echo "</td>";
                        }
                    ?>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    </div>
    </div>
    <?php
    endif;

    ?>
</div>