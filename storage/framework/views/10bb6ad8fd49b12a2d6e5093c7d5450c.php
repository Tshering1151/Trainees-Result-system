
<?php $__env->startSection('title', 'Upload Trainees'); ?>
<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <h2 class="text-3xl font-bold text-center mb-6">
        Upload Trainees for Course ID: <?php echo e($course_id); ?>

    </h2>
<!-- Flash Messages -->
<?php if(session('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" id="successMessage">
        <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" id="errorMessage">
        <?php echo e(session('error')); ?>

    </div>
<?php endif; ?>

<!-- Detailed Results Section -->
<?php if(session('show_details')): ?>
    <?php
        $uploadResults = session('upload_results');
    ?>

    <!-- Existing Trainees Section -->
    <?php if(!empty($uploadResults['existing_trainees'])): ?>
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
            <h3 class="text-xl font-semibold text-yellow-800 mb-3">
                Existing Trainees (<?php echo e(count($uploadResults['existing_trainees'])); ?>)
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full bg-white shadow-md rounded-lg">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">RIM ID</th>
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Email</th>
                            <th class="px-4 py-2 text-left">Existing Record Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $uploadResults['existing_trainees']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $existingTrainee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="border-b">
                                <td class="px-4 py-2"><?php echo e($existingTrainee['data'][0]); ?></td>
                                <td class="px-4 py-2"><?php echo e($existingTrainee['data'][1]); ?></td>
                                <td class="px-4 py-2"><?php echo e($existingTrainee['data'][3]); ?></td>
                                <td class="px-4 py-2 text-yellow-700">
                                    <?php if($existingTrainee['existing_record']): ?>
                                        Already in database with RIM ID: <?php echo e($existingTrainee['existing_record']->rim_id); ?>

                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <!-- Invalid Trainees Section -->
    <?php if(!empty($uploadResults['invalid_trainees'])): ?>
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
            <h3 class="text-xl font-semibold text-red-800 mb-3">
                Invalid Trainees (<?php echo e(count($uploadResults['invalid_trainees'])); ?>)
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full bg-white shadow-md rounded-lg">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">RIM ID</th>
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Validation Errors</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $uploadResults['invalid_trainees']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invalidTrainee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="border-b">
                                <td class="px-4 py-2"><?php echo e($invalidTrainee['data'][0]); ?></td>
                                <td class="px-4 py-2"><?php echo e($invalidTrainee['data'][1]); ?></td>
                                <td class="px-4 py-2 text-red-700">
                                    <ul class="list-disc list-inside">
                                        <?php $__currentLoopData = $invalidTrainee['errors']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<!-- Upload Container -->
<div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
    <!-- Download Template -->
    <div class="mb-4">
        <a href="<?php echo e(route('trainee.download_template')); ?>" 
           class="w-full block text-center bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-300">
            Download CSV Template
        </a>
    </div>

    <!-- Upload Form -->
    <form 
        action="<?php echo e(route('trainee.upload_csv', $course_id)); ?>" 
        method="POST" 
        enctype="multipart/form-data" 
        class="space-y-4"
    >
        <?php echo csrf_field(); ?>
        <input type="hidden" name="course_id" value="<?php echo e($course_id); ?>">

        <div>
            <label for="csv_file" class="block text-gray-700 font-bold mb-2">
                Select CSV File to Upload
            </label>
            <input 
                type="file" 
                name="csv_file" 
                id="csv_file" 
                required 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
        </div>

        <button 
            type="submit" 
            class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded transition duration-300"
        >
            Upload CSV
        </button>
    </form>
</div>
</div>
<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-xl text-center">
        <p id="modal-message" class="mb-4 text-lg"></p>
        <button 
            onclick="closeModal()" 
            class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded"
        >
            OK
        </button>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
    function showModal(message) {
        document.getElementById("modal-message").innerText = message;
        document.getElementById("successModal").classList.remove('hidden');
        document.getElementById("successModal").classList.add('flex');
        
        // Hide the original success message div
        const successMessageEl = document.getElementById("successMessage");
        if (successMessageEl) {
            successMessageEl.style.display = "none";
        }
    }

    function closeModal() {
        document.getElementById("successModal").classList.remove('flex');
        document.getElementById("successModal").classList.add('hidden');
    }

    // Show success message popup on page load
    document.addEventListener("DOMContentLoaded", function () {
        const successMessageEl = document.getElementById("successMessage");
        if (successMessageEl) {
            showModal(successMessageEl.innerText);
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('components.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Demo-project\resources\views/upload_trainees.blade.php ENDPATH**/ ?>