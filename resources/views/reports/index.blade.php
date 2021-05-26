@extends('app')

@section('content')
    <div class="container mt-5 text-center">
        <ul class="nav nav-pills mb-4 justify-content-center" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-washed-materials-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-washed-materials" type="button" role="tab" aria-controls="pills-washed-materials"
                    aria-selected="true">Изпран материал</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-stored-materials-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-stored-materials" type="button" role="tab" aria-controls="pills-stored-materials"
                    aria-selected="false">Складиран материал</button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-washed-materials" role="tabpanel"
                aria-labelledby="pills-washed-materials-tab">
                <form id="washed-materials-form" class="col-12 col-md-4 mx-auto" method="get"
                    action="/washed-materials-report" data-tablebodyid="washed-materials-table">
                    @csrf
                    <div class="m-3">
                        <label for="from_date" class="form-label">От дата</label>
                        <input type="date" class="form-control" id="from_date" name="from_date">
                    </div>
                    <div class="m-3">
                        <label for="to_date" class="form-label">До дата</label>
                        <input type="date" class="form-control" id="to_date" name="to_date">
                    </div>
                    <div class="m-3 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">OK</button>
                    </div>
                </form>

                <div class="table-responsive mt-5">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Наличен изпран материал</th>
                                <th scope="col">Код</th>
                                <th scope="col">Налично изпрано количество</th>
                            </tr>
                        </thead>
                        <tbody id="washed-materials-table">
                        </tbody>
                    </table>
                </div>

            </div>
            <div class=" tab-pane fade" id="pills-stored-materials" role="tabpanel"
                aria-labelledby="pills-stored-materials-tab">
                <form id="stored-materials-form" method="get" class="col-12 col-md-4 mx-auto"
                    action="/stored-materials-report" data-tablebodyid="stored-materials-table">
                    @csrf
                    <div class="m-3">
                        <label for="from_date" class="form-label">От дата</label>
                        <input type="date" class="form-control" id="from_date" name="from_date">
                    </div>
                    <div class="m-3">
                        <label for="to_date" class="form-label">До дата</label>
                        <input type="date" class="form-control" id="to_date" name="to_date">
                    </div>
                    <button type="submit" class="btn btn-primary">OK</button>
                </form>

                <div class="table-responsive mt-5">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Наличен складиран материал</th>
                                <th scope="col">Код</th>
                                <th scope="col">Налично складирано количество</th>
                            </tr>
                        </thead>
                        <tbody id="stored-materials-table">
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <script>
        $("form").submit(function(e) {
            e.preventDefault();

            $.ajax({
                    url: $(this).attr("action"),
                    method: $(this).attr("method"),
                    data: $(this).serialize(),
                })
                .done((data) => {
                    console.log(data);
                    let tableBody = $(`#${$(this).data("tablebodyid")}`);

                    let tableBodyHtml = data.reduce((acc, val) => {
                        return acc +=
                            `<tr><td>${val.name}</td><td>${val.code}</td><td>${val.quantity}</td></tr>`;
                    }, "")

                    $(tableBody).html(tableBodyHtml);
                })
                .fail(() => {
                    alert("Възникна грешка.");
                });
        });

    </script>

@endsection
