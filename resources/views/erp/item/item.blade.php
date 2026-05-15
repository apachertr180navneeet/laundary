@extends('erp.layouts.app')
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
        display: none; /* Hidden by default */
    }

    .add-button {
        margin-right: 15px;
    }

</style>
<div class="content-wrapper page_content_section_hp">
    <div class="container-xxl">
        <div class="client_list_area_hp">
            <div class="card">
                <div class="card-body">
                    <div class="client_list_heading_area">
                        <h4>Item List</h4>
                        {{-- <div class="client_list_heading_search_area">
                            <i class="menu-icon tf-icons ti ti-search"></i>
                            <input type="search" id="itemSearch" class="form-control" placeholder="Searching ...">
                        </div> --}}
                        <a href="{{ route('add.items') }}" class="btn btn-success add-button">+ ADD</a>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead class="table_head_1f446E">
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Item Name</th>
                                            <th>Category</th>
                                            <th>Service</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $serialNumber = 1;
                                        @endphp
                                        @foreach ($items as $item)
                                            <tr>
                                                <td>{{ $serialNumber++ }}</td>
                                                <td>{{ $item->item_name }}</td>
                                                <td>{{ $item->category }}</td>
                                                <td>{{ $item->service }}</td>
                                                <td>{{ $item->price }}</td>
                                                <td>
                                                    <div class="Client_table_action_area">
                                                        <a href="{{ url('/erp/edit-items/' . $item->id) }}" class="btn Client_table_action_icon px-2 edit_services_btn" data-id="{{ $item->id }}">
                                                            <i class="tf-icons ti ti-pencil"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="no-records-found">No records found related to your search.</div>
                                @if ($items->count() > 0)
                                    <div class="pagination-container">
                                        {{ $items->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


