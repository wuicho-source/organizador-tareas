<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                
                <?php if (session()->get('is_admin')): ?>
                    <a href="/users" class="btn btn-outline-info btn-sm me-2"><?= lang('App.users') ?></a>
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

    <div class="container my-4" style="max-width: 850px;">
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success py-2 auto-dismiss-alert"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger py-2 auto-dismiss-alert"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="card p-4 p-md-5 shadow-sm border-0 rounded-4">
            <h2 class="fw-bold text-dark mb-3"><?= lang('App.tasks') ?></h2>

            <div class="mb-4">
                <a href="/tasks/create" class="btn btn-primary fw-bold px-4 shadow-sm">+ <?= lang('App.new_task') ?></a>
            </div>

            <form action="/tasks" method="get" class="mb-4" style="max-width: 320px;">
                <input type="hidden" name="order" value="<?= esc($order ?? 'newer') ?>">
                <label class="form-label fw-bold text-secondary small mb-1"><?= lang('App.search') ?></label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">🔍</span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="<?= lang('App.search') ?>" value="<?= esc($search ?? '') ?>">
                </div>
            </form>

            <div class="list-group list-group-flush border-top border-bottom mb-4">
                <?php if(empty($tasks)): ?>
                    <div class="text-muted py-4 text-center"><?= lang('App.no_tasks') ?></div>
                <?php else: ?>
                    <?php foreach($tasks as $t): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3 bg-transparent">
                            <a href="/tasks/edit/<?= $t['id'] ?>" class="fw-semibold fs-5 text-primary text-decoration-none">
                                <?= esc($t['class_name']) ?>
                            </a>
                            <div>
                                <a href="/tasks/delete/<?= $t['id'] ?>" class="btn btn-sm btn-outline-danger px-3" onclick="return confirm('<?= lang('App.are_you_sure') ?>');"><?= lang('App.delete') ?></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="d-flex justify-content-start gap-2 pt-2">
                <a href="/tasks?search=<?= urlencode($search ?? '') ?>&order=newer" class="btn <?= ($order ?? 'newer') === 'newer' ? 'btn-primary' : 'btn-outline-primary' ?> btn-sm px-3 fw-bold">
                    <?= lang('App.newer') ?>
                </a>
                <a href="/tasks?search=<?= urlencode($search ?? '') ?>&order=older" class="btn <?= ($order ?? '') === 'older' ? 'btn-primary' : 'btn-outline-primary' ?> btn-sm px-3 fw-bold">
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
