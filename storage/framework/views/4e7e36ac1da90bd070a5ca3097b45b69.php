

<?php $__env->startSection('title'); ?>
Admin Dashboard
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/styleHome.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <span class="close-btn" onclick="closeSidebar()">×</span>
    <a href="#" class="active">Dashboard</a>
    <a href="<?php echo e(url('/coursedashboard')); ?>">Courses</a>
    <a href="<?php echo e(url('/unit')); ?>">Units</a>
    <a href="<?php echo e(url('/trainees')); ?>">Trainees</a>
    <a href="<?php echo e(url('/result')); ?>">Results</a>
    <a href="#">Reports</a>
</aside>

<!-- Main Content -->
<div class="content">
    <button class="open-sidebar-btn" onclick="openSidebar()">☰ Open Sidebar</button>
</div>

<!-- Page Title -->
<h2 class="page-title">Trainees Result Management System</h2>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('components.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Demo-project\resources\views/admin.blade.php ENDPATH**/ ?>