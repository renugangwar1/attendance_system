@extends('admin.layout')
@section('content')

<div class="container-fluid mt-4">

    <h2>Faculty List</h2>
    <table id="datatable" class="table table-striped table-bordered display nowrap w-100">

        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile Number</th>
                <th>Employee ID</th>
                <th>Department</th>
                <th>Designation</th>
                <th>Address</th>
                <th>Image</th>


            </tr>
        </thead>
        <tbody>
            @foreach($faculties as $faculty)
            <tr>
                <td>{{ $faculty->id }}</td>
                <td>{{ $faculty->name }}</td>
                <td>{{ $faculty->email }}</td>
                <td>{{ $faculty->mobile }}</td>
                <td>{{ $faculty->employee_id }}</td>
                <td>{{ $faculty->department }}</td>
                <td>{{ $faculty->designation }}</td>
                <td>{{ $faculty->address }}</td>
                <td>
                    @if($faculty->image && file_exists(public_path('uploads/' . $faculty->image)))
                    <img src="{{ asset('uploads/' . $faculty->image) }}" alt="Faculty Image" width="60" height="60">
                    @else
                    N/A
                    @endif
                </td>
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
    $('#datatable').DataTable({
        responsive: true,
        autoWidth: false
    });
});
</script>

@endsection