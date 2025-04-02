

<?php $__env->startSection('title'); ?>
Edit Course
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/styleCourse.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="form-container">
    <h2 class="form-title">Edit Course</h2>

    <!-- Popup Success Message -->
    <div class="popup" id="successPopup" style="display: none;">
        <p id="successMessage"></p> <!-- Success message is inserted here -->
        <button onclick="closePopup()">OK</button>
    </div>

    <form id="courseForm" action="<?php echo e(route('courses.update', $course->course_id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?> <!-- Use PUT method for update -->
        
        <label>Course Name:</label>
        <input type="text" name="course_name" value="<?php echo e(old('course_name', $course->course_name)); ?>" required>

        <label>Course ID:</label>
        <input type="text" name="course_id" value="<?php echo e(old('course_id', $course->course_id)); ?>" required>

        <label>Start Year:</label>
        <input type="number" name="start_year" value="<?php echo e(old('start_year', $course->start_year)); ?>" required>

        <label>End Year:</label>
        <input type="number" name="end_year" value="<?php echo e(old('end_year', $course->end_year)); ?>" required>

        <label>Duration (Months):</label>
        <input type="number" name="duration" value="<?php echo e(old('duration', $course->duration)); ?>" required>

        <label>Total Terms:</label>
        <input type="number" name="total_term" value="<?php echo e(old('total_term', $course->total_term)); ?>" required>

        <label>Description (optional):</label>
        <textarea name="description"><?php echo e(old('description', $course->description)); ?></textarea>

        <!-- Buttons -->
        <div class="button-group">
            <button type="submit" class="btn btn-save" onclick="return confirm('Are you sure you want to Update this course?')">💾 Update</button>
            <a href="<?php echo e(url('/coursedashboard')); ?>" class="btn btn-cancel">❌ Cancel</a>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $("#courseForm").submit(function(e) {
        e.preventDefault(); // Prevent normal form submission

        $.ajax({
            url: "<?php echo e(route('courses.update', $course->course_id)); ?>", // Correct URL for update
            method: "PUT", // PUT method for updates
            data: $(this).serialize(), // Send form data
            success: function(response) {
                // Check if response contains a success message
                if(response.message) {
                    // Set the message inside the popup
                    $("#successMessage").text(response.message);
                    // Show the popup
                    $("#successPopup").fadeIn();
                }
            },
            error: function(xhr) {
                alert("Something went wrong. Please try again.");
            }
        });
    });
});

// Function to close the popup
function closePopup() {
    $("#successPopup").fadeOut();
}
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('components.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Demo-project\resources\views/editCourse.blade.php ENDPATH**/ ?>