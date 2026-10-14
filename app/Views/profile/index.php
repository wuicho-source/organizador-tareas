<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= lang('App.profile_title') ?></title>
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

                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php if (!empty($user['profile_image']) && file_exists('uploads/profiles/' . $user['profile_image'])): ?>
                            <img src="/uploads/profiles/<?= $user['profile_image'] ?>" alt="Profile" class="rounded-circle me-2" style="width: 36px; height: 36px; object-fit: cover;">
                        <?php else: ?>
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                <svg width="20" height="20" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16"><path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/></svg>
                            </div>
                        <?php endif; ?>
                        <span><?= lang('App.hello') ?>, <strong><?= esc($user['name']) ?></strong></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userMenu">
                        <li><a class="dropdown-item" href="/profile"><?= lang('App.profile') ?></a></li>
                        <?php if (session()->get('is_admin')): ?>
                            <li><a class="dropdown-item fw-bold text-primary" href="/users"><?= lang('App.users') ?></a></li>
                        <?php endif; ?>
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
            <div class="d-flex align-items-center gap-4 border-bottom pb-4 mb-4">
                <?php if (!empty($user['profile_image']) && file_exists('uploads/profiles/' . $user['profile_image'])): ?>
                    <img src="/uploads/profiles/<?= $user['profile_image'] ?>" alt="Profile Image" class="rounded-circle shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                <?php else: ?>
                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 120px; height: 120px;">
                        <svg width="60" height="60" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16"><path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/></svg>
                    </div>
                <?php endif; ?>

                <div>
                    <h3 class="fw-bold mb-1"><?= esc($user['name']) ?></h3>
                    <p class="text-muted mb-2"><?= esc($user['email']) ?></p>
                    <div>
                        <?php if($user['is_admin']): ?>
                            <span class="badge bg-warning text-dark me-2">Administrador</span>
                        <?php endif; ?>
                        <span class="badge bg-success">Cuenta Activa</span>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <h5 class="fw-bold text-dark mb-2"><?= lang('App.bio') ?></h5>
                <p class="text-secondary bg-light p-3 rounded-3 border mb-0">
                    <?= !empty($user['bio']) ? nl2br(esc($user['bio'])) : '<em>' . lang('App.no_bio') . '</em>' ?>
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2 pt-2 border-top">
                <a href="/profile/edit" class="btn btn-outline-primary"><?= lang('App.edit_profile') ?></a>
                <a href="/profile/change-password" class="btn btn-outline-secondary"><?= lang('App.change_password') ?></a>
                <a href="/profile/change-image" class="btn btn-outline-info"><?= lang('App.change_image') ?></a>
                <?php if (!empty($user['profile_image'])): ?>
                    <a href="/profile/delete-image" class="btn btn-outline-warning" onclick="return confirm('<?= lang('App.are_you_sure') ?>');"><?= lang('App.delete_image') ?></a>
                <?php endif; ?>
                <a href="/profile/delete-account" class="btn btn-outline-danger ms-auto" onclick="return confirm('<?= lang('App.confirm_delete_account') ?>');"><?= lang('App.delete_account') ?></a>
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
