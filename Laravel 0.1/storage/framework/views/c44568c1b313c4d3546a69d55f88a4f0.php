<?php $__env->startSection('title', 'Registro - YACHAY'); ?>

<?php $__env->startSection('content'); ?>
<div style="min-height:100vh; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg, var(--primary-50), var(--secondary-50)); padding:var(--spacing-2xl);">
    <div class="card" style="max-width:550px; width:100%; padding:var(--spacing-2xl); box-shadow:var(--shadow-lg);">

        <div style="text-align:center; margin-bottom:var(--spacing-2xl);">
            <div style="width:80px; height:80px; margin:0 auto var(--spacing-lg); background:linear-gradient(135deg, var(--primary-600), var(--secondary-600)); border-radius:12px; display:flex; align-items:center; justify-content:center;">
                <i class="fas fa-user-plus" style="font-size:2.5rem; color:white;"></i>
            </div>

            <h2 style="margin:0; font-size:1.75rem;">Crear Cuenta</h2>
            <p style="color:var(--text-secondary); margin-top:6px;">Únete a la comunidad YACHAY</p>
        </div>

        <?php if($errors->any()): ?>
            <div style="padding:var(--spacing-md); background:#ef4444; color:white; border-radius:var(--radius-md); margin-bottom:var(--spacing-lg); text-align:center;">
                <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                <div style="margin-top:6px;"><?php echo e($errors->first()); ?></div>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('register')); ?>" novalidate>
            <?php echo csrf_field(); ?>

            <div style="margin-bottom:var(--spacing-md);">
                <label for="nombre" style="display:block; margin-bottom:var(--spacing-sm); font-weight:600;">
                    <i class="fas fa-user" aria-hidden="true"></i> Nombre
                </label>
                <input id="nombre" type="text" name="nombre" value="<?php echo e(old('nombre')); ?>" required aria-required="true" autocomplete="name"
                       style="width:100%; padding:var(--spacing-md); border:2px solid var(--primary-200); border-radius:var(--radius-md); font-size:var(--text-base);">
                <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="color:#ef4444; margin-top:6px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div style="margin-bottom:var(--spacing-md);">
                <label for="apellido" style="display:block; margin-bottom:var(--spacing-sm); font-weight:600;">
                    <i class="fas fa-user" aria-hidden="true"></i> Apellido
                </label>
                <input id="apellido" type="text" name="apellido" value="<?php echo e(old('apellido')); ?>" required
                       style="width:100%; padding:var(--spacing-md); border:2px solid var(--primary-200); border-radius:var(--radius-md); font-size:var(--text-base);">
                <?php $__errorArgs = ['apellido'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="color:#ef4444; margin-top:6px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div style="margin-bottom:var(--spacing-md);">
                <label for="email" style="display:block; margin-bottom:var(--spacing-sm); font-weight:600;">
                    <i class="fas fa-envelope" aria-hidden="true"></i> Email
                </label>
                <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email"
                       style="width:100%; padding:var(--spacing-md); border:2px solid var(--primary-200); border-radius:var(--radius-md); font-size:var(--text-base);">
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="color:#ef4444; margin-top:6px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div style="margin-bottom:var(--spacing-md);">
                <label for="codigo_universitario" style="display:block; margin-bottom:var(--spacing-sm); font-weight:600;">
                    <i class="fas fa-id-card" aria-hidden="true"></i> Código Universitario <span style="font-weight:400; color:var(--text-tertiary);">(opcional)</span>
                </label>
                <input id="codigo_universitario" type="text" name="codigo_universitario" value="<?php echo e(old('codigo_universitario')); ?>"
                       style="width:100%; padding:var(--spacing-md); border:2px solid var(--primary-200); border-radius:var(--radius-md); font-size:var(--text-base);">
                <?php $__errorArgs = ['codigo_universitario'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="color:#ef4444; margin-top:6px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div style="margin-bottom:var(--spacing-md);">
                <label for="password" style="display:block; margin-bottom:var(--spacing-sm); font-weight:600;">
                    <i class="fas fa-lock" aria-hidden="true"></i> Contraseña
                </label>
                <input id="password" type="password" name="password" required minlength="6" autocomplete="new-password"
                       style="width:100%; padding:var(--spacing-md); border:2px solid var(--primary-200); border-radius:var(--radius-md); font-size:var(--text-base);">
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="color:#ef4444; margin-top:6px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div style="margin-bottom:var(--spacing-lg);">
                <label for="password_confirmation" style="display:block; margin-bottom:var(--spacing-sm); font-weight:600;">
                    <i class="fas fa-lock" aria-hidden="true"></i> Confirmar Contraseña
                </label>
                <input id="password_confirmation" type="password" name="password_confirmation" required minlength="6" autocomplete="new-password"
                       style="width:100%; padding:var(--spacing-md); border:2px solid var(--primary-200); border-radius:var(--radius-md); font-size:var(--text-base);">
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; margin-bottom:var(--spacing-md); display:flex; align-items:center; justify-content:center; gap:8px;">
                <i class="fas fa-rocket" aria-hidden="true"></i> Crear Cuenta
            </button>
        </form>

        <div style="text-align:center; margin:var(--spacing-lg) 0; color:var(--text-secondary);">
            <span>─────── O ───────</span>
        </div>

        <a href="<?php echo e(route('google.login')); ?>" class="btn" style="width:100%; display:flex; align-items:center; justify-content:center; gap:var(--spacing-sm); background:white; color:#333; border:2px solid var(--primary-200); margin-bottom:var(--spacing-md); padding:var(--spacing-md);">
            <svg width="20" height="20" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Continuar con Google
        </a>

        <div style="text-align:center; margin-top:var(--spacing-lg);">
            <p style="color:var(--text-secondary); margin:0;">
                ¿Ya tienes cuenta? <a href="<?php echo e(route('login')); ?>" style="color:var(--primary-600); font-weight:600;">Inicia sesión aquí</a>
            </p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\resources\views/auth/register.blade.php ENDPATH**/ ?>