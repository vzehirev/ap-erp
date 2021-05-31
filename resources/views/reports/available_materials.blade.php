@extends('app')

@section('content')
    <div class="container text-center">

        {{-- Available materials table --}}
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">Материал</th>
                        <th scope="col">Код</th>
                        <th scope="col">Налично количество</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($availableMaterials as $availableMaterial)
                        <tr>
                            <td>{{ $availableMaterial->name }}</td>
                            <td>{{ $availableMaterial->code }}</td>
                            <td>{{ $availableMaterial->available_quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
