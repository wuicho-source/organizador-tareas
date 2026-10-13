<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= lang('App.tasks') ?></title>
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
            </div>
        </div>
    </nav>

    <div class="container" style="max-width: 900px;">
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success py-2 auto-dismiss-alert"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold"><?= lang('App.tasks') ?></h2>
            <div>
                <a href="/tasks/create" class="btn btn-success me-2">+ <?= lang('App.new_task') ?></a>
                <a href="/tasks?show_hidden=<?= $show_hidden ? '0' : '1' ?>" class="btn btn-outline-secondary">
                    <?= $show_hidden ? lang('App.active_tasks') : lang('App.hidden_tasks') ?>
                </a>
            </div>
        </div>

        <form action="/tasks" method="get" class="mb-4">
            <input type="hidden" name="show_hidden" value="<?= $show_hidden ?>">
            <div class="input-group shadow-sm">
                <input type="text" name="search" class="form-control" placeholder="<?= lang('App.search') ?>" value="<?= esc($search) ?>">
                <button type="submit" class="btn btn-primary"><?= lang('App.search') ?></button>
            </div>
        </form>

        <div class="row">
            <?php foreach($tasks as $task): ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-body d-flex flex-column p-4">
                            <h5 class="card-title mb-2">
                                <a href="/tasks/show/<?= $task['id'] ?>" class="text-decoration-none text-primary fw-bold">
                                    📋 <?= esc($task['class_name'] ?? 'Clase sin nombre') ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted small mb-3">
                                <strong><?= lang('App.assigned_date') ?>:</strong> <?= esc($task['assigned_date']) ?><br>
                                <strong><?= lang('App.due_date') ?>:</strong> <?= esc($task['due_date']) ?>
                            </p>
                            <p class="card-text flex-grow-1 text-truncate text-secondary"><?= esc($task['notes']) ?></p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                <a href="/tasks/show/<?= $task['id'] ?>" class="btn btn-sm btn-info text-white fw-bold">Abrir Pizarrón &raquo;</a>
                                <div class="d-flex gap-1">
                                    <a href="/tasks/edit/<?= $task['id'] ?>" class="btn btn-sm btn-outline-primary"><?= lang('App.edit') ?></a>
                                    <a href="/tasks/toggle-hide/<?= $task['id'] ?>" class="btn btn-sm btn-outline-warning">
                                        <?= $task['is_hidden'] ? lang('App.show') : lang('App.hide') ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if(empty($tasks)): ?>
                <div class="col-12">
                    <div class="card p-5 text-center text-muted shadow-sm border-0">
                        <?= lang('App.no_tasks') ?>
                    </div>
                </div>
            <?php endif; ?>
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
