@extends('backend.layouts.app')
@section('content')
<style>
    .pagination-container {
        display: flex;
        justify-content: end;
        margin-top: 20px;
    }
    .pagination-container svg {
        width: 30px;
    }
    .pagination-container nav .justify-between {
        display: none;
    }
    .no-records-found {
        text-align: center;
        color: red;
        margin-top: 20px;
        font-size: 18px;
        display: none;
    }
    .add-button {
        margin-right: 15px;
    }
</style>

<div class="content-wrapper page_content_section_hp">
    <div class="container-xxl">
        <div class="client_list_area_hp">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Item</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form id="item-form" action="{{ route('update.item') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $Intemdetails->id }}">
                        <input type="hidden" name="item_id" value="{{ $Intemdetails->item_id }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="item_name" class="form-label">Item Name</label>
                                    <input type="text" name="item_name" class="form-control" placeholder="Enter Item Name" id="item_name" value="{{ $Intemdetails->item_name }}">
                                    <span class="text-danger">
                                        @error('item_name') {{ $message }} @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="item_name" class="form-label">Item category</label>
                                    <input type="text" name="item_category" class="form-control" placeholder="Enter Item Name" id="item_name" value="{{ $Intemdetails->category }}" readonly>
                                    <span class="text-danger">
                                        @error('item_category') {{ $message }} @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="item_service" class="form-label">Item service</label>
                                    <input type="text" name="item_service" class="form-control" placeholder="Enter Item Name" id="item_name" value="{{ $Intemdetails->service }}" readonly>
                                    <span class="text-danger">
                                        @error('item_service') {{ $message }} @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="item_price" class="form-label">Item price</label>
                                    <input type="text" name="item_price" class="form-control" placeholder="Enter Item Name" id="item_name" value="{{ $Intemdetails->price }}">
                                    <span class="text-danger">
                                        @error('item_price') {{ $message }} @enderror
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-footer text-right">
                    <button type="submit" form="item-form" id="item-save" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
