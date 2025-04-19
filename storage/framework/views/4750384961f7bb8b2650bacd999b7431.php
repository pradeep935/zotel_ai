<?php if(Auth::check()): ?>
    <div class="top-menu">
        <div class="row">
            <div class="col-sm-8 ">
                <ul class="main-menu">

                </ul>
            </div>
            <div class="col-sm-4" style="text-align: right;">
                <div class="welcome-nav">
                    <span class="name">
                        <?php echo e(Auth::user()->name); ?>

                    </span>
                    <div class="menu">
                        <ul>
                            <li>
                                <a href="<?php echo e(url('/update-password')); ?>"><i class="icons icon-lock-open"></i> <span>Change Password</span></a>
                            </li>

                            <li>
                                <a href="<?php echo e(url('/logout')); ?>"><i class="icons icon-logout"></i> <span>Logout</span></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/zotel-master/resources/views/page_header.blade.php ENDPATH**/ ?>