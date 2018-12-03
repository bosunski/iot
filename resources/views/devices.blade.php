@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                        <div class="panel-heading">
                            Your Devices <a href="{{ route('create.device') }}" class="btn btn-default">Create device</a>
                        </div>

                        <div class="panel-body">
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif

                            @if ($devices->count() > 0)
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Unique ID</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($devices as $key => $device)
                                            <tr>
                                                <th scope="row">{{ $key + 1 }}</th>
                                                <td>{{ $device->name }}</td>
                                                <td>{{ $device->unique_id }}</td>
                                                <td>
                                                    <a href="{{ route('show.overview', $device->id) }}" class="btn btn-default">
                                                        Overview
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                            @else
                                <h1>You have not added any device.</h1>
                            @endif
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection
