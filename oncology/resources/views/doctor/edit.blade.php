@extends('layouts.app') <!-- أو استبدل باللي عندك -->

@section('content')
<div class="container">
    <h2>تعديل الوصفة</h2>

    <form method="POST" action="{{ route('prescriptions.update', $prescription->id) }}">
        @csrf
        @method('PUT')

        <label>اسم العلاج:
            <input type="text" name="drug_name" value="{{ $prescription->drug_name }}" required>
        </label>

        <label>الكمية:
            <input type="number" name="quantity" value="{{ $prescription->quantity }}" required>
        </label>

        <label>ملاحظات:
            <textarea name="notes">{{ $prescription->notes }}</textarea>
        </label>

        <label>تاريخ التسجيل:
            <input type="date" name="prescribed_at" value="{{ $prescription->prescribed_at }}" required>
        </label>

        <button type="submit">تحديث</button>
    </form>
</div>
@endsection
