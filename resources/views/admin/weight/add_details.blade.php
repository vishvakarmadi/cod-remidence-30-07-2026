@extends('admin.admin_layouts')
@section('admin_content')
<style>
    textarea {
  width: 100%;
  height: 100px;
  padding: 12px 20px;
  box-sizing: border-box;
  border: 2px solid #ccc;
  border-radius: 4px;
  background-color: #f8f8f8;
  font-size: 16px;
  resize: none;
}
input[type="file"]{
  /* display: none; */
}
.custom-file-upload {
  border: 1px solid #ccc;
  display: inline-block;
  padding: 6px 12px;
  cursor: pointer;
}
</style>
@php 
$user_id = Auth::guard('admin')->user()->id;
@endphp
<div class="block-header">
            <div class="row">
                <div class="col-lg-5 col-md-8 col-sm-12">
                    <h2>Add Details</h2>
                </div>
            </div>
        </div>
<div class="card pt-30 ratecard">
    <!-- <div class="card-header">
    </div> -->
    <div class="card-body">
        <form class="product-form" action="{{ route('admin.weight.storedetails') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col-md-12">
            <div class="row">
                <input type='hidden' name='order_id' value="{{$order->id}}">
            <div class="form-group  col-md-10">
                <label for="">Description *</label>
                <textarea name="description" required></textarea>
            </div>
            <?php for($i=1;$i<5;$i++){ ?>
            <div class="form-group col-md-6">
                <label for="inputTag{{$i}}">
                   <!-- <br/> -->
                    <i class="custom-file-upload" > Select Image {{$i}}</i>
                    <input id="inputTag{{$i}}" type="file" name="photo{{$i}}" accept="image/*"/>
                    <span id="imageName{{$i}}"></span>
                </label>
                <script>
                    let input{{$i}} = document.getElementById("inputTag{{$i}}");
                    let imageName{{$i}} = document.getElementById("imageName{{$i}}")

                    input{{$i}}.addEventListener("change", ()=>{
                        let inputImage{{$i}} = document.querySelector("#inputTag{{$i}}").files[0];

                        imageName{{$i}}.innerText = inputImage{{$i}}.name;
                    })
                </script>
            </div>
            
            <?php } ?>  
                </div>
                </div>
            <div class="col-4">
                <button type="submit" class="btn-primary btn w-100">Submit</button>
            </div>
        </form>
    </div>
</div>
  
@endsection
