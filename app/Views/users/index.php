<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <div class="container my-4" style="max-width: 900px;">
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success py-2 auto-dismiss-alert"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger py-2 auto-dismiss-alert"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="card p-4 p-md-5 shadow-sm border-0 rounded-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark mb-0"><?= lang('App.users') ?></h2>
                <a href="/users/create" class="btn btn-primary fw-bold shadow-sm">+ <?= lang('App.new_user') ?></a>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th><?= lang('App.name') ?></th>
                            <th><?= lang('App.email') ?></th>
                            <th><?= lang('App.active') ?></th>
                            <th><?= lang('App.administrator') ?></th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $u): ?>
                            <tr>
                                <td class="fw-bold">
                                    <a href="/users/show/<?= $u['id'] ?>" class="text-decoration-none text-primary">
                                        <?= esc($u['name']) ?>
                                    </a>
                                </td>
                                <td><?= esc($u['email']) ?></td>
                                <td>
                                    <?php if($u['active']): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3"><?= lang('App.yes') ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3"><?= lang('App.no') ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($u['is_admin']): ?>
                                        <span class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill px-3"><?= lang('App.yes') ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3"><?= lang('App.no') ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <?php if($u['id'] != session()->get('user_id')): ?>
                                        <a href="/users/delete/<?= $u['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('<?= lang('App.are_you_sure') ?>');"><?= lang('App.delete') ?></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-start gap-2 pt-2 border-top">
                <a href="/users?order=newer" class="btn <?= ($order ?? 'newer') === 'newer' ? 'btn-primary' : 'btn-outline-primary' ?> btn-sm px-3 fw-bold">
                    <?= lang('App.newer') ?>
                </a>
                <a href="/users?order=older" class="btn <?= ($order ?? '') === 'older' ? 'btn-primary' : 'btn-outline-primary' ?> btn-sm px-3 fw-bold">
                    <?= lang('App.older') ?>
                </a>
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
