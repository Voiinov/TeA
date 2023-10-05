<?php

use Core\Services\Timetable;

$timetable = new Timetable();
$UID = \Core\Services\Auth\Auth::userID();
$group = 0;
?>
<div class="row">
    <div class="col-md-8 col-12">
        <div class="card">
            <div class="card-header ui-sortable-handle">
                <h3 class="card-title">
                    <i class="ion ion-clipboard mr-1"></i>
                    <?= _("To Do List") ?>
                </h3>

            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <ul class="todo-list ui-sortable" data-widget="todo-list">
                    <li>
                    <span class="handle ui-sortable-handle">
                      <i class="fas fa-ellipsis-v"></i>
                      <i class="fas fa-ellipsis-v"></i>
                    </span>
                        <div class="icheck-primary d-inline ml-2">
                            <input type="checkbox" value="" name="todo6" id="todoCheck6">
                            <label for="todoCheck6"></label>
                        </div>
                        <span class="text">Let theme shine like a star</span>
                        <small class="badge badge-secondary"><i class="far fa-clock"></i> 1 month</small>
                        <div class="tools">
                            <i class="fas fa-edit"></i>
                            <i class="fas fa-trash-o"></i>
                        </div>
                    </li>
                </ul>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
                <button type="button" class="btn btn-primary float-right"><i
                            class="fas fa-plus"></i> <?= _("Add task") ?></button>
            </div>
        </div>
        <!-- calendar -->
        <div class="card card-primary">
            <div class="card-body p-0">
                <div id="calendar"></div>
            </div>
        </div>
        <!-- ./calendar -->
    </div>
    <div class="col-md-4 col-12">
        <div class="card">
            <div class="card-header bg-success">
                <h3 class="card-title">Вичитано годин</h3>
                <div class="card-tools">
                    <a type="button" class="btn btn-tool disabled">
                        <i class="fas fa-print"></i>
                    </a>
                </div>
            </div>
            <div class="card-body p-2">
                <?php foreach ($timetable->finishedLessons($UID) as $group): ?>
                    <?= $group['index'] ?>
                    <?php foreach ($group['subjects'] as $sid => $item): ?>
                        <div class="progress-group">
                            <span class="progress-text"><a href="<?= APP_URL_F . "/workflow?p=subjects&sid={$sid}"?>"><?= $item['subject'] ?></a></span>
                            <span class="float-right"><b><?= $item['done'] ?></b>/<?= $item['plan'] ?></span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-success" style="width: <?= $item['%'] ?>%"></div>
                                <?php if ($item[1]['p'] > 0): ?>
                                    <div class="progress-bar bg-danger"
                                         style="width:<?= $item[1]['%'] ?>%"><?= $item[1]['h'] ?></div>
                                <?php
                                endif;
                                if ($item[2]['p'] > 0): ?>
                                    <div class="progress-bar bg-warning"
                                         style="width:<?= $item[2]['%'] ?>%"><?php echo $item[2]['h'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <div class="row">
                    <div class="col-4"><i class="fa fa-circle text-success"></i> вичитано</div>
                    <div class="col-4"><i class="fa fa-circle text-danger"></i> 1 семестр</div>
                    <div class="col-4"><i class="fa fa-circle text-warning"></i> 2 семестр</div>
                </div>
            </div>
            <div class="card-footer bg-success"><a href="#" class="btn btn-block btn-sm"><?= _("More info") ?> <i
                            class="fas fa-arrow-circle-right"></i></a></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-calendar" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Default Modal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p>One fine body…</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= _("Close") ?></button>
                <a href="#" type="button" class="btn btn-primary lesson"><?= _("Lesson") ?></a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>