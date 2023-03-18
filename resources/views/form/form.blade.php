@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    @if(Route::is('product.add'))
                        เพิ่มรายการสินค้า
                    @elseif(Route::is('product.edit'))
                        แก้ไขรายการสินค้า
                    @endif
                </div>

                <div class="card-body">
                    <form method="POST" id="product-form">
                        <div class="form-group">
                                <label for="product-name">ชื่อสินค้า</label>
                            <input type="text" name="name" class="form-control" id="product-name" placeholder="กรุณาใส่ชื่อรายการสินค้า" value="{{ $product->name ?? null }}" required>
                        </div>
                        <div class="form-group">
                            <label for="product-code">รหัสสินค้า</label>
                            <input type="text" name="code" class="form-control" id="product-code" placeholder="กรุณาใส่รหัสสินค้า" value="{{ $product->code ?? null }}" required>
                        </div>
                        <div class="form-group">
                            <label for="product-stock">จำนวนสินค้า</label>
                            <input type="number" name="stock" class="form-control" id="product-stock" placeholder="กรุณาใส่จำนวนสินค้าที่พร้อมขาย" value="{{ $product->stock ?? null }}" required>
                        </div>
                        <div class="input-group pt-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text">รูปสินค้า</span>
                            </div>
                            <div class="custom-file">
                                <input type="file" name="image" accept="image/png, image/jpeg" class="custom-file-input" id="product-img">
                                <label class="custom-file-label" for="product-img" id="product-img-filename">กรุณาเลือกไฟล์รูป</label>
                            </div>
                        </div>
                        <div class="col-md-4 pt-2 pb-4">
                            <img class="img-fluid" id='img-upload' @if(isset($product)) src="{{ Storage::url($product->image_path) }}" @endif />
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="available" value="" id="product-available"
                                @if(isset($product) && $product->available == 1)
                                    checked
                                @endif
                                >
                                <label class="form-check-label" for="invalidCheck2">
                                    พร้อมขาย
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $("#product-img").change(function() {
            let input = $(this), label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            $('#product-img-filename').text(label);
            readURL(this);
        });
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#img-upload').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#product-form').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData();
        @if(isset($product))
            formData.append('id', {{ $product->id ?? null }}); // if edit
        @endif
        formData.append('name', $('#product-name').val());
        formData.append('code', $('#product-code').val());
        formData.append('stock', $('#product-stock').val());
        if($('#product-img')[0].files[0]){
            formData.append('image', $('#product-img')[0].files[0]);
        }
        formData.append('available', $('#product-available').is(':checked') ? 1 : 0);
        formData.append('_token', '{{ csrf_token() }}');
        $.ajax({
            type: 'POST',
            url: '{{ route('product.save') }}',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                if (data.status ==='success') {
                    window.location.href = '{{ route('product.index') }}';
                }
            },
            error: function(data) {
                console.log(data);
            }
        })
    });
</script>
@endsection
