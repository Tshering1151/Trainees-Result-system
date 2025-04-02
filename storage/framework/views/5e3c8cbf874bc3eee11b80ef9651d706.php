<?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr>
    <td><?php echo e($loop->iteration); ?></td>
    <td><?php echo e($course->course_id); ?></td>
    <td><?php echo e($course->course_name); ?></td>
    <td><?php echo e($course->start_year); ?></td>
    <td><?php echo e($course->end_year); ?></td>
    <td><?php echo e($course->duration); ?> Months</td>
    <td><?php echo e($course->total_term); ?></td>
    <td>
        <a href="<?php echo e(route('courses.edit', $course->course_id)); ?>" class="btn btn-edit">✏️ Edit</a>
        <button type="submit" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this course?')">🗑 Delete</button>
    </td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH C:\xampp\htdocs\Demo-project\resources\views/partials/course_rows.blade.php ENDPATH**/ ?>