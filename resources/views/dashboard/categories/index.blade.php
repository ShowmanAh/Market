@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @lang('site.categories')

            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('/dashboard/index') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard') </a></li>
                <li class="active">@lang('site.categories')</li>
            </ol>
        </section>

        <!-- Main content -->

        <!-- /.content -->
        <section class="content">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title" style="margin-bottom: 20px"> @lang('site.categories')<small>{{ $categories->total() }}</small></h3>
                    <!-- search data -->
                    <form action="{{ route('dashboard.categories.index') }}" method="get">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="@lang('site.search')" value="{{ request()->search }}" >

                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>@lang('site.search')</button>
                                <!-- check  create category permission -->
                                @if (auth()->user()->hasPermission('create_categories'))
                                    <a href="{{ route('dashboard.categories.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>@lang('site.add')</a>
                                @else
                                    <a href="#" class="btn btn-primary"><i class="fa fa-plus disabled"></i>@lang('site.add')</a>
                                @endif


                            </div>
                        </div>
                    </form>


                </div><!-- end of box header -->
                <div class="box-body">
                    <!-- check category counts-->
                    @if($categories->count() > 0)
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('site.name')</th>
                                <th>@lang('site.products_count')</th>
                                <th>@lang('site.related_products')</th>
                                <th>@lang('site.action')</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($categories as $index=>$category)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->products->count() }}</td>
                                    <td><a href="{{ route('dashboard.products.index', ['category_id' => $category->id]) }}"  class="btn btn-info btn-sm">@lang('site.related_products')</a></td>

                                    <td>
                                        <!-- check  update category permission -->
                                        @if (auth()->user()->hasPermission('update_categories'))
                                            <a class="btn btn-info sm" href=" {{ route('dashboard.categories.edit', $category->id) }}" > <i class="fa fa-edit"></i>@lang('site.edit')</a>
                                        @else
                                            <a class="btn btn-info sm disabled" href=" #" >@lang('site.edit')</a>
                                        @endif
                                    <!-- check  delete category permission -->

                                        @if (auth()->user()->hasPermission('delete_categories'))
                                            <form  action="{{ route('dashboard.categories.destroy',$category->id) }}" method="post" style="display: inline-block">
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

                        {{ $categories->appends(request()->query())->links() }}

                    @else
                        <h2>@lang('site.no_data_found')</h2>
                    @endif
                </div><!-- end of box body -->
            </div><!-- end of box  -->

        </section>
    </div>


@endsection