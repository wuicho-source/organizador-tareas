<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= lang('App.delete_account') ?></title>
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
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        <span><?= lang('App.hello') ?>, <strong><?= session()->get('user_name') ?></strong></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userMenu">
                        <li><a class="dropdown-item" href="/profile"><?= lang('App.profile') ?></a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="/logout"><?= lang('App.logout') ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="d-flex justify-content-center align-items-center mt-4">
        <div class="card p-4 shadow-sm border-0 rounded-4" style="width: 480px;">
            <h4 class="mb-3 text-danger fw-bold"><?= lang('App.delete_account') ?></h4>
            <p class="text-muted mb-4"><?= lang('App.confirm_delete_account') ?></p>

            <form action="/profile/delete-account" method="post">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-danger px-4 fw-bold"><?= lang('App.delete_account') ?></button>
                    <a href="/profile" class="btn btn-secondary px-4"><?= lang('App.cancel') ?></a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
