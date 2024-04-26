<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
 
 function deleteMethod(nexturl, call_method = 'get') {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success mx-1',
                cancelButton: 'btn btn-danger mx-1'
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: are_you_sure,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: yes,
            cancelButtonText: no,
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $('#preloader').show();

                // Send DELETE request
                axios.delete(nexturl, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                        location.reload(); // Refresh the page or do any other action
                })
                .catch(error => {
                    location.reload(); // Refresh the page or do any other action
                });
            } else {
                result.dismiss === Swal.DismissReason.cancel
            }
        })
    }
</script>

<table class="table table-striped table-bordered py-3 zero-configuration w-100">
    <thead>
        <tr class="fw-500">
            <td>{{ trans('labels.id') }}</td>
            <td>{{ trans('labels.name') }}</td>
            <td>{{trans('labels.email')}}</td>
            <td>{{ trans('labels.type') }}</td>
            <td>{{ trans('labels.action') }}</td>
        </tr>
    </thead>
    <tbody>
        @php $i = 1;@endphp
        @foreach ($employees as $employee)
            <tr>
                <th>{{$employee->id}}</th>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->email }}</td>
                <td>{{ $employee->type_name }}</td>
               <th>
                    <a class="btn btn-sm btn-info btn-size" tooltip="{{trans('labels.edit')}}" data-original-title="" title=""
                        href="{{ route('employees.edit',['employee' => $employee->id]) }}"> 
                        <i class="fa-regular fa-pen-to-square"></i> 
                    </a>
                    <a class="btn btn-sm btn-danger btn-size" tooltip="{{ trans('labels.delete') }}"
                        onclick="deleteMethod('{{ route('employees.destroy', ['employee' => $employee->id]) }}')" >
                        <i class="fa-regular fa-trash"></i> 
                     </a>
                </th>
            </tr>
        @endforeach
    </tbody>
</table>
