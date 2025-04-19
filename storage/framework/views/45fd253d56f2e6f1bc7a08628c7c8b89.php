<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title inertia><?php echo e(config('app.name', 'Internal23Watts')); ?></title>
    <link rel="stylesheet" href="<?php echo e(url('assets/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(url('assets/plugins/simple-ine/css/simple-line-icons.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(url('assets/css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('assets/css/custom.css')); ?>">
    <?php if (!isset($__inertiaSsrDispatched)) { $__inertiaSsrDispatched = true; $__inertiaSsrResponse = app(\Inertia\Ssr\Gateway::class)->dispatch($page); }  if ($__inertiaSsrResponse) { echo $__inertiaSsrResponse->head; } ?>
</head>

<body class="">
    <div class="wrapper <?php if(Auth::check()): ?> user-<?php echo e(Auth::user()->user_type); ?> <?php endif; ?> ">
        <!-- <div class="page-menu">
            <?php echo $__env->make('page_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div> -->
        <div class="main">
            <?php echo $__env->make('page_header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="content">
                <?php if (!isset($__inertiaSsrDispatched)) { $__inertiaSsrDispatched = true; $__inertiaSsrResponse = app(\Inertia\Ssr\Gateway::class)->dispatch($page); }  if ($__inertiaSsrResponse) { echo $__inertiaSsrResponse->body; } else { ?><div id="app" data-page="<?php echo e(json_encode($page)); ?>"></div><?php } ?>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var base_url = "<?php echo e(url('/')); ?>";
    </script>
    <script src="<?php echo e(url('assets/js/jquery-3.7.1.slim.min.js')); ?>"></script>
    <script src="<?php echo e(url('assets/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(url('assets/js/bootbox.min.js')); ?>"></script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>
</body>

</html>
<?php /**PATH /var/www/html/zotel_ai/resources/views/app.blade.php ENDPATH**/ ?>