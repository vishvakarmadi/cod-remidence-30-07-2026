<div class="col-md-12">
    <form class="product-form" action="{{ route('import.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-header">
                <h4>Import Order File</h4>
            </div>
            <div class="card card-body">
                <div class="form-group">
                    <div class="row">
                        <div class="form-group col-4">
                            <div class="image-upload">
                                <div class="thumb">
                                    <div class="avatar-edit">
                                        <input type="file" class="profilePicUpload" name="excel" id="profilePicUpload1" required>
                                        <label for="profilePicUpload1" class="bg-secondary text-white">Upload File</label>
                                        {{-- <small class="mt-2">Supported files: <b>xlsx</b>.</small> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <button type="submit" class="btn-primary btn h-45 w-100">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>