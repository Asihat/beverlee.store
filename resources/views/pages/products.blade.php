@extends('layouts.app')

@section('content')
    <h3>Все продукты</h3>
    <p><a href="/addproduct">Add Product</a></p>
    <table class="table" id="tblPosts">
        <thead>
        <tr>
            <th scope="col">id</th>
            <th scope="col">Наименование пакета</th>
            <th scope="col">Описание</th>
            <th scope="col">Дата создания</th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{$product->id}}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product -> description }}</td>
                <td>{{ $product -> created_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
