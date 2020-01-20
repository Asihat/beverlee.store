@extends('layouts.app')

@section('content')
    <h3>Все пакеты</h3>
    <p><a href="/addpacket">Add Packet</a></p>
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
        @foreach($packets as $packet)
            <tr>
                <td>{{$packet->id}}</td>
                <td>{{ $packet->name }}</td>
                <td>{{ $packet -> description }}</td>
                <td>{{ $packet -> created_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
