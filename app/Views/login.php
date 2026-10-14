<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= lang('App.login') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 mb-4">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-3">
                <a class="navbar-brand fw-normal fs-5 me-1" href="/"><?= lang('App.home') ?></a>
                <a href="/lang/en" class="text-white text-decoration-none">English</a>
                <a href="/lang/es" class="text-white text-decoration-none">Español</a>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="/login" class="text-white fw-bold text-decoration-none"><?= lang('App.login') ?></a>
                <a href="/signup" class="text-white text-decoration-none"><?= lang('App.signup') ?></a>
            </div>
        </div>
    </nav>

    <div class="container mt-5" style="max-width: 500px;">
        <div class="card p-4 shadow-sm border-0 rounded-4">
            <h2 class="fw-bold text-center text-dark mb-4"><?= lang('App.login') ?></h2>

            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success py-2 auto-dismiss-alert"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger py-2 auto-dismiss-alert"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <form action="/login" method="post">
                <div class="mb-3">
                    <label class="form-label fw-bold"><?= lang('App.email') ?></label>
                    <input type="email" name="email" class="form-control form-control-lg" required placeholder="correo@ejemplo.com">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold"><?= lang('App.password') ?></label>
                    <input type="password" name="password" class="form-control form-control-lg" required placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold mb-3"><?= lang('App.login') ?></button>
            </form>

            <div class="text-center pt-2 border-top">
                <span class="text-muted">¿No tienes una cuenta?</span>
                <a href="/signup" class="text-primary fw-bold text-decoration-none ms-1"><?= lang('App.signup') ?></a>
            </div>
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
