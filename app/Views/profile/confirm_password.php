<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= lang('App.edit_profile') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
    <div class="card p-3 shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a href="/" class="me-3 text-decoration-none fw-bold text-dark"><?= lang('App.home') ?></a>
                <a href="/lang/en" class="me-2 btn btn-outline-secondary btn-sm">English</a>
                <a href="/lang/es" class="btn btn-outline-secondary btn-sm">Español</a>
            </div>
            <div>
                <span class="me-3"><?= lang('App.hello') ?>, <?= session()->get('user_name') ?></span>
                <a href="/profile" class="me-2 text-decoration-none fw-bold"><?= lang('App.profile') ?></a>
                <a href="/tasks" class="me-2 text-decoration-none"><?= lang('App.tasks') ?></a>
                <a href="/logout" class="text-decoration-none text-danger"><?= lang('App.logout') ?></a>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center align-items-center mt-4">
        <div class="card p-4 shadow-sm" style="width: 450px;">
            <h4 class="mb-3"><?= lang('App.confirm_pass_msg') ?></h4>

            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger py-2"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <form action="/profile/confirm-password" method="post">
                <div class="mb-3">
                    <label class="form-label"><?= lang('App.password') ?></label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success px-4"><?= lang('App.send') ?></button>
                    <a href="/profile" class="btn btn-danger px-4"><?= lang('App.cancel') ?></a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
