<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }}</title>
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
        <nav class="navbar navbar-expand-xxl navbar-dark bg-dark">
            <div class="container-fluid">

                <a href="/"><img src="{{ asset('/favicon.ico') }}"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler"
                    aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarToggler">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 mx-auto">
                        <li class="nav-item">
                            <a class="nav-link text-center {{ Request::path() == 'bought-material' ? 'text-decoration-underline active' : '' }}"
                                aria-current="page" href="/bought-material">Закупен материал</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center {{ Request::path() == 'sorted-material' ? 'text-decoration-underline active' : '' }}"
                                aria-current="page" href="/sorted-material">Сортиран материал</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center {{ Request::path() == 'ground-material' ? 'text-decoration-underline active' : '' }}"
                                aria-current="page" href="/ground-material">Смлян материал</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center {{ Request::path() == 'washed-material' ? 'text-decoration-underline active' : '' }}"
                                aria-current="page" href="/washed-material">Изпран материал</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center {{ Request::path() == 'granular-material' ? 'text-decoration-underline active' : '' }}"
                                aria-current="page" href="/granular-material">Гранулиран материал</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center {{ Request::path() == 'sold-material' ? 'text-decoration-underline active' : '' }}"
                                aria-current="page" href="/sold-material">Продаден материал</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center {{ Request::path() == 'expenses' ? 'text-decoration-underline active' : '' }}"
                                aria-current="page" href="/expenses">Разходи</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center {{ Request::path() == 'salaries' ? 'text-decoration-underline active' : '' }}"
                                aria-current="page" href="/salaries">Заплати</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center {{ Request::path() == 'prepaid' ? 'text-decoration-underline active' : '' }}"
                                aria-current="page" href="/prepaid">Предплатени</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center {{ Request::path() == 'reports' ? 'text-decoration-underline active' : '' }}"
                                aria-current="page" href="/reports">Отчети</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center {{ Request::path() == 'others' ? 'text-decoration-underline active' : '' }}"
                                aria-current="page" href="/others">Други</a>
                        </li>
                        <form class="nav-item" action="/logout" method="post">
                            @csrf
                            <button class="nav-link border-0 bg-transparent text-center mx-auto"
                                type="submit">ИЗХОД</button>
                        </form>
                    </ul>
                </div>
            </div>
        </nav>
    @endauth

    @yield('content')

</body>

</html>
