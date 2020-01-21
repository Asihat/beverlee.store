@extends('layouts.app')

@section('content')
    <h2>Amount of products</h2>
    <p><a href="/addProduct">add Product</a></p>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">id</th>
            <th scope="col">Name</th>
            <th scope="col">Amount</th>
            <th scope="col">Created_at</th>
        </tr>
        </thead>
        <tbody>
        @foreach($goods as $good)
        <tr>
            <th scope="row">{{ $good -> id }}</th>
            <td>{{ $good -> name }}</td>
            <td>{{ $good -> total_amount }}</td>
            <td>{{ $good -> created_at }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
@endsection
