@extends('admin.layout')
@section('content')

<div class="container mt-4">
    <h2>Faculty List</h2>
    <table id="datatable" class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile Number</th>

                <th>Department</th>

                <th>Address</th>
                <th>Image</th>


            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td>{{ $student->id }}</td>
                <td>{{ $student->name }}</td>
                <td>{{ $student->email }}</td>
                <td>{{ $student->mobile }}</td>

                <td>{{ $student->department }}</td>

                <td>{{ $student->address }}</td>
                <td>{{ $student->image }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- DataTables Script --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#datatable').DataTable();
});
</script>

@endsection