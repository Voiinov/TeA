<?php
    $options = new \Core\Services\Options();
?>
<div class="card card-primary">
    <form>
        <div class="card-body">
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label><?= _("Last name") ?></label>
                        <input type="email" class="form-control" name="last_name" placeholder="<?= _("Last name") ?>" required>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label><?= _("First name") ?></label>
                        <input type="email" class="form-control" name="first_name" placeholder="<?= _("First name") ?>" required>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label><?= _("Middle name") ?></label>
                        <input type="email" class="form-control" name="middle_name" placeholder="<?= _("Middle name") ?>" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label><?= _("Email address") ?></label>
                        <input type="email" class="form-control" name="email" placeholder="<?= _("Email") ?>" required>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label><?= _("Post") ?></label>
                        <select class="form-control select2" name="post" required>
                            <?php foreach ($options->getPosts() as $post): ?>
                                <option value="<?= $post['id'] ?>"><?= $post['value'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label><?= _("Role") ?></label>
                        <select class="form-control select2" name="role" required>
                            <?php foreach ($options->getRoles() as $post): ?>
                                <option value="<?= $post['id'] ?>"><?= $post['value'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                    <div class="form-group">
                        <label><?= _("Gender") ?></label>
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" value="m" required>
                                <label class="form-check-label"><?= _("Male") ?></label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" value="f" required>
                                <label class="form-check-label"><?= _("Female") ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="form-group">
                        <label><?= _("Birthday") ?></label>

                    </div>
                </div>
                <div class="col-5">
                    <div class="form-group">
                        <label><?= _("Teaching experience") ?></label>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>