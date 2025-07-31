@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">تعديل بيانات المركز</h2>

    <form action="{{ route('superadmin.center.update', $center->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">اسم المركز</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $center->name }}" required>
        </div>

        <button type="submit" class="btn btn-primary">تحديث</button>
        <a href="{{ route('superadmin.dashboard') }}" class="btn btn-secondary">رجوع</a>
    </form>
</div>

<!-- Toasts -->
@if(session('success') || session('error'))
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="liveToast" class="toast align-items-center text-white {{ session('success') ? 'bg-success' : 'bg-danger' }} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') ?? session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        const toast = new bootstrap.Toast(document.getElementById('liveToast'));
        toast.show();
    </script>
@endif
@endsection
