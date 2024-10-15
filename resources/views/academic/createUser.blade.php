@if (!Auth::user()->hasRole('coordinador'))
<h1>hola mundooo</h1>
    
@else
    <h1>Sebassss</h1>
@endif