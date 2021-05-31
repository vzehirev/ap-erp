@extends('app')

@section('content')
    <div class="container mt-5 text-center">

        <form id="materials-reports-form" class="col-12 col-md-4 mx-auto" method="get" action="/materials-reports" @csrf
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

    <div class="accordion accordion-flush" id="accordionFlushExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="flush-headingOne">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                    Accordion Item #1
                </button>
            </h2>
            <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
                data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Материал</th>
                                    <th scope="col">Код</th>
                                    <th scope="col">Закупено количество</th>
                                    <th scope="col">Средна цена</th>
                                </tr>
                            </thead>
                            <tbody id="bought-materials">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="flush-headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                    Accordion Item #2
                </button>
            </h2>
            <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo"
                data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the
                    <code>.accordion-flush</code> class. This is the second item's accordion body. Let's imagine this
                    being filled with some actual content.
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="flush-headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                    Accordion Item #3
                </button>
            </h2>
            <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree"
                data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the
                    <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more exciting
                    happening here in terms of content, but just filling up the space to make it look, at least at first
                    glance, a bit more representative of how this would look in a real-world application.
                </div>
            </div>
        </div>

    </div>

    <script>
        const tablesBodies = {
            boughtMaterials: $("#bought-materials"),
            wastedMaterials: $("#wasted-materials"),
            sortedMaterials: $("#sorted-materials"),
            groundMaterials: $("#ground-materials"),
            washedMaterials: $("#washed-materials"),
            granularMaterials: $("#granular-materials"),
            soldMaterials: $("#sold-materials"),
        };

        $("#materials-reports-form").submit(function(e) {
            e.preventDefault();

            $.ajax({
                    url: $(this).attr("action"),
                    method: $(this).attr("method"),
                    data: $(this).serialize(),
                })
                .done((data) => {
                    console.log(data);
return;
                    Object.entries(tablesBodies).forEach(kvp => {
                        let tableBodyHtml = kvp.value.reduce((acc, val) => {
                            return acc +=
                                `<tr><td>${val.name}</td><td>${val.code ?? ""}</td><td>${val.quantity}</td><td>${val.avgPrice}</td></tr>`;
                        }, "")
                    });

                    $(boughtMaterialsTableBody).html(tableBodyHtml);
                })
                .fail(() => {
                    alert("Възникна грешка.");
                });
        });

    </script>

@endsection
