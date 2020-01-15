@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-2">

                <ul>
                    <li><a href="/home">Платежи</a></li>
                    <li><a href="/addProduct">Добавить товар</a></li>
                </ul>

            </div>
            <div class="col-sm-10">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <form method="post" action="/add">
                    {{ csrf_field() }}
                    <select name="product_id">
                        @foreach ($product as $pro)
                            <option  value="{{$pro->id}}"> {{ $pro->name }} </option>
                        @endforeach
                    </select>
                    <br>
                    <input name="quantity" type="number"/> <br>

                    <button type="submit" href="/add" class="alert-success"> Добавить</button>

                </form>

            </div>
        </div>
    </div>
@endsection
