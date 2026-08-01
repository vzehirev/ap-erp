@extends('app')

@section('content')
    <div class="container text-center">

        {{-- Available materials table --}}
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">{{ __('app.fields.material') }}</th>
                        <th scope="col">{{ __('app.fields.code') }}</th>
                        <th scope="col">{{ __('app.fields.available_quantity') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($availableMaterials as $availableMaterial)
                        <tr>
                            <td class="align-middle">{{ $availableMaterial->name }}</td>
                            <td class="align-middle">{{ $availableMaterial->code }}</td>
                            <td class="align-middle">{{ $availableMaterial->available_quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
