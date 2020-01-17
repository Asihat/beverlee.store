@extends('layouts.app')

@section('content')
    <div class="container">
        <div>
            <div class="menu">

                <ul>
                    <li><a class="link" href="/home">Платежи</a></li>
                    <li><a class="link" href="/addProduct">Добавить товар</a></li>
                    <li><a class="link" href="/report">Отчет</a></li>
                </ul>

            </div>
            <div class="product">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <form method="post" action="/add" class="dropdown">
                    {{ csrf_field() }}
                    <select name="product_id" class="dropdown-select">
                        @foreach ($product as $pro)
                            <option  value="{{$pro->id}}"> {{ $pro->name }} </option>
                        @endforeach
                    </select>
                    <br>
                    <br>
                    <input name="quantity" type="number" class="number"/> <br>

                    <button type="submit" href="/add" class="add"> Добавить</button>

                </form>

            </div>
        </div>
    </div>
@endsection
