@extends('components.layout')

@section('title')
Add Trainees
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('css/styleCourse.css') }}">
@endsection

@section('content')
<div class="form-container">
    <h2 class="form-title">Add New Trainees</h2>

    <!-- Success Message (Hidden by Default) -->
    @if(session('success'))
    <div class="popup" id="successPopup">
        <p id="successMessage">{{ session('success') }}</p>
        <button onclick="closePopup()">OK</button>
    </div>
    @endif

    <h2>Add Trainee for Course ID: {{ $courseId }}</h2>

    <form method="POST" action="{{ route('trainees.store') }}">
        @csrf
        <input type="hidden" name="course_id" value="{{ $courseId }}">

        <div class="form-group">
            <label for="rim_id">RIM ID:</label>
            <input type="text" id="rim_id" name="rim_id" placeholder="Ex(4025)..." required>
            
            @if ($errors->has('rim_id'))
                <div class="error-message" style="color: red;">
                    {{ $errors->first('rim_id') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="Your Name..." required>
        </div>

        <div class="form-group">
            <label for="cid">CID:</label>
            <input type="text" id="cid" name="cid" placeholder="Your CID..." required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" placeholder="Your Email Address..." required>
            
            @if ($errors->has('email'))
                <div class="error-message" style="color: red;">
                    {{ $errors->first('email') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="contact_no">Contact:</label>
            <input type="text" id="contact_no" name="contact_no" placeholder="Your Contact Number..." required>
            
            @if ($errors->has('contact_no'))
                <div class="error-message" style="color: red;">
                    {{ $errors->first('contact_no') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="emergency_contact">Emergency Contact:</label>
            <input type="text" id="emergency_contact" name="emergency_contact" placeholder="Your Emergency Contact Number..." required>
            
            @if ($errors->has('emergency_contact'))
                <div class="error-message" style="color: red;">
                    {{ $errors->first('emergency_contact') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" placeholder="Your Address..." required>
        </div>

        <!-- Buttons -->
        <div class="button-group">
            <button type="submit" class="btn btn-save" onclick="return confirm('Are you sure you want to Add this Trainee?')">💾 Save</button>
            <a href="{{ url('/trainees') }}" class="btn btn-cancel">❌ Cancel</a>
        </div>
    </form>
</div>

<!-- JavaScript for Success Popup -->
<script>
    function closePopup() {
        document.getElementById("successPopup").style.display = "none";
    }

    // Show the popup if there is a success message
    document.addEventListener("DOMContentLoaded", function () {
        let popup = document.getElementById("successPopup");
        if (popup) {
            popup.style.display = "block";
        }
    });
</script>
@endsection
