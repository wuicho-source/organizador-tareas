<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= lang('App.edit_profile') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm px-4 mb-4">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="/"><?= lang('App.home') ?></a>
            <div class="d-flex align-items-center gap-2">
                <a href="/lang/en" class="btn btn-outline-light btn-sm">English</a>
                <a href="/lang/es" class="btn btn-outline-light btn-sm">Español</a>
                
                <a href="/tasks" class="btn btn-primary btn-sm me-2"><?= lang('App.tasks') ?></a>
                
                <?php if (session()->get('is_admin')): ?>
                    <a href="/users" class="btn btn-outline-info btn-sm me-2"><?= lang('App.users') ?></a>
                <?php endif; ?>

                <a href="/profile" class="btn btn-outline-light btn-sm me-2"><?= lang('App.profile') ?></a>
                <a href="/logout" class="btn btn-outline-danger btn-sm"><?= lang('App.logout') ?></a>
            </div>
        </div>
    </nav>

    <div class="container my-5" style="max-width: 600px;">
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger py-2 auto-dismiss-alert"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="card p-4 p-md-5 shadow-sm border-0 rounded-4">
            <h3 class="fw-bold text-dark mb-4"><?= lang('App.edit_profile') ?></h3>

            <form action="/profile/edit" method="post">
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary"><?= lang('App.name') ?></label>
                    <input type="text" name="name" class="form-control form-control-lg fs-6" value="<?= esc($user['name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary"><?= lang('App.email') ?></label>
                    <input type="email" name="email" class="form-control form-control-lg fs-6" value="<?= esc($user['email']) ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold text-secondary"><?= lang('App.bio') ?></label>
                    <textarea name="bio" class="form-control" rows="4" placeholder="Cuéntanos un poco sobre ti..."><?= esc($user['bio'] ?? '') ?></textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success fw-bold px-4 shadow-sm"><?= lang('App.save') ?></button>
                    <a href="/profile" class="btn btn-outline-secondary px-4"><?= lang('App.cancel') ?></a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
