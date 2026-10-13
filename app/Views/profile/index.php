<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= lang('App.profile') ?></title>
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
                        <?php if (!empty($user['profile_image']) && file_exists('uploads/profiles/' . $user['profile_image'])): ?>
                            <img src="/uploads/profiles/<?= $user['profile_image'] ?>" alt="Profile" class="rounded-circle me-2" style="width: 36px; height: 36px; object-fit: cover;">
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
            </div>
        </div>
    </nav>

    <div class="container" style="max-width: 800px;">
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success py-2 auto-dismiss-alert"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger py-2 auto-dismiss-alert"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="card p-4 shadow-sm border-0 rounded-4">
            <h2 class="mb-4 fw-bold"><?= lang('App.profile_title') ?></h2>
            
            <div class="row">
                <div class="col-md-4 text-center">
                    <?php if (!empty($user['profile_image']) && file_exists('uploads/profiles/' . $user['profile_image'])): ?>
                        <img src="/uploads/profiles/<?= $user['profile_image'] ?>" alt="Profile" class="img-thumbnail rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    <?php else: ?>
                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 150px; height: 150px;">
                            <svg width="80" height="80" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                            </svg>
                        </div>
                    <?php endif; ?>

                    <div>
                        <a href="/profile/delete-image" class="btn btn-outline-danger btn-sm mb-3"><?= lang('App.delete_image') ?></a>
                    </div>
                </div>

                <div class="col-md-8">
                    <h3 class="text-uppercase fw-bold text-dark mb-4"><?= esc($user['name']) ?></h3>

                    <div class="mb-3">
                        <h5 class="fw-bold mb-1"><?= lang('App.contact') ?></h5>
                        <p class="text-muted mb-0"><?= esc($user['email']) ?></p>
                    </div>

                    <div class="mb-4">
                        <h5 class="fw-bold mb-1"><?= lang('App.bio') ?></h5>
                        <p class="text-muted mb-0">
                            <?= !empty($user['bio']) ? nl2br(esc($user['bio'])) : lang('App.no_bio') ?>
                        </p>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="/profile/confirm-password" class="btn btn-primary"><?= lang('App.edit') ?></a>
                        <a href="/profile/change-password" class="btn btn-primary"><?= lang('App.change_password') ?></a>
                        <a href="/profile/change-image" class="btn btn-primary"><?= lang('App.change_image') ?></a>
                        <a href="/profile/delete-account" class="btn btn-outline-danger"><?= lang('App.delete_account') ?></a>
                    </div>
                </div>
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
