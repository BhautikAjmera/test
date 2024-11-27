<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        
        <div class="container-fluid mt-4">
            <div class="row">
                <div class="col-sm-5">
                    <div class="alert alert-danger d-none" id="errorMessage"></div>
                </div>    
            </div>
            
            <div class="row">
                <div class="col-sm-5">
                    <div class="alert alert-success d-none" id="succesMessage"></div>
                </div>
            </div>

            <div class="row">
                <form method="post" id="frmSaveProduct" action="#">
                    @csrf
                    <input type="hidden" name="productId" id="productId" />

                    <div class="row mb-3">
                        <label for="productName" class="col-sm-2 col-form-label">Product Name<span class="text text-danger">*</span></label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="productName" id="productName" placeholder="Enter Product Name" autocomplete="off" required />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="qty" class="col-sm-2 col-form-label">Quantity In Stock<span class="text text-danger">*</span></label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control onlynumeric" name="qty" id="qty" placeholder="Enter Quantity" autocomplete="off" maxlength="5" required />
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="price" class="col-sm-2 col-form-label">Price Per Item<span class="text text-danger">*</span></label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control onlynumeric" name="price" id="price" placeholder="Enter Price" autocomplete="off" maxlength="5" required />
                        </div>
                    </div>

                    <input type="submit" class="btn btn-success" id="saveProduct" value="Save" />
                </form>
            </div>
            
            <div id="renderTable">
                @include('table')
            </div>
        </div>
    </body>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        $(document).ready(function(){
            var host = "{{URL::to('/')}}";
            
            $(".onlynumeric").keypress(function (e) {
                var keyCode = e.keyCode || e.which;
                var regex = /^[0-9]+$/;
                return  regex.test(String.fromCharCode(keyCode));
            });
            
            $(document).on("click","#saveProduct",function(e) {
                e.preventDefault();
                $('#errorMessage').addClass('d-none');
                $('#errorMessage').text('');


                var productName = $('#productName').val();
                var qty         = $('#qty').val();
                var price       = $('#price').val();
                var productId   = $('#productId').val();

                if(productName == '' || qty == '' || price == ''){
                    displayError('Please fill all required information to save product information.');
                    return false;
                }
                
                $.ajax({
                    type: "POST",
                    url: "{{ route('product.store') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        productName:productName, 
                        qty:qty, 
                        price:price,
                        productId:productId,
                    },success: function(response) {
                        if(response.success == true){
                            $("#frmSaveProduct").get(0).reset();

                            displaySuccess(response.message);
                            setTimeout(function () {
                                resetSuccess();
                            },2000);

                            $('#renderTable').html(response.data);
                            $('#saveProduct').val('Save');
                        }else{
                            displayError(response.message);
                            setTimeout(function () {
                                resetError();
                            },2000);
                        }
                    }
                });
            });

            function resetSuccess(){
                $('#succesMessage').addClass('d-none');
                $('#succesMessage').text('');
            }

            function resetError(){
                $('#errorMessage').addClass('d-none');
                $('#errorMessage').text('');
            }

            function displaySuccess(message){
                $('#succesMessage').removeClass('d-none');
                $('#succesMessage').text(message);
            }

            function displayError(message){
                $('#errorMessage').removeClass('d-none');
                $('#errorMessage').text(message);
            }

            $(document).on("click",".edit",function() {
                var id = $(this).attr('id');
                
                $.ajax({
                    type: "GET",
                    url: "{{ route('product.edit') }}",
                    data: {id:id},
                    success: function(response) {
                        $('#productName').val(response.data.product_name);
                        $('#qty').val(response.data.qty);
                        $('#price').val(response.data.price);
                        $('#productId').val(id);
                        $('#saveProduct').val('Update');
                    }
                });
            });
        });
    </script>
</html>
