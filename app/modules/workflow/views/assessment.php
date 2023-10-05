<?php
$G = new \Core\Services\Groups();
$gid = (int)$_GET["grade_book"];
?>
<div class="col-lg-12">
    <div class="card">
        <div class="card-header"><?= _("Grade book") ?></div>
        <div class="card-body table-responsive p-2">
            <table class="table table-bordered table-head-fixed text-nowrap" id="studentsList">
                <thead>
                <tr>
                    <th><?= _("Student") ?></th>
                    <?php
                    foreach ($G->getGroupSubjectsList($gid) as $subject){
                        echo "<th style='writing-mode: vertical-lr;'>" . $subject['name'] . "</th>";
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
                            echo isset($student['marks'][$sid]) ? round($student['marks'][$sid],0) : "";
                            echo "</td>";
                        }
                        ?>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>