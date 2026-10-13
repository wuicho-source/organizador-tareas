<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= lang('App.tasks') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
    <div class="card p-3 shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a href="/" class="me-3 text-decoration-none fw-bold text-dark"><?= lang('App.home') ?></a>
                <a href="/lang/en" class="me-2 btn btn-outline-secondary btn-sm">English</a>
                <a href="/lang/es" class="btn btn-outline-secondary btn-sm">Español</a>
            </div>
            <div>
                <span class="me-3"><?= lang('App.hello') ?>, <?= session()->get('user_name') ?></span>
                <a href="/profile" class="me-2 text-decoration-none"><?= lang('App.profile') ?></a>
                <?php if(session()->get('is_admin')): ?>
                    <a href="/users" class="me-2 text-decoration-none"><?= lang('App.users') ?></a>
                <?php endif; ?>
                <a href="/tasks" class="me-2 text-decoration-none fw-bold"><?= lang('App.tasks') ?></a>
                <a href="/logout" class="text-decoration-none text-danger"><?= lang('App.logout') ?></a>
            </div>
        </div>
    </div>

    <div class="container" style="max-width: 600px;">
        <div class="card p-4 shadow-sm mb-4">
            <h4><?= lang('App.new_task') ?></h4>
            <form action="/tasks/create" method="post" class="d-flex gap-2">
                <input type="text" name="description" class="form-control" placeholder="<?= lang('App.placeholder') ?>" required>
                <button type="submit" class="btn btn-success"><?= lang('App.save') ?></button>
            </form>
        </div>

        <div class="card p-4 shadow-sm">
            <h4><?= lang('App.my_tasks') ?></h4>
            <ul class="list-group mt-2">
                <?php foreach($tasks as $task): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= esc($task['description']) ?>
                        <a href="/tasks/delete/<?= $task['id'] ?>" class="btn btn-sm btn-outline-danger"><?= lang('App.delete') ?></a>
                    </li>
                <?php endforeach; ?>
                <?php if(empty($tasks)): ?>
                    <li class="list-group-item text-muted text-center"><?= lang('App.no_tasks') ?></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</body>
</html>
