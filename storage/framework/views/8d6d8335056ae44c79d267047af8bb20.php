<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<!-- Alpine.js for interactive components -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<title><?php echo e($title ?? config('app.name')); ?></title>

<link rel="icon" href="/favicon.ico?v=<?php echo e(time()); ?>" type="image/x-icon">
<link rel="icon" href="/favicon.png?v=<?php echo e(time()); ?>" type="image/png">
<link rel="apple-touch-icon" href="/favicon.png?v=<?php echo e(time()); ?>">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

<?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
<?php echo app('flux')->fluxAppearance(); ?><?php /**PATH C:\www\shark\resources\views/partials/head.blade.php ENDPATH**/ ?>