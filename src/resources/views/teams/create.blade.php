@extends('_layout.app')

@section('title', 'Create New Team')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-dark text-white py-3">
                    <h4 class="mb-0 fs-5 fw-bold">Create New Team</h4>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('teams.store') }}" method="POST">
                        @csrf

                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label for="code" class="form-label fw-semibold text-secondary small">Team Code</label>
                                <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="Ej: RMA, FCB">
                                @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label for="short_name" class="form-label fw-semibold text-secondary small">Short Name</label>
                                <input type="text" name="short_name" id="short_name" class="form-control @error('short_name') is-invalid @enderror" value="{{ old('short_name') }}" placeholder="Ej: Real Madrid">
                                @error('short_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-5">
                                <label for="full_name" class="form-label fw-semibold text-secondary small">Full Name</label>
                                <input type="text" name="full_name" id="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" placeholder="Ej: Real Madrid Club de Fútbol">
                                @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label for="city_id" class="form-label fw-semibold text-secondary small">City Location</label>
                                <select name="city_id" id="city_id" class="form-select @error('city_id') is-invalid @enderror">
                                    <option value="">Select City</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('city_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="coach" class="form-label fw-semibold text-secondary small">Head Coach</label>
                                <input type="text" name="coach" id="coach" class="form-control @error('coach') is-invalid @enderror" value="{{ old('coach') }}" placeholder="Nombre del entrenador">
                                @error('coach') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="stadium" class="form-label fw-semibold text-secondary small">Stadium Name</label>
                                <input type="text" name="stadium" id="stadium" class="form-control @error('stadium') is-invalid @enderror" value="{{ old('stadium') }}" placeholder="Ej: Santiago Bernabéu">
                                @error('stadium') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label for="brand" class="form-label fw-semibold text-secondary small">Kit Brand</label>
                                <input type="text" name="brand" id="brand" class="form-control @error('brand') is-invalid @enderror" value="{{ old('brand') }}" placeholder="Ej: Adidas, Nike">
                                @error('brand') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="sponsor" class="form-label fw-semibold text-secondary small">Main Sponsor</label>
                                <input type="text" name="sponsor" id="sponsor" class="form-control @error('sponsor') is-invalid @enderror" value="{{ old('sponsor') }}" placeholder="Patrocinador principal">
                                @error('sponsor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="budget" class="form-label fw-semibold text-secondary small">Annual Budget (€)</label>
                                <input type="number" name="budget" id="budget" class="form-control @error('budget') is-invalid @enderror" value="{{ old('budget') }}" placeholder="Ej: 50000000">
                                @error('budget') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <hr class="text-secondary-subtle my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('teams.index') }}" class="btn btn-secondary px-4 shadow-sm">Cancel</a>
                            <button type="submit" class="btn btn-dark px-4 shadow-sm">Save Team</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection