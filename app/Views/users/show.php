<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= lang('App.user') ?></title>
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
                    <a href="/users" class="btn btn-info btn-sm text-white fw-bold me-2"><?= lang('App.users') ?></a>
                <?php endif; ?>

                <div class="dropdown">
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

    <div class="container" style="max-width: 700px;">
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success py-2 auto-dismiss-alert"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger py-2 auto-dismiss-alert"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="mb-3">
            <a href="/users" class="btn btn-link text-decoration-none p-0 fw-bold">← <?= lang('App.back_to_index') ?></a>
        </div>

        <div class="card p-4 shadow-sm border-0 rounded-4">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h3 class="fw-bold text-dark mb-0">👤 <?= lang('App.user') ?>: <?= esc($user['name']) ?></h3>
                <div>
                    <?php if($user['is_admin']): ?>
                        <span class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill px-3">Administrador</span>
                    <?php else: ?>
                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3">Usuario</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-sm-6">
                    <label class="text-muted small fw-bold text-uppercase"><?= lang('App.name') ?></label>
                    <p class="fs-5 fw-bold text-dark mb-0"><?= esc($user['name']) ?></p>
                </div>
                <div class="col-sm-6">
                    <label class="text-muted small fw-bold text-uppercase"><?= lang('App.email') ?></label>
                    <p class="fs-5 text-dark mb-0"><?= esc($user['email']) ?></p>
                </div>
                <div class="col-sm-6">
                    <label class="text-muted small fw-bold text-uppercase"><?= lang('App.active') ?></label>
                    <div>
                        <?php if($user['active']): ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3"><?= lang('App.yes') ?></span>
                        <?php else: ?>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3"><?= lang('App.no') ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label class="text-muted small fw-bold text-uppercase"><?= lang('App.administrator') ?></label>
                    <div>
                        <?php if($user['is_admin']): ?>
                            <span class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill px-3"><?= lang('App.yes') ?></span>
                        <?php else: ?>
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3"><?= lang('App.no') ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label class="text-muted small fw-bold text-uppercase"><?= lang('App.created_at') ?></label>
                    <p class="text-secondary mb-0"><?= date('Y-m-d H:i:s', strtotime($user['created_at'])) ?></p>
                </div>
                <div class="col-sm-6">
                    <label class="text-muted small fw-bold text-uppercase"><?= lang('App.updated_at') ?></label>
                    <p class="text-secondary mb-0"><?= date('Y-m-d H:i:s', strtotime($user['updated_at'] ?? $user['created_at'])) ?></p>
                </div>
            </div>

            <div class="d-flex gap-2 pt-3 border-top">
                <a href="/users/edit/<?= $user['id'] ?>" class="btn btn-primary px-4 fw-bold"><?= lang('App.edit') ?></a>
                <?php if($user['id'] != session()->get('user_id')): ?>
                    <a href="/users/delete/<?= $user['id'] ?>" class="btn btn-outline-danger px-4" onclick="return confirm('¿Está seguro de eliminar este usuario?');"><?= lang('App.delete') ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
