<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= isset($task) ? lang('App.edit_task') : lang('App.new_task') ?></title>
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

    <div class="container" style="max-width: 650px;">
        <div class="card p-4 shadow-sm border-0 rounded-4">
            <h3 class="fw-bold mb-4 text-primary"><?= isset($task) ? lang('App.edit_task') : lang('App.new_task') ?></h3>

            <form action="<?= isset($task) ? '/tasks/update/' . $task['id'] : '/tasks/create' ?>" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label fw-bold"><?= lang('App.class_name') ?></label>
                    <input type="text" name="class_name" class="form-control" value="<?= esc($task['class_name'] ?? '') ?>" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold"><?= lang('App.assigned_date') ?></label>
                        <input type="date" name="assigned_date" class="form-control" value="<?= esc($task['assigned_date'] ?? date('Y-m-d')) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold"><?= lang('App.due_date') ?></label>
                        <input type="date" name="due_date" class="form-control" value="<?= esc($task['due_date'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold"><?= lang('App.notes') ?></label>
                    <textarea name="notes" class="form-control" rows="4" placeholder="Escribe tus apuntes aquí..."><?= esc($task['notes'] ?? '') ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold"><?= lang('App.image') ?></label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <?php if(isset($task) && !empty($task['image'])): ?>
                        <div class="mt-2">
                            <img src="/uploads/tasks/<?= $task['image'] ?>" class="img-thumbnail rounded" style="max-height: 100px;">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success px-4"><?= lang('App.save') ?></button>
                    <a href="/tasks" class="btn btn-danger px-4"><?= lang('App.cancel') ?></a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
