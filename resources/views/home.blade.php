@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-2">

                <ul>
                    <li><a href="/home">Платежи</a></li>
                    <li><a href="/addProduct">Добавить товар</a></li>
                </ul>

            </div>
            <div class="card-body col-md-8">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <h1>Beverlee Store</h1>
                <h3>
                    Payments
                </h3>
                <div>
                    <form method="post" action="/home/search">
                        {{ csrf_field() }}
                        <label>Статус: </label>
                        <select name="status">
                            <option value="0">Не выбрано</option>
                            <option value="1">Новая</option>
                            <option value="2">Предоплата оплачен</option>
                            <option value="3">Полностью оплачен</option>
                            <option value="4">Отправлен</option>
                        </select><br>

                        <label>Начало: </label><br>
                        <input type="date" class="form-control" class="mydate" name="start" placeholder="Дата">
{{--                        <label>День: </label>--}}
{{--                        <select>--}}
{{--                            <option>A</option>--}}
{{--                        </select>--}}

{{--                        <label>Месяц: </label>--}}
{{--                        <select>--}}
{{--                            <option>A</option>--}}
{{--                        </select>--}}

{{--                        <label>Год: </label>--}}
{{--                        <select>--}}
{{--                            <option>A</option>--}}
{{--                        </select> <br>--}}

                        <label>Конец: </label><br>
                        <input type="date" class="form-control" class="mydate" name="end" placeholder="Дата">
{{--                        <label>День: </label>--}}
{{--                        <select>--}}
{{--                            <option>A</option>--}}
{{--                        </select>--}}

{{--                        <label>Месяц: </label>--}}
{{--                        <select>--}}
{{--                            <option>A</option>--}}
{{--                        </select>--}}

{{--                        <label>Год: </label>--}}
{{--                        <select>--}}
{{--                            <option>A</option>--}}
{{--                        </select>--}}

                        <button type="submit" class="alert-success">Поиск</button>
                    </form>


                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">id</th>
                            {{--                        <th scope="col">Название товара</th>--}}
                            <th scope="col">Описание товара</th>
                            <th scope="col">Статус</th>
                            <th scope="col">Дата</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                {{--                            <th scope="row">{{$payment->id}}</th>--}}

                                <td>{{$payment->id}}</td>
                                <td>{{ $payment->description }}</td>

                                @switch($payment->status)
                                    @case('1')
                                    <td> Новая</td>
                                    @break

                                    @case('2')
                                    <td> Предоплата Оплачен</td>
                                    @break

                                    @case('3')
                                    <td>Польностью оплачен</td>
                                    @break

                                    @case('4')
                                    <td>Отправлен</td>
                                    @break

                                    @default
                                    <td></td>
                                    @break
                                @endswitch

                                <td>{{ $payment->updated_at }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    {{--    </div>--}}
    {{--    </div>--}}
@endsection
