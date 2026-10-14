<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= lang('App.home') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm px-4 mb-4">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="/"><?= lang('App.home') ?></a>
            <div class="d-flex align-items-center gap-2">
                <a href="/lang/en" class="btn btn-outline-light btn-sm">English</a>
                <a href="/lang/es" class="btn btn-outline-light btn-sm">Español</a>
                
                <?php if(session()->get('logged_in')): ?>
                    <a href="/tasks" class="btn btn-primary btn-sm me-2"><?= lang('App.tasks') ?></a>
                    <?php if (session()->get('is_admin')): ?>
                        <a href="/users" class="btn btn-outline-info btn-sm me-2"><?= lang('App.users') ?></a>
                    <?php endif; ?>
                    <a href="/profile" class="btn btn-outline-light btn-sm me-2"><?= lang('App.profile') ?></a>
                    <a href="/logout" class="btn btn-outline-danger btn-sm"><?= lang('App.logout') ?></a>
                <?php else: ?>
                    <a href="/login" class="btn btn-outline-light btn-sm ms-2"><?= lang('App.login') ?></a>
                    <a href="/signup" class="btn btn-primary btn-sm fw-bold"><?= lang('App.signup') ?></a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container my-5 text-center" style="max-width: 750px;">
        <div class="card p-5 shadow-sm border-0 rounded-4">
            <div class="mb-3 fs-1">📝</div>
            <h1 class="fw-bold text-dark mb-3"><?= lang('App.welcome') ?></h1>
            <p class="text-secondary fs-5 mb-4"><?= lang('App.subtitle') ?></p>
            
            <div class="d-flex justify-content-center gap-3">
                <?php if(session()->get('logged_in')): ?>
                    <a href="/tasks" class="btn btn-primary btn-lg px-4 fw-bold shadow-sm">Ver Mis Tareas</a>
                <?php else: ?>
                    <a href="/login" class="btn btn-primary btn-lg px-4 fw-bold shadow-sm"><?= lang('App.login') ?></a>
                    <a href="/signup" class="btn btn-outline-secondary btn-lg px-4 fw-bold"><?= lang('App.signup') ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
