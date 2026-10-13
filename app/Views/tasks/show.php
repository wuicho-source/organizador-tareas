<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= esc($task['class_name']) ?> - <?= lang('App.tasks') ?></title>
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

    <div class="container" style="max-width: 850px;">
        <div class="mb-3">
            <a href="/tasks" class="btn btn-outline-secondary btn-sm">&laquo; <?= lang('App.tasks') ?></a>
        </div>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success py-2 auto-dismiss-alert"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-4">
                    <div>
                        <h2 class="text-primary fw-bold mb-1"><?= esc($task['class_name'] ?? 'Materia sin nombre') ?></h2>
                        <span class="badge bg-secondary">ID: #<?= $task['id'] ?></span>
                    </div>
                    <div class="text-end text-muted">
                        <small class="d-block"><strong><?= lang('App.assigned_date') ?>:</strong> <?= esc($task['assigned_date']) ?></small>
                        <small class="d-block"><strong><?= lang('App.due_date') ?>:</strong> <?= esc($task['due_date']) ?></small>
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="fw-bold mb-2">📌 <?= lang('App.notes') ?></h5>
                    <div class="p-3 rounded bg-light border" style="min-height: 100px; white-space: pre-wrap; font-size: 1rem;">
<?= !empty($task['notes']) ? esc($task['notes']) : lang('App.no_tasks') ?>
                    </div>
                </div>

                <div class="mb-3">
                    <h5 class="fw-bold mb-3">📷 <?= lang('App.image') ?></h5>
                    
                    <div class="row g-3 mb-4">
                        <?php foreach($images as $img): ?>
                            <div class="col-6 col-md-4">
                                <div class="card h-100 shadow-sm border">
                                    <a href="/uploads/tasks/<?= $img['image_path'] ?>" target="_blank">
                                        <img src="/uploads/tasks/<?= $img['image_path'] ?>" class="card-img-top" style="height: 150px; object-fit: cover;" alt="Adjunto">
                                    </a>
                                    <div class="card-footer p-2 text-center bg-white border-top">
                                        <a href="/tasks/delete-image/<?= $img['id'] ?>/<?= $task['id'] ?>" class="btn btn-sm btn-outline-danger w-100 py-1" onclick="return confirm('¿Eliminar imagen?');"><?= lang('App.delete') ?></a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php if(empty($images)): ?>
                            <div class="col-12">
                                <p class="text-muted fst-italic mb-0">No hay imágenes adjuntas a esta tarea.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="p-3 rounded border bg-light">
                        <label class="form-label fw-bold mb-2">Adjuntar más imágenes:</label>
                        <form action="/tasks/upload-images/<?= $task['id'] ?>" method="post" enctype="multipart/form-data" class="d-flex gap-2">
                            <input type="file" name="images[]" class="form-control" accept="image/*" multiple required>
                            <button type="submit" class="btn btn-primary px-4"><?= lang('App.save') ?></button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-light border-top p-3 d-flex justify-content-between align-items-center">
                <div class="d-flex gap-2">
                    <a href="/tasks/edit/<?= $task['id'] ?>" class="btn btn-primary px-3"><?= lang('App.edit') ?></a>
                    <a href="/tasks/toggle-hide/<?= $task['id'] ?>" class="btn btn-warning px-3">
                        <?= $task['is_hidden'] ? lang('App.show') : lang('App.hide') ?>
                    </a>
                </div>
                <a href="/tasks/delete/<?= $task['id'] ?>" class="btn btn-danger px-3" onclick="return confirm('¿Eliminar tarea?');"><?= lang('App.delete') ?></a>
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
