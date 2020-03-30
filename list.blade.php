@extends('admin.layouts.app')
@section('content')
<div class="br-pagebody">
    <div class="container-fluid pt-2">
        <div class="row row-sm justify-content-center new">
            <div class="col-12 col-sm-10 col-md-12 col-lg-12">
                <!-- <h4 class="tx-13 tx-uppercase tx-white tx-medium tx-spacing-1 mb-4">Vendor Product Listing</h4> -->
                <div class="card bg-white border-0 shadow-sm py-5 px-4">
                    <div class="bd bd-gray-300 rounded table-responsive">
                        <table class="table mg-b-0">
                            <thead>
                                <tr>
                                    <th class="wd-5p">#</th>
                                    <th class="wd-10p">NAME</th>
                                    <th class="wd-15p">DCIN</th>
                                    <th class="wd-10p">Image</th>
                                    <th class="wd-10p">PRICE</th>
                                    <th class="wd-10p">MRP</th>
                                    <th class="wd-10p">BRAND</th>
                                    <th class="wd-10p">CATEGORY</th>
                                    <th class="wd-10p">Vendor</th>
                                    <th class="wd-10p">Store99</th>
                                  
                                    <th class="wd-20p">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $i= 0;
                                @endphp
                                @foreach ($products as $key=> $item)
                                <tr>
                                    <td scope="row">{{++$i}}</td>
                                    <td scope="row">
                                        {{$item->name}} <br />
                                    </td>
                                    <td scope="row">{{$item->dcin}}</td>
                                    <td>
                                        @if($item->images->first())
                                        @if(file_exists($item->images->first()->image))
                                        <a target="_blank" href="{{asset($item->images->first()->image)}}"><img src="{{asset($item->images->first()->image)}}" style="width:80px;"></a>
                                        @endif
                                        @endif
                                    </td>
                                    <td scope="row">{{$item->price}}</td>
                                    <td scope="row">{{$item->mrp}}</td>
                                    <td scope="row">
                                        @if($item->brand)
                                        {{$item->brand->name}}
                                        @else
                                        {{$item->other_brand}}
                                        @endif
                                    </td>
                                    <td scope="row">{{$item->category->name}}</td>
                                    <td scope="row">{{$item->vendor->company_name}}</td>

                                    @if($item->store_99 =='1')  
                                    <td scope="row"> <span>
                                            <a class="btn btn-sm btn-success"  title=" Already  Added Store 99 product"> 
                                            <i class="menu-item-icon fa fa-product-hunt"></i>
                                            </a>
                                        </span></td>
                                    @else
                                    <td scope="row"> <span>
                                            <a class="btn btn-sm btn-warning" onclick="return confirm('Are you sure you want to Move  this product on Store 99?');"  href="{{route('store99',['id'=>$item->id])}}" title="Move Store 99 product">
                                            <i class="menu-item-icon fa fa-paper-plane"></i>
                                            </a>
                                        </span></td>
                                    @endif

                                  

                                    <td scope="row">
                                        <span>
                                            <!-- <a class="btn btn-sm btn-success" href="{{route('showPendingProductAdmin',['product'=>$item->id])}}" title="edit product" > 
                                                <i class="fa fa-edit"></i>
                                            </a> -->
                                        </span>
                                        <span>
                                            <!-- <a class="btn btn-sm btn-success" href="{{route('showNewAdminvariant',['product'=>$item->id])}}" title="add variant">
                                                <i class="fa fa-copy"></i>
                                            </a> -->
                                        </span>
                                        <span>
                                            <a class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?');"  href="{{route('deleteProduct',['id'=>$item->id])}}" title="delete product">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="dataTables_paginate paging_simple_numbers" id="datatable1_paginate">
                            {{ $products->appends(['search' => $search])->links() }}
                        </div>
                    </div>
                </div>
                <!-- bd -->
            </div>
        </div>
    </div><!-- row -->
</div>
</div><!-- br-pagebody -->
@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function(e) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>
@endsection