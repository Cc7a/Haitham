@if ($errors->any())
    <div class="form-errors" role="alert" aria-live="polite">
        <strong>تحقق من الحقول:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif