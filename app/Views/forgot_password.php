<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= lang('App.forgot_password') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm px-4 mb-4">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="/"><?= lang('App.home') ?></a>
            <div class="d-flex align-items-center gap-2">
                <a href="/lang/en" class="btn btn-outline-light btn-sm">English</a>
                <a href="/lang/es" class="btn btn-outline-light btn-sm">Español</a>
                <a href="/login" class="btn btn-primary btn-sm fw-bold ms-2"><?= lang('App.login') ?></a>
                <a href="/signup" class="btn btn-outline-light btn-sm"><?= lang('App.signup') ?></a>
            </div>
        </div>
    </nav>

    <div class="container my-5" style="max-width: 480px;">
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger py-2 auto-dismiss-alert"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success py-2 auto-dismiss-alert"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="card p-4 p-md-5 shadow-sm border-0 rounded-4">
            <h3 class="fw-bold text-dark mb-2"><?= lang('App.forgot_password') ?></h3>
            <p class="text-secondary small mb-4">Ingresa tu correo electrónico para enviarte las instrucciones de restablecimiento.</p>

            <form action="/forgot-password" method="post">
                <div class="mb-4">
                    <label class="form-label fw-semibold text-secondary"><?= lang('App.email') ?></label>
                    <input type="email" name="email" class="form-control form-control-lg fs-6" required placeholder="correo@ejemplo.com">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary fw-bold px-4 shadow-sm"><?= lang('App.send') ?></button>
                    <a href="/login" class="btn btn-outline-secondary px-4"><?= lang('App.cancel') ?></a>
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
