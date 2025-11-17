<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-COMPATIBLE" content="ie=edge">
    <meta name="description" content="YACHAY - Plataforma educativa para estudiantes de Ingeniería de Sistemas">
    <meta name="keywords" content="educación, universidad continental, sistemas, apuntes, libros, tutorías">
    <meta name="author" content="YACHAY Team">

    <title><?php echo $__env->yieldContent('title', 'YACHAY - Plataforma Educativa'); ?></title>

    <link rel="icon" type="image/png" href="/images/favicon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;600;700;800;900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="/css/variables.css">
    <link rel="stylesheet" href="/css/base.css">
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/hero.css">
    <link rel="stylesheet" href="/css/footer.css">
    <link rel="stylesheet" href="/css/responsive.css">

    
    <link rel="stylesheet" href="/css/dark-mode.css">

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>


<body>

    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main style="margin-top: 70px;">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <button id="scrollTop" class="scroll-top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script src="/js/main.js"></script>

    
    <script src="/js/mode-toggle.js"></script>

    <?php echo $__env->yieldPushContent('scripts'); ?>

</body>

</html>
<?php /**PATH C:\xampp\htdocs\laravel\resources\views/layouts/app.blade.php ENDPATH**/ ?>