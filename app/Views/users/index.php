<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= lang('App.users') ?></title>
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

    <div class="container" style="max-width: 950px;">
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success py-2 auto-dismiss-alert"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger py-2 auto-dismiss-alert"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="card p-4 shadow-sm border-0 rounded-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-0"><?= lang('App.users') ?></h3>
                    <p class="text-muted small mb-0">Gestión general de cuentas y permisos de administradores</p>
                </div>
                <a href="/users/create" class="btn btn-primary fw-bold shadow-sm">+ <?= lang('App.new_user') ?></a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light border-bottom">
                        <tr>
                            <th class="fw-bold text-secondary"><?= lang('App.name') ?></th>
                            <th class="fw-bold text-secondary"><?= lang('App.email') ?></th>
                            <th class="fw-bold text-secondary text-center"><?= lang('App.active') ?></th>
                            <th class="fw-bold text-secondary text-center"><?= lang('App.administrator') ?></th>
                            <th class="fw-bold text-secondary"><?= lang('App.created_at') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($users)): ?>
                            <?php foreach($users as $u): ?>
                                <tr>
                                    <td>
                                        <a href="/users/show/<?= $u['id'] ?>" class="fw-bold text-primary text-decoration-none">
                                            <?= esc($u['name']) ?>
                                        </a>
                                    </td>
                                    <td class="text-secondary"><?= esc($u['email']) ?></td>
                                    <td class="text-center">
                                        <?php if($u['active']): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3"><?= lang('App.yes') ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3"><?= lang('App.no') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if($u['is_admin']): ?>
                                            <span class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill px-3"><?= lang('App.yes') ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3"><?= lang('App.no') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-muted small"><?= date('Y-m-d H:i:s', strtotime($u['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-start gap-2 mt-4 pt-3 border-top">
                <?php 
                    $prevUrl = (isset($pager) && method_exists($pager, 'getPrevious')) ? $pager->getPrevious() : null;
                    $nextUrl = (isset($pager) && method_exists($pager, 'getNext')) ? $pager->getNext() : null;
                ?>

                <?php if ($prevUrl): ?>
                    <a href="<?= $prevUrl ?>" class="btn btn-outline-primary btn-sm px-3 fw-bold"><?= lang('App.newer') ?></a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary btn-sm px-3 disabled"><?= lang('App.newer') ?></button>
                <?php endif; ?>

                <?php if ($nextUrl): ?>
                    <a href="<?= $nextUrl ?>" class="btn btn-outline-primary btn-sm px-3 fw-bold"><?= lang('App.older') ?></a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary btn-sm px-3 disabled"><?= lang('App.older') ?></button>
                <?php endif; ?>
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
