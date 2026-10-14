<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= isset($user) ? 'Edit user' : lang('App.new_user') ?></title>
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

    <div class="container" style="max-width: 650px;">
        <div class="mb-3">
            <a href="/users" class="btn btn-link text-decoration-none p-0 fw-bold">← <?= lang('App.back_to_index') ?></a>
        </div>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger py-2 auto-dismiss-alert"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="card p-4 shadow-sm border-0 rounded-4">
            <h3 class="fw-bold mb-4 text-dark"><?= isset($user) ? 'Edit user' : lang('App.new_user') ?></h3>

            <form action="<?= isset($user) ? '/users/update/' . $user['id'] : '/users/create' ?>" method="post">
                <div class="mb-3">
                    <label class="form-label fw-bold"><?= lang('App.name') ?></label>
                    <input type="text" name="name" class="form-control" value="<?= esc($user['name'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold"><?= lang('App.email') ?></label>
                    <input type="email" name="email" class="form-control" value="<?= esc($user['email'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold"><?= lang('App.password') ?></label>
                    <input type="password" name="password" class="form-control" <?= isset($user) ? '' : 'required' ?> placeholder="<?= isset($user) ? 'Dejar en blanco para mantener la actual' : '' ?>">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold"><?= lang('App.repeat_password') ?></label>
                    <input type="password" name="repeat_password" class="form-control" <?= isset($user) ? '' : 'required' ?>>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="active" class="form-check-input" id="active" value="1" <?= (isset($user) && $user['active']) || !isset($user) ? 'checked' : '' ?>>
                    <label class="form-check-label fw-bold" for="active"><?= lang('App.active') ?></label>
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" name="is_admin" class="form-check-input" id="is_admin" value="1" <?= isset($user) && $user['is_admin'] ? 'checked' : '' ?>>
                    <label class="form-check-label fw-bold" for="is_admin"><?= lang('App.administrator') ?></label>
                </div>

                <div class="d-flex gap-2 pt-2 border-top">
                    <button type="submit" class="btn btn-success px-4 fw-bold"><?= lang('App.save') ?></button>
                    <a href="/users" class="btn btn-outline-secondary px-4"><?= lang('App.cancel') ?></a>
                </div>
            </form>
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
