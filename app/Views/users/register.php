<div class="d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
        <div class="card-body p-4">
            <h2 class="text-center mb-4"><?= esc($title) ?></h2>

            <div id="error-message" class="alert alert-danger py-2 d-none"></div>

            <form id="register-form">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="uid" class="form-label">User Name</label>
                    <input type="text" name="uid" id="uid" class="form-control" 
                           placeholder="Letters, numbers, underscores only"
                           value="<?= set_value('uid') ?>" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" 
                           placeholder="At least 6 characters"
                           required>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control" 
                           placeholder="Your name (supports Chinese)"
                           value="<?= set_value('name') ?>" required>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" id="register-btn" class="btn btn-primary">
                        <span id="btn-spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                        Sign Up
                    </button>
                </div>

                <div class="text-center mt-3">
                    <p class="text-muted">Already have an account? 
                        <a href="/users/login">Log In</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
