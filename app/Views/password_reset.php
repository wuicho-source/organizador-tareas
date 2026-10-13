<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Password reset</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
    <div class="card p-3 shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a href="/" class="me-3 text-decoration-none fw-bold text-dark"><?= lang('App.home') ?></a>
                <a href="/lang/en" class="me-2 btn btn-outline-secondary btn-sm">English</a>
                <a href="/lang/es" class="btn btn-outline-secondary btn-sm">Español</a>
            </div>
            <div>
                <a href="/signup" class="me-3 text-decoration-none"><?= lang('App.signup') ?></a>
                <a href="/login" class="text-decoration-none"><?= lang('App.login') ?></a>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center align-items-center mt-4">
        <div class="card p-4 shadow-sm" style="width: 420px;">
            <h3 class="card-title mb-4">Password reset</h3>
            
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger py-2"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <form action="/reset-password" method="post">
                <input type="hidden" name="token" value="<?= esc($token) ?>">
                <div class="mb-3">
                    <label class="form-label">password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Repeat password</label>
                    <input type="password" name="repeat_password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Reset password</button>
            </form>
        </div>
    </div>
</body>
</html>
