@extends('backends.templates.master')
@section('title', __('Thông báo'))
@section('content')
    <div id="notification" class="content-wrapper events">
        <section class="content">
            <div class="head container">
                <h1 class="title">{{ __('Thông báo') }}</h1>
                <a href="{{ route('admin.readAll') }}" class="btn float-right" style="color: rgb(0, 255, 21);"> <i class="fa fa-check"></i>
                    Đánh dấu tất cả là đã đọc </a>
            </div>
            <div class="main">
                <h4 style="color: red">{{ __('Bạn có ') }}{{ $total }}{{ __(' thông báo mới') }}</h4>
                <div class="card">
                    <div class="card-body p-0">
                        @include('notices.index')
                        <form class="dev-form" action="" name="listEvent" method="POST">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" role="table">
                                    <thead class="thead">
                                        <tr>
                                            <th>{{ __('STT') }}</th>
                                            <th>{{ __('Thời gian') }}</th>
                                            <th>{{ __('Nội dung thông báo') }}</th>
                                            <th>{{ __('Người gửi') }}</th>
                                            <th>{{ __('Thao tác') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">
                                        @if (!$notifications->isEmpty())
                                            @foreach ($notifications as $key => $notification)
                                                @php
                                                    $notice = $notification->data;
                                                @endphp
                                                <tr class="{{ $notification->read_at == null ? 'notice-red' : '' }}">
                                                    <td>{{ ++$key }}</td>
                                                    <td>{{ $notification->created_at }}</td>
                                                    <td>{{ $notice['content'] }}</td>
                                                    <td>{{ getUserById($notice['user_id']) ? getUserById($notice['user_id']) : '' }}</td>
                                                    <td class="group-action text-nowrap">
                                                        <a class="btn btn-info btn-sm"
                                                            href="{{ route('admin.edit', ['id' => $notification->id]) }}"><i
                                                                class="fas fa-pencil-alt"></i></a>
                                                        <a class="btn btn-danger btn-sm"
                                                            href="{{ route('admin.delete', ['id' => $notification->id]) }}"
                                                            data-toggle="modal" data-target="#sideModal"
                                                            data-direct="modal-top-right"><i class="fas fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="11">{{ __('No items!') }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            {!! $notifications->links() !!}
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
        @include('modals.modal_delete')
    </div>
@endsection
