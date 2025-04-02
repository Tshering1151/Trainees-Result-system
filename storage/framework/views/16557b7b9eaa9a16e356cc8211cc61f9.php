

<?php $__env->startSection('title', 'Trainees Results'); ?>

<?php $__env->startSection('style'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/styleCourse.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <span class="close-btn" onclick="closeSidebar()">×</span>
    <a href="<?php echo e(url('/admin')); ?>">Dashboard</a>
    <a href="<?php echo e(url('/coursedashboard')); ?>">Courses</a>
    <a href="<?php echo e(url('/unit')); ?>">Units</a>
    <a href="<?php echo e(url('/trainees')); ?>">Trainees</a>
    <a href="<?php echo e(url('/result')); ?>" class="active">Results</a>
    <a href="#">Reports</a>
</aside>

<!-- Main Content -->
<div class="content">
    <button class="open-sidebar-btn" onclick="openSidebar()">☰ Open Sidebar</button>

    <h2 class="page-title">Result Management</h2>

    <!-- Add Result Section -->
    <div class="top-actions">
        <button class="btn btn-primary" onclick="openAddResultOptionsModal()">➕ Add Result</button>
        <input type="text" id="searchUnits" onkeyup="searchUnits()" placeholder="🔍 Search Trainees Result by RIM ID..." class="search-box">
    </div>  

    <!-- Add Result Options Modal -->
    <div id="addResultOptionsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddResultOptionsModal()">×</span>
            <h2>Add Result Options</h2>
            <div class="option-buttons">
                <button class="btn btn-primary" onclick="openAddIndividualResultModal()">Add Individual Result</button>
                <button class="btn btn-primary" onclick="openAddBulkResultModal()">📂 Upload CSV Results</button>
            </div>
        </div>
    </div>

    <!-- Add Individual Result Modal -->
    <div id="addIndividualResultModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddIndividualResultModal()">×</span>
            <h2>Add Individual Result</h2>
            <form id="addResultForm">
                <?php echo csrf_field(); ?>
                <label for="course_id">Course ID:</label>
                <input type="text" id="course_id" name="course_id" required>
                <div id="courseError" class="error-message"></div>

                <label for="rim_id">RIM ID:</label>
                <input type="text" id="rim_id" name="rim_id" required>
                <div id="rimError" class="error-message"></div>

                <label for="term">Term:</label>
                <select id="term" name="term" disabled>
                    <option value="">Select Term</option>
                </select>

                <button type="button" id="submitResult" disabled onclick="validateAndRedirect()">Add Result</button>
            </form>
        </div>
    </div>
    
    <!-- Add Bulk Results Modal (CSV Upload) -->
    <div id="addBulkResultModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddBulkResultModal()">×</span>
            <h2>Upload CSV Results</h2>
            <form id="uploadCSVForm">
                <?php echo csrf_field(); ?>
                <label for="csv_course_id">Course ID:</label>
                <input type="text" id="csv_course_id" name="course_id" required>
                <div id="csvCourseError" class="error-message"></div>

                <label for="csv_term">Term:</label>
                <select id="csv_term" name="term" disabled>
                    <option value="">Select Term</option>
                </select>
                
                <button type="button" id="proceedToUploadButton" class="btn btn-primary" disabled onclick="validateAndRedirectCSV()">Proceed to Upload</button>
            </form>
        </div>
    </div>
    
</div>

<script>
    // Modal control functions
    function openAddResultOptionsModal() {
        document.getElementById("addResultOptionsModal").style.display = "block";
    }
    
    function closeAddResultOptionsModal() {
        document.getElementById("addResultOptionsModal").style.display = "none";
    }
    
    function openAddIndividualResultModal() {
        closeAddResultOptionsModal();
        document.getElementById("addIndividualResultModal").style.display = "block";
    }
    
    function closeAddIndividualResultModal() {
        document.getElementById("addIndividualResultModal").style.display = "none";
        clearErrors();
    }
    
    function openAddBulkResultModal() {
        closeAddResultOptionsModal();
        document.getElementById("addBulkResultModal").style.display = "block";
    }
    
    function closeAddBulkResultModal() {
        document.getElementById("addBulkResultModal").style.display = "none";
        clearCSVErrors();
    }

    function clearErrors() {
        document.getElementById('courseError').innerText = '';
        document.getElementById('rimError').innerText = '';
        document.getElementById("term").innerHTML = '<option value="">Select Term</option>';
        document.getElementById("term").disabled = true;
        document.getElementById("submitResult").disabled = true;
    }
    
    function clearCSVErrors() {
        document.getElementById('csvCourseError').innerText = '';
        document.getElementById("csv_term").innerHTML = '<option value="">Select Term</option>';
        document.getElementById("csv_term").disabled = true;
        document.getElementById("proceedToUploadButton").disabled = true;
    }

    // Add event listeners to validate on blur
    document.getElementById("course_id").addEventListener("blur", validateCourseId);
    document.getElementById("rim_id").addEventListener("blur", validateRimId);
    document.getElementById("csv_course_id").addEventListener("blur", validateCSVCourseId);
    
    // Event listener for term dropdown
    document.getElementById("term").addEventListener("change", checkAllInputs);
    document.getElementById("csv_term").addEventListener("change", checkCSVInputs);
    
    // Validation functions
    function validateCourseId() {
        let courseId = document.getElementById("course_id").value;
        
        if (!courseId) {
            document.getElementById("courseError").innerText = "Please fill in the Course ID";
            clearErrors();
            return;
        }

        fetchCourseDetails(courseId);
    }
    
    function validateCSVCourseId() {
        let courseId = document.getElementById("csv_course_id").value;
        
        if (!courseId) {
            document.getElementById("csvCourseError").innerText = "Please fill in the Course ID";
            clearCSVErrors();
            return;
        }

        fetchCSVCourseDetails(courseId);
    }

    function fetchCourseDetails(courseId) {
        fetch(`/course-details/${courseId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Course not found or server error");
                }
                return response.json();
            })
            .then(data => {
                if (data.exists) {
                    document.getElementById("courseError").innerText = "";
                    populateTermDropdown(data.total_term);
                } else {
                    document.getElementById("courseError").innerText = "Course ID not found";
                    clearErrors();
                }
            })
            .catch(error => {
                console.error("Error fetching course details:", error);
                document.getElementById("courseError").innerText = "Error fetching course details";
                clearErrors();
            });
    }
    
    function validateRimId() {
        let rimId = document.getElementById("rim_id").value;
        
        if (!rimId) {
            document.getElementById("rimError").innerText = "Please fill in the RIM ID";
            document.getElementById("submitResult").disabled = true;
            return;
        }
        
        fetch(`/validate-rim-id/${rimId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Server error");
                }
                return response.json();
            })
            .then(data => {
                if (data.exists) {
                    document.getElementById("rimError").innerText = "";
                    checkAllInputs();
                } else {
                    document.getElementById("rimError").innerText = "RIM ID not found";
                    document.getElementById("submitResult").disabled = true;
                }
            })
            .catch(error => {
                console.error("Error validating RIM ID:", error);
                document.getElementById("rimError").innerText = "Error validating RIM ID";
                document.getElementById("submitResult").disabled = true;
            });
    }
    
    function fetchCSVCourseDetails(courseId) {
        fetch(`/course-details/${courseId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Course not found or server error");
                }
                return response.json();
            })
            .then(data => {
                if (data.exists) {
                    document.getElementById("csvCourseError").innerText = "";
                    populateCSVTermDropdown(data.total_term);
                } else {
                    document.getElementById("csvCourseError").innerText = "Course ID not found";
                    clearCSVErrors();
                }
            })
            .catch(error => {
                console.error("Error fetching course details:", error);
                document.getElementById("csvCourseError").innerText = "Error fetching course details";
                clearCSVErrors();
            });
    }

    function populateTermDropdown(totalTerms) {
        let termDropdown = document.getElementById("term");
        termDropdown.innerHTML = '<option value="">Select Term</option>';

        for (let i = 1; i <= totalTerms; i++) {
            let option = document.createElement("option");
            option.value = i;
            option.textContent = `Term ${i}`;
            termDropdown.appendChild(option);
        }

        termDropdown.disabled = false;
        checkAllInputs();
    }
    
    function populateCSVTermDropdown(totalTerms) {
        let termDropdown = document.getElementById("csv_term");
        termDropdown.innerHTML = '<option value="">Select Term</option>';

        for (let i = 1; i <= totalTerms; i++) {
            let option = document.createElement("option");
            option.value = i;
            option.textContent = `Term ${i}`;
            termDropdown.appendChild(option);
        }

        termDropdown.disabled = false;
        checkCSVInputs();
    }
    
    function checkAllInputs() {
        let courseId = document.getElementById("course_id").value;
        let rimId = document.getElementById("rim_id").value;
        let term = document.getElementById("term").value;
        let courseError = document.getElementById("courseError").innerText;
        let rimError = document.getElementById("rimError").innerText;
        
        // Enable submit button only if all inputs are valid
        if (courseId && rimId && term && !courseError && !rimError) {
            document.getElementById("submitResult").disabled = false;
        } else {
            document.getElementById("submitResult").disabled = true;
        }
    }
    
    function checkCSVInputs() {
        let courseId = document.getElementById("csv_course_id").value;
        let term = document.getElementById("csv_term").value;
        let courseError = document.getElementById("csvCourseError").innerText;
        
        // Enable proceed button only if course ID and term are valid
        if (courseId && term && !courseError) {
            document.getElementById("proceedToUploadButton").disabled = false;
        } else {
            document.getElementById("proceedToUploadButton").disabled = true;
        }
    }
    
    function validateAndRedirect() {
        let courseId = document.getElementById("course_id").value;
        let rimId = document.getElementById("rim_id").value;
        let term = document.getElementById("term").value;
        
        if (!courseId) {
            document.getElementById("courseError").innerText = "Please fill in the Course ID";
            return;
        }
        
        if (!rimId) {
            document.getElementById("rimError").innerText = "Please fill in the RIM ID";
            return;
        }
        
        if (!term) {
            alert("Please select a Term");
            return;
        }
        
        // Save form data to session storage
        sessionStorage.setItem('result_data', JSON.stringify({
            course_id: courseId,
            rim_id: rimId,
            term: term
        }));
        
        // Redirect to add_result page with query parameters
        window.location.href = `<?php echo e(url('/add_result')); ?>?course_id=${courseId}&rim_id=${rimId}&term=${term}`;
    }
    
    function validateAndRedirectCSV() {
        let courseId = document.getElementById("csv_course_id").value;
        let term = document.getElementById("csv_term").value;
        
        if (!courseId) {
            document.getElementById("csvCourseError").innerText = "Please fill in the Course ID";
            return;
        }
        
        if (!term) {
            alert("Please select a Term");
            return;
        }
        
        // Save form data to session storage
        sessionStorage.setItem('csv_upload_data', JSON.stringify({
            course_id: courseId,
            term: term
        }));
        
        // Redirect to upload_result page with query parameters
        window.location.href = `<?php echo e(url('/upload_result')); ?>?course_id=${courseId}&term=${term}`;
    }
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('components.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Demo-project\resources\views/result.blade.php ENDPATH**/ ?>