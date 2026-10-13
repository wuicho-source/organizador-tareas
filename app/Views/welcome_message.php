<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Organizador de Tareas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm px-4 mb-4">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="/"><?= lang('App.home') ?></a>
            <div class="d-flex align-items-center gap-2">
                <a href="/lang/en" class="btn btn-outline-light btn-sm">English</a>
                <a href="/lang/es" class="btn btn-outline-light btn-sm">Español</a>
                
                <?php if (session()->get('logged_in')): ?>
                    <a href="/tasks" class="btn btn-primary btn-sm ms-2"><?= lang('App.tasks') ?></a>
                    <div class="dropdown ms-3">
                        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php 
                                $userModel = new \App\Models\UserModel();
                                $currentUser = $userModel->find(session()->get('user_id'));
                                if (!empty($currentUser['profile_image']) && file_exists('uploads/profiles/' . $currentUser['profile_image'])): 
                            ?>
                                <img src="/uploads/profiles/<?= $currentUser['profile_image'] ?>" alt="Profile" class="rounded-circle me-2" style="width: 36px; height: 36px; object-fit: cover;">
                            <?php else: ?>
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                    <svg width="20" height="20" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16"><path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/></svg>
                                </div>
                            <?php endif; ?>
                            <span><?= lang('App.hello') ?>, <strong><?= session()->get('user_name') ?></strong></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userMenu">
                            <li><a class="dropdown-item" href="/profile"><?= lang('App.profile') ?></a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/logout"><?= lang('App.logout') ?></a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="/signup" class="btn btn-outline-light btn-sm ms-2"><?= lang('App.signup') ?></a>
                    <a href="/login" class="btn btn-success btn-sm ms-1"><?= lang('App.login') ?></a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show text-center auto-dismiss-alert shadow-sm" role="alert">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <div class="card p-5 text-center shadow-sm border-0 rounded-4">
            <h1 class="display-4 fw-bold text-primary mb-3"><?= lang('App.welcome') ?></h1>
            <p class="lead text-muted mb-0"><?= lang('App.subtitle') ?></p>
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
