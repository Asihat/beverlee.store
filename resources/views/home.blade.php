@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">

            <div class="menu">

                <ul>
                    <li><a class="link" href="/home">Платежи</a></li>
                    <li><a class="link" href="/addProduct">Добавить товар</a></li>
                    <li><a class="link" href="/report">Отчет</a></li>
                </ul>

            </div>

            <div class="content">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <h1 class="header">Платежи</h1>
                <div>

                    <form method="post" action="/home/search">
                        {{ csrf_field() }}
                        <label>Статус: </label>
                        <div class="dropdown">
                            <select name="status" class="dropdown-select">
                                <option value="0">Не выбрано</option>
                                <option value="1">Новая</option>
                                <option value="2">Предоплата оплачен</option>
                                <option value="3">Полностью оплачен</option>
                                <option value="4">Отправлен</option>
                            </select>
                        </div><br>

                        <label>Начало: </label><br>
                        <input type="date" class="form-control" class="mydate" name="start" placeholder="Дата">
                        <label>Конец: </label><br>
                        <input type="date" class="form-control" class="mydate" name="end" placeholder="Дата">
                        <br>
                        <button type="submit" class="search">Поиск</button>
                    </form>
                </div>
            </div>

<div class="payments">
    <form method="post" action="/home/mark">
        {{ csrf_field() }}
        <button id="btnClick" type="submit" class="mark">Отметить как отправлен</button>
        <table class="table" id="tblPosts">
            <thead>
            <tr>
                <th scope="col">id</th>
                <th scope="col">Описание товара</th>
                <th scope="col">Статус</th>
                <th scope="col">Дата</th>
                <th scope="col">Отметка</th>
            </tr>
            </thead>
            <tbody>

            @foreach($payments as $payment)
                <tr>
                    <td>{{$loop->index+1}}</td>
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

                    <td> @if ($payment->status == 2)
                            <input type="checkbox" name="check[]" value="{{ $payment->id }}">
                        @endif
                    </td>

                </tr>
            @endforeach

            </tbody>

        </table>
    </form>

</div>
        </div>
</div>

    <div class="page"> {{$payments->links()}}</div>
{{--        </div>--}}
{{--    </div>--}}
@endsection
