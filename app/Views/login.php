<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= lang('App.login') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm px-4 mb-4">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="/"><?= lang('App.home') ?></a>
            <div class="d-flex align-items-center gap-2">
                <a href="/lang/en" class="btn btn-outline-light btn-sm">English</a>
                <a href="/lang/es" class="btn btn-outline-light btn-sm">Español</a>
                <a href="/signup" class="btn btn-outline-light btn-sm ms-2"><?= lang('App.signup') ?></a>
                <a href="/login" class="btn btn-success btn-sm ms-1"><?= lang('App.login') ?></a>
            </div>
        </div>
    </nav>

    <div class="d-flex justify-content-center align-items-center mt-5">
        <div class="card p-4 shadow-sm border-0 rounded-4" style="width: 420px;">
            <h3 class="fw-bold mb-4 text-center text-primary"><?= lang('App.login') ?></h3>

            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger py-2 auto-dismiss-alert"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success py-2 auto-dismiss-alert"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <form action="/login" method="post">
                <div class="mb-3">
                    <label class="form-label fw-semibold"><?= lang('App.email') ?></label>
                    <input type="email" name="email" class="form-control" placeholder="correo@ejemplo.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold"><?= lang('App.password') ?></label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-4 text-end">
                    <a href="/forgot-password" class="text-decoration-none small text-muted"><?= lang('App.forgot_password') ?></a>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-3"><?= lang('App.login') ?></button>
                <div class="text-center">
                    <a href="/signup" class="text-decoration-none small"><?= lang('App.need_account') ?></a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        setTimeout(function() {
            let alerts = document.querySelectorAll('.auto-dismiss-alert');
            alerts.forEach(function(alert) {
                alert.style.transition = "opacity 0.5s ease";
                alert.style.opacity = 0;
                setTimeout(() => alert.remove(), 500);
            });
        }, 3000);
    </script>
</body>
</html>
