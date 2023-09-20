<?php
$G = new Core\Services\Groups;
$user = new \Core\Services\User();

?>
<div class="row">
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                <tr>
                    <th><?= _("Group index") ?></th>
                    <th><?= _("Group name") ?></th>
                    <th style="width: 450px;"></th>
                </tr>
                </thead>
            <?php
            foreach ($G->getGroupsList() as $group):
            ?>
                <tr>
                    <td><?= $group["index"] ?></td>
                    <td><?= $group['prof'] ?><br><small><?= _("Created:") . date(" d.m.Y",strtotime ($group['open'])) . ""; ?></small></td>
                    <td class="text-center">
                        <ul class="list-inline">
                            <?php if($group["master"]['id']!=""): ?>
                            <li class="list-inline-item">
                                <a href="<?= APP_URL_F; ?>/profile?u=<?= $group["master"]['id'] ?>"><img class="table-avatar" src="<?= $user::avatar($group["master"]['id']); ?>" alt="User Image"></a>
                                <a href="<?= APP_URL_F; ?>/profile?u=<?= $group["master"]['id'] ?>" class="users-list-name"><?= $group["master"]['name'] ?></a>
                                <span class="users-list-date"><?= _("Master") ?></span>
                            </li>
                            <?php endif; ?>
                            <?php if($group["curator"]['id']!=""): ?>
                            <li class="list-inline-item">
                                <a href="<?= APP_URL_F; ?>/profile?u=<?= $group["curator"]['id'] ?>"><img class="table-avatar" src="<?= $user::avatar($group["curator"]['id']); ?>" alt="<?= $group["curator"]['name'] ?>"></a>
                                <a href="<?= APP_URL_F; ?>/profile?u=<?= $group["curator"]['id'] ?>" class="users-list-name"><?= $group["curator"]['name'] ?></a>
                                <span class="users-list-date"><?= _("Class teacher") ?></span>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </td>
                </tr>
            <?php
            endforeach;
            ?>
            </table>
        </div>
    </div>
</div>