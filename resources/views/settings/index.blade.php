@extends('layouts.app')

@section('content')
<div class="row g-3">
    <div class="col-12 col-md-6">
        <h4 class="mb-3">Zmiana hasła</h4>

        @if(session('status'))
            <div class="alert-success">{{ session('status') }}</div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="needs-validation" novalidate="" method="POST" action="{{ route('settings.password') }}">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label for="current_password" class="form-label">Obecne hasło</label>
                    <input type="password" class="form-control" name="current_password" id="current_password" required="">
                    <div class="invalid-feedback">
                        Valid password is required.
                    </div>
                </div>
                <div class="col-12">
                    <label for="password" class="form-label">Nowe hasło</label>
                    <input type="password" class="form-control" name="password" id="password" required="">
                    <div class="invalid-feedback">
                        Valid password is required.
                    </div>
                </div>
                <div class="col-12">
                    <label for="password_confirmation" class="form-label">Powtórz nowe hasło</label>
                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required="">
                    <div class="invalid-feedback">
                        Valid password is required.
                    </div>
                </div>
                <div class="col-12">
                    <hr class="my-4">
                    <button class="w-100 btn btn-primary btn-lg" type="submit">Zmień hasło</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection