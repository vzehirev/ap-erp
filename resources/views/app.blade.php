<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Анхрима Пласт ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="{{ mix('/js/app.js') }}"></script>
</head>

<body>
    @auth
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">{{ env('APP_NAME') }}</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse d-flex justify-content-between" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="/bought-material">Закупен материал</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/sorted-material">Сортиран материал</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/ground-material">Смлян материал</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/washed-material">Изпран материал</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/granular-material">Гранулиран материал</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/sold-material">Продаден материал</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/expenses">Разходи</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/salaries">Заплати</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/prepaid">Предплатени</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/others">Други</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="#">{{ auth()->user()->username }}</a>
                        </li>
                        <li class="nav-item">
                            <form action="/logout" method="post">
                                @csrf
                                <button class="nav-link border-0 bg-transparent" type="submit">Излез</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    @endauth
    @yield('content')
</body>

</html>
