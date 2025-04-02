@foreach($courses as $course)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $course->course_id }}</td>
    <td>{{ $course->course_name }}</td>
    <td>{{ $course->start_year }}</td>
    <td>{{ $course->end_year }}</td>
    <td>{{ $course->duration }} Months</td>
    <td>{{ $course->total_term }}</td>
    <td>
        <a href="{{ route('courses.edit', $course->course_id) }}" class="btn btn-edit">✏️ Edit</a>
        <button type="submit" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this course?')">🗑 Delete</button>
    </td>
</tr>
@endforeach