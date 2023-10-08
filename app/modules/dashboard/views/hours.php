<?php

use Core\Services\Timetable;
use App\Helper\Calculator;
use App\Helper\Writer;

$timetable = new Timetable();
$calculator = new Calculator();
$writer = new Writer();

$UID = \Core\Services\Auth\Auth::userID();
$calculator->settings($_GET);
$daysCount = $calculator->getDaysCountInMonth();
$tb = $timetable->getTimetable($UID, $calculator->getDate("Y-m-01"), $calculator->getDate("Y-m-" . $daysCount));
$title = sprintf(_("Timesheet for %s"), mb_strtolower($writer->monthName($calculator->getDate("n")))) . " " . $calculator->getDate("Y");
?>
<div class="row no-print">
    <div class="col-12">
        <?php foreach ($timetable->getUserPeriods($UID) as $year => $months): ?>
            <div class="btn-group">
                <button type="button" class="btn btn-success btn-flat dropdown-toggle dropdown-icon"
                        data-toggle="dropdown" aria-expanded="false">
                    <span><?= $year ?></span>
                </button>
                <div class="dropdown-menu" role="menu" style="">
                <?php for($m=1;$m<=12;$m++):?>
                    <a class="dropdown-item<?php echo isset($months[$m]) ? "\"" . " href=\"" .  APP_URL_F . "/dashboard?p=hours&date={$year}-". str_pad($m,2,"0",0) . "-01\""  : " disabled\"" ?>><?= $writer->monthName($m) ?></a>
                <?php endfor; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header"><?= $title ?>
                <div class="card-tools">
                    <button class="btn btn-tool" onclick="window.print()"><i class="fas fa-print"></i> <?= _("Print") ?>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-responsive table-head-fixed text-center">
                    <thead>
                    <tr>
                        <th>№</th>
                        <?php
                        for ($d = 1; $d <= $daysCount; $d++) {
                            echo "<th>" . str_pad($d, 2, "0", 0) . "</th>";
                        } ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php for ($l = 1; $l <= 10; $l++):
                        echo "<tr><td>$l</td>";
                        for ($d = 1; $d <= $daysCount; $d++): ?>
                            <td><?php
                                if (isset($tb[$l][$d][0])) {
                                    $printArr[$l][$d] = "";
                                    foreach ($tb[$l][$d] as $item) {
                                        $abbr = $writer->abbreviation($item['title']);
                                        $text = "<div>" . $item['group'] . "<br><small class='text-muted'>{$abbr}</small></div>";
                                        echo $text;
                                        $printArr[$l][$d] .= $text;
                                        $total["subjects"][$item['sid']] = [$abbr, $item['title']];
                                        $total["groups"][$item['group']][$item['sid']][] = 1;
                                        $total["days"][$d][] = 1;
                                        $total["sum"][] = 1;
                                    }
                                } else {
                                    $printArr[$l][$d] = null;
                                } ?></td>
                        <?php
                        endfor;
                        echo "</tr>";
                    endfor; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="print-only" style="line-height: 0.8;">
    <h6 class="text-center"><?= $title ?></h6>
    <div class="row text-center">
        <table class="table-print" style="width: 100%;border:1px solid black">
            <thead>
            <tr>
                <th rowspan="3">№</th>
                <th colspan="<?= $daysCount ?>"><?= _("Day") ?></th>
            </tr>
            <tr>
                <?php
                for ($d = 1; $d <= $daysCount; $d++) {
                    echo "<th>" . str_pad($d, 2, "0", 0) . "<br>";
                    echo "<small>" . $writer->dayName(date("w", mktime(0, 0, 0, $calculator->getDate("m"), $d, $calculator->getDate("Y"))), 0) . "</small></th>";
                } ?>
            </tr>
            </thead>
            <tbody>
            <?php for ($l = 1; $l <= 10; $l++):
                echo "<tr><td>$l</td>";
                for ($d = 1; $d <= $daysCount; $d++): ?>
                    <td><?= $printArr[$l][$d] ?></td>
                <?php
                endfor;
                echo "</tr>";
            endfor; ?>
            </tbody>
            <tfoot>
            <tr>
                <td></td>
                <?php
                for ($d = 1; $d <= $daysCount; $d++) {
                    echo "<th>";
                    echo isset($total["days"][$d]) ? count($total["days"][$d]) : 0;
                    echo "</th>";
                }
                ?>
            </tr>
            </tfoot>
        </table>
    </div>
    <?php
    ksort($total["subjects"]);
    ksort($total["groups"]);
    ?>
    <div class="row" style="margin-top: 20px">
        <div class="col-8">
            <table class="table-print text-center" style="width: 100%;border:1px solid black">
                <thead>
                <tr>
                    <th colspan="2"><?= _("Subject") ?></th>
                    <th><?= implode("</th><th>", array_keys($total["groups"])) ?></th>
                </tr>
                </thead>
                <?php
                foreach ($total["subjects"] as $sid => $subject): ?>
                    <tr>
                        <td class="text-left"><?= $subject[0] ?></td>
                        <td class="text-left"><?= $subject[1] ?></td>
                        <?php
                        foreach ($total["groups"] as $sub) {
                            echo "<td>";
                            echo isset($sub[$sid]) ? count($sub[$sid]) : "";
                            echo "</td>";
                        }
                        ?>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="col-4">
            <p><strong><?= sprintf(_('Total hours per month: %d'), array_sum($total["sum"])); ?></strong></p>
            <p><?= sprintf(_('Working days per month: %d'), count($total["days"])); ?></p>
            <p><?= sprintf(_('Data as of: %s'), date('d.m.Y H:i')); ?></p>
        </div>
    </div>
    <div class="row" style="padding: 15px 0px">
        <div class="col-6 text-right">
            ______________<br>
            <span style="padding-right: 20px"><small>(<?= _("signature") ?>)</small></span>
        </div>
        <div class="col-6">
            <?= \Core\Services\Auth\Auth::userName(); ?>
        </div>
    </div>
</div>