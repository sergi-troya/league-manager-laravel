@extends('_layout.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Edit City: {{ $city->name }}</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('cities.update', $city) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="code" class="form-label">Code City</label>
                            <input type="text" 
                                   name="code" 
                                   id="code" 
                                   class="form-control @error('code') is-invalid @enderror" 
                                   value="{{ old('code', $city->code) }}">
                            
                            @error('code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Name City</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $city->name) }}" 
                                   maxlength="30" 
                                   placeholder="Ej: Valencia">
                            
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="population" class="form-label">Population (Opcional)</label>
                            <input type="number" 
                                   name="population" 
                                   id="population" 
                                   class="form-control @error('population') is-invalid @enderror" 
                                   value="{{ old('population', $city->population) }}">
                            
                            @error('population')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('cities.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update City</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection