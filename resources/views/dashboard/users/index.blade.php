@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @lang('site.users')

            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('/dashboard/index') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard') </a></li>
                <li class="active">@lang('site.users')</li>
            </ol>
        </section>

        <!-- Main content -->

        <!-- /.content -->
        <section class="content">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title" style="margin-bottom: 20px"> @lang('site.users')<small>{{ $users->total() }}</small></h3>
                    <!-- search data -->
                    <form action="{{ route('dashboard.users.index') }}" method="get">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="@lang('site.search')" value="{{ request()->search }}" >

                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>@lang('site.search')</button>
                                <!-- check  create user permission -->
                                @if (auth()->user()->hasPermission('create_users'))
                                    <a href="{{ route('dashboard.users.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>@lang('site.add')</a>
                                    @else
                                    <a href="#" class="btn btn-primary"><i class="fa fa-plus disabled"></i>@lang('site.add')</a>
                                @endif


                            </div>
                        </div>
                    </form>


                </div><!-- end of box header -->
                <div class="box-body">
                    <!-- check user counts-->
                    @if($users->count() > 0)
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('site.first_name')</th>
                                <th>@lang('site.last_name')</th>
                                <th>@lang('site.email')</th>
                                <th>@lang('site.image')</th>
                                <th>@lang('site.action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $index=>$user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->first_name }}</td>
                                    <td>{{ $user->last_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td><img src="{{ $user->image_path }}" style="width: 100px" class="img-thumbnail"> </td>
                                    <td>
                                        <!-- check  update user permission -->
                                        @if (auth()->user()->hasPermission('update_users'))
                                            <a class="btn btn-info sm" href=" {{ route('dashboard.users.edit', $user->id) }}" > <i class="fa fa-edit"></i>@lang('site.edit')</a>
                                        @else
                                            <a class="btn btn-info sm disabled" href=" #" >@lang('site.edit')</a>
                                        @endif
                                    <!-- check  delete user permission -->

                                            @if (auth()->user()->hasPermission('delete_users'))
                                               <form  action="{{ route('dashboard.users.destroy',$user->id) }}" method="post" style="display: inline-block">
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

                        {{ $users->appends(request()->query())->links() }}

                        @else
                        <h2>@lang('site.no_data_found')</h2>
                    @endif
                </div><!-- end of box body -->
            </div><!-- end of box  -->

        </section>
    </div>


@endsection