@if(session('success'))
    <script>Flux.ui.toast('{{ session('success') }}', { variant: 'success' })</script>
@endif

@if(session('error'))
    <script>Flux.ui.toast('{{ session('error') }}', { variant: 'danger' })</script>
@endif

@if($errors->any())
    <script>Flux.ui.toast('{{ $errors->first() }}', { variant: 'danger' })</script>
@endif
