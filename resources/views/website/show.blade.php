@extends('layouts.app')

@section('content')
<div class="row">
    <h1><a href="{{ @route('website.index') }}">{{ $website->name }}</a></h1>
    <h2>{{ $website->uri }}</h2>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">@lang('messages.uri.uri')</th>
            <th scope="col">@lang('messages.uri.status')</th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($website->uris as $uri)
            <tr>
                <td><a href="{{ $uri->uri }}">{{ $uri->uri }}</a></td>
                <td>
                    <span class="badge @if ($uri->status === 'success')badge-success @elseif ($uri->status === 'cancelled') badge-warning @else badge-secondary @endif">{{ $uri->status }}</span>
                </td>
                <td>{{ $uri->created_at }}</td>
                <td>{{ $uri->updated_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
