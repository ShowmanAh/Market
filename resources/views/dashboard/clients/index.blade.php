@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @lang('site.clients')

            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('/dashboard/index') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard') </a></li>
                <li class="active">@lang('site.clients')</li>
            </ol>
        </section>

        <!-- Main content -->

        <!-- /.content -->
        <section class="content">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title" style="margin-bottom: 20px"> @lang('site.clients')<small>{{ $clients->total() }}</small></h3>
                    <!-- search data -->
                    <form action="{{ route('dashboard.clients.index') }}" method="get">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="@lang('site.search')" value="{{ request()->search }}" >

                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>@lang('site.search')</button>
                                <!-- check  create client permission -->
                                @if (auth()->user()->hasPermission('create_clients'))
                                    <a href="{{ route('dashboard.clients.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>@lang('site.add')</a>
                                @else
                                    <a href="#" class="btn btn-primary"><i class="fa fa-plus disabled"></i>@lang('site.add')</a>
                                @endif


                            </div>
                        </div>
                    </form>


                </div><!-- end of box header -->
                <div class="box-body">
                    <!-- check client counts-->
                    @if($clients->count() > 0)
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('site.name')</th>
                                <th>@lang('site.phone')</th>
                                <th>@lang('site.address')</th>
                                <th>@lang('site.add_order')</th>
                                <th>@lang('site.action')</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($clients as $index=>$client)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $client->name }}</td>
                                    <!-- implode() get array and converter to string and put delimiter '-' between item and next item -->
                                    <!-- array_filter() looping on array and remove any thing with null and false and 0 -->
                                    <td>{{ is_array($client->phone) ? implode(array_filter( $client->phone ), '-') : $client->phone }}</td>
                                    <td>{{ $client->address }}</td>
                                    <td>
                                        <!-- check  create orders client permission -->
                                    @if (auth()->user()->hasPermission('create_orders'))
                                        <a href="{{ route('dashboard.clients.orders.create', $client->id) }}" class="btn btn-info btn-sm">@lang('site.add_order')</a>
                                    @else
                                        <a href="#" class="btn btn-info btn-sm disabled">@lang('site.add_order')</a>

                                    @endif


                                    <td>
                                        <!-- check  update client permission -->
                                        @if (auth()->user()->hasPermission('update_clients'))
                                            <a class="btn btn-info sm" href=" {{ route('dashboard.clients.edit', $client->id) }}" > <i class="fa fa-edit"></i>@lang('site.edit')</a>
                                        @else
                                            <a class="btn btn-info sm disabled" href=" #" >@lang('site.edit')</a>
                                        @endif
                                    <!-- check  delete client permission -->

                                        @if (auth()->user()->hasPermission('delete_clients'))
                                            <form  action="{{ route('dashboard.clients.destroy',$client->id) }}" method="post" style="display: inline-block">
                                                {{csrf_field()}}
                                                {{method_field('delete')}}
                                                <button type="submit" class="btn btn-danger delete"><i class="fa fa-trash"></i>@lang('site.delete')</button>

                                            </form><!-- end form -->
                                        @else
                                            <button type="submit" class="btn btn-danger btn-sm disabled"><i class="fa fa-trash"></i>@lang('site.delete')</button>
                                        @endif

                                    </td>

                                </tr>
                            @endforeach
                            </tbody><!-- end tbody -->

                        </table><!-- end of table -->
                        <!--pagination link -->
                        <!--appends prevent guery search deleted from url -->

                        {{ $clients->appends(request()->query())->links() }}

                    @else
                        <h2>@lang('site.no_data_found')</h2>
                    @endif
                </div><!-- end of box body -->
            </div><!-- end of box  -->

        </section>
    </div>


@endsection