<div class="row mt-3">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity In Stock</th>
                <th>Price Per Item</th>
                <th>Submitted Datetime</th>
                <th>Total Value Number</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @php $sum = 0; @endphp
            @foreach($results as $key => $value)
                @php
                    $total = $value['qty'] * $value['price']; 
                    $sum = $sum + $total; 
                @endphp
                <tr>
                    <td>{{$value['product_name']}}</td>
                    <td>{{$value['qty']}}</td>
                    <td>{{$value['price']}}</td>
                    <td>{{$value['created_at']}}</td>
                    <td>{{$total}}</td>
                    <td>
                        <button class="btn btn-info edit" id="{{$key}}">Edit</button>
                    </td>
                </tr>
            @endforeach

            @if(count($results) > 0)
                <tr>
                    <td colspan="4">
                        <div class="d-flex justify-content-end mx-1">
                            <b>{{$sum}}</b>
                        </div>
                    </td>
                    <td></td>
                </tr>
            @else
                <tr>
                    <td colspan="5">
                        <center>
                            No Record Found!
                        </center>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>