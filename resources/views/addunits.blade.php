@extends('components.layout')

@section('title', 'Add Units')

@section('style')
<link rel="stylesheet" href="{{ asset('css/styleCourse.css') }}">
@endsection

@section('content')

<div class="content">
    <h2 class="page-title">Add Units for {{ $course->course_name }} (Term {{ $term }})</h2>

    <form action="{{ route('unit.save') }}" method="POST">
        @csrf
        <input type="hidden" name="course_id" value="{{ $course->course_id }}">
        <input type="hidden" name="term" value="{{ $term }}">

        <div id="unitList">
            <div class="unit-row">
                <input type="text" name="units[0][unit_name]" placeholder="Unit Name..." required>
                <input type="text" name="units[0][lecture]" placeholder="Lecture Name..." required>
                <input type="text" name="units[0][description]" placeholder="Unit Description..." required>
                <button type="button" class="add-unit-btn" onclick="addUnitRow()">➕ Add More</button>
            </div>
        </div>

        <button type="submit" class="btn btn-save">Save Units</button>
        <a href="{{ route('unit.index') }}" class="btn btn-cancel">Cancel</a>
    </form>
</div>

@endsection

@section('script')
<script>
    let unitIndex = 1;

    function addUnitRow() {
        document.getElementById("unitList").innerHTML += `
            <div class="unit-row">
                <input type="text" name="units[${unitIndex}][unit_name]" placeholder="Unit Name..." required>
                <input type="text" name="units[${unitIndex}][lecture]" placeholder="Lecture Name..." required>
                <input type="text" name="units[${unitIndex}][description]" placeholder="Unit Description..." required>
                <button type="button" onclick="this.parentElement.remove()">❌ Remove</button>
            </div>`;
        unitIndex++;
    }
</script>
@endsection
