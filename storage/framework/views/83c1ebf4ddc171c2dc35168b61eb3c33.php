<div class="text-center">
    <div class="side-logo">
        <?php if(Session::get('sidebar_logo')): ?>
        <img src="<?php echo e(Session::get('sidebar_logo')); ?>" />
        <?php endif; ?>
    </div>
</div>
<?php if(!isset($sidebar)) $sidebar = ""; $menu = "admin"; ?>
<ul>
    <?php echo $__env->make("menus.".$menu, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</ul><?php /**PATH /Applications/MAMP/htdocs/zotel_ai/resources/views/page_menu.blade.php ENDPATH**/ ?>