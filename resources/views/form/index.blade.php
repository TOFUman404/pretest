@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">รายการสินค้า</div>

                <div class="card-body">
                    <div>
                        <a href="{{ route('product.add') }}" class="btn btn-success float-right">เพิ่มสินค้า</a>
                    </div>
                    <div class="table-responsive pt-2">
                        <table class="table nowrap" id="product-table" style="width: 100%">
                            <thead>
                            <tr class="text-center">
                                <th>รูปสินค้า</th>
                                <th>ชื่อสินค้า</th>
                                <th>รหัสสินค้า</th>
                                <th>จำนวนสินค้า</th>
                                <th>พร้อมขาย</th>
                                <th>จัดการ</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            genTable();
            function genTable(filterData) {
                var table = $('#product-table').DataTable({
                    'order': [[1, 'asc']],
                    'columnDefs': [
                        { 'orderable': false, 'targets': 0 },
                        { 'targets': 0, 'width': '10%' },
                        { 'targets': 1, 'width': '20%' },
                        { 'targets': 2, 'width': '10%' },
                        { 'targets': 3, 'width': '5%' },
                        { 'targets': 4, 'width': '5%' },
                        { 'targets': 5, 'width': '10%' },
                    ],
                    'stateSave': true,
                    'processing': true,
                    'serverSide': true,
                    'searching': true,
                     'ajax': {
                        'url': '{{ route('product.list') }}',
                        'type': 'get'
                     },
                    'columns': [
                        { 'data': 'image_path' , 'orderable': false, 'searchable': false , 'className': 'text-center' },
                        { 'data': 'name' },
                        { 'data': 'code' },
                        { 'data': 'stock', 'className': 'text-center' },
                        { 'data': 'available', 'className': 'text-center' },
                        { 'data': 'action' , 'orderable': false, 'searchable': false , 'className': 'text-center'}
                    ],
                })
            }
        });

        function deleteProduct(id) {
            if (confirm('Are you sure you want to delete this product?')) {
                $.ajax({
                    url: '{{ route('product.delete') }}',
                    type: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function (data) {
                        if (data.status == 'success') {
                            alert('Product deleted successfully');
                            location.reload();
                        } else {
                            alert('Something went wrong');
                        }
                    }
                });
            }
        }
    </script>
@endsection
