@extends('layouts.app')

@section('content')
    <div class="container">
            <div class="menu">

                <ul>
                    <li><a class="link" href="/home">Платежи</a></li>
                    <li><a class="link" href="/addProduct">Добавить товар</a></li>
                    <li><a class="link" href="/report">Отчет</a></li>
                </ul>

            </div>
            <div class="report">
                <form method="post" action="/export" class="dropdown">
                    {{ csrf_field() }}
                    <label>Статус: </label>
                    <select name="status" required class="dropdown-select">
                        <option value="0">Не выбрано</option>
                        <option value="1">Новая</option>
                        <option value="2">Предоплата оплачен</option>
                        <option value="3">Полностью оплачен</option>
                        <option value="4">Отправлен</option>
                    </select><br><br>

                    <label>Начало: </label><br>
                    <input type="date" class="form-control" class="mydate" name="start" placeholder="Дата" required>
                    <br><br>
                    <label>Конец: </label><br>
                    <input type="date" class="form-control" class="mydate" name="end" placeholder="Дата" required>
                    <br><br>
                    <button type="submit" class="export">Экспорт в Excel</button>
                </form>
            </div>
        </div>
    </div>
@endsection
