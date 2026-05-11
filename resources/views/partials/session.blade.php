@if(session('success'))
    <script>Flux.ui.toast(@json(session('success')), { variant: 'success' })</script>
@endif

@if(session('error'))
    <script>Flux.ui.toast(@json(session('error')), { variant: 'danger' })</script>
@endif

@if($errors->any())
    <script>Flux.ui.toast(@json($errors->first()), { variant: 'danger' })</script>
@endif
