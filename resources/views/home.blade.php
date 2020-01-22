@extends('layouts.app')

@section('content')
    <div class="content">
        <h1 class="header">Платежи</h1>
        <div>
            @if (session('search'))
                <div class="alert-danger">
                    {{session('search')}}
                </div>
            @endif

            <form method="get" action="/home/search">
                <label>Статус: </label>
                <div class="dropdown">
                    <select name="status" class="dropdown-select">
                        <option value="0">Не выбрано</option>
                        <option value="1">Новая</option>
                        <option value="2">Предоплата оплачен</option>
                        <option value="3">Полностью оплачен</option>
                        <option value="4">Отправлен</option>
                    </select>
                </div>
                <br>

                <label>Начало: </label><br>
                <input type="date" class="form-control" class="mydate" name="start" placeholder="Дата">
                <label>Конец: </label><br>
                <input type="date" class="form-control" class="mydate" name="end" placeholder="Дата">
                <br>
                <button type="submit" name="search" class="search" value="search">Поиск</button>
            </form>

        </div>
        @if (isset($export))
            <div>
                <form method="post" action="/export" class="dropdown">
                    {{ csrf_field() }}
                    <button type="submit" class="export">Экспорт в Excel</button>
                </form>
            </div>
        @endif
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
                            <td>{{ $payment -> id }}</td>
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

                            <td>{{ date("H:i:s,  d:m:Y",strtotime($payment -> created_at)) }}</td>

                            <td> @if ($payment->status == 2)
                                    <input type="checkbox" name="check[]" value="{{ $payment->id }}">
                                @endif
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </form>
            @if (session('status'))
                {{$payments->appends(array("status" => Session::get('status'), "start" => Session::get('start'),"end" => Session::get('end')  ))->links()}}
            @else
                {{$payments->links()}}

            @endif
        </div>
    </div>
@endsection
