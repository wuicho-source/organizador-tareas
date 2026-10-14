<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= lang('App.signup') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm px-4 mb-4">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="/"><?= lang('App.home') ?></a>
            <div class="d-flex align-items-center gap-2">
                <a href="/lang/en" class="btn btn-outline-light btn-sm">English</a>
                <a href="/lang/es" class="btn btn-outline-light btn-sm">Español</a>
                <a href="/login" class="btn btn-outline-light btn-sm ms-2"><?= lang('App.login') ?></a>
                <a href="/signup" class="btn btn-primary btn-sm fw-bold"><?= lang('App.signup') ?></a>
            </div>
        </div>
    </nav>

    <div class="container my-5" style="max-width: 520px;">
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger py-2 auto-dismiss-alert"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="card p-4 p-md-5 shadow-sm border-0 rounded-4">
            <h3 class="fw-bold text-center text-dark mb-4"><?= lang('App.signup') ?></h3>

            <form action="/signup" method="post">
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary"><?= lang('App.name') ?></label>
                    <input type="text" name="name" class="form-control form-control-lg fs-6" required placeholder="Tu nombre completo">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary"><?= lang('App.email') ?></label>
                    <input type="email" name="email" class="form-control form-control-lg fs-6" required placeholder="correo@ejemplo.com">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold text-secondary"><?= lang('App.password') ?></label>
                    <input type="password" name="password" class="form-control form-control-lg fs-6" required placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn-success btn-lg w-100 fw-bold shadow-sm mb-3"><?= lang('App.signup') ?></button>
            </form>

            <div class="text-center pt-3 border-top">
                <span class="text-muted small">¿Ya tienes una cuenta?</span>
                <a href="/login" class="text-primary fw-bold text-decoration-none small ms-1"><?= lang('App.login') ?></a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
