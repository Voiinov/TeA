<?php
$subjects = new \Core\Services\Subjects();
$subjectsList = $subjects->getList();
?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= _("Subjects List") ?></h3>
        <?php if(\Core\Services\Auth\Permission::access("add_new_user")): ?>
        <div class="card-tools">
            <a href="?p=users&newuser" class="btn btn-tool"><i class="fas fa-plus"></i></a>
        </div>
        <?php endif; ?>
    </div>
    <div class="card-body">
    <table class='table table-hover'>
        <thead>
        <tr>
            <th>№</th>
            <th><?= _("Subject name") ?></th>
            <th><?= _("Short title/abbreviation") ?></th>
            <th></th>
        </tr>
        </thead>
        <?php if (count($subjectsList) > 0) {
            $counter = 1;
            foreach ($subjectsList as $sid => $sdata): ?>
                <tr>
                    <td><?= $counter++ ?></td>
                    <td>
                        <?= $sdata['name'] ?>
                        <?php
                        if (isset($sdata['sub'])) {
                            echo "<br>";
                            foreach ($sdata['sub'] as $subid => $subdata) {
                                ?>
                                <a href="<?= $_SERVER['REQUEST_URI'] . "&sid=" . $subid; ?>"
                                   class="badge badge-primary"><i
                                            class="fas fa-bookmark"></i> <?= $subdata['name'] ?> </a>
                                <?php
                            }
                        }
                        ?>
                    </td>
                    <td>
                        <?= $sdata['shortname'] ?>
                    </td>
                    <td><a href="<?= $_SERVER['REQUEST_URI'] . "&sid=" . $sid; ?>" class="btn btn-flat btn-info btn-sm"><i
                                    class="fas fa-info-circle"></i> <?= _("Details") ?> </a></td>
                </tr>
            <?php endforeach; ?>
            <?php
        } else {
            echo "<tr><td colspan='4'>list are empty. Create new subject</td></tr>";
        }
        ?>
    </table>
    </div>
</div>
