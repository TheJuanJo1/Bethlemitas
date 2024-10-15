@extends ('layout.masterPage')

@section ('title', 'PIAR Manager')

@section ('content')
    <section class="flex items-center justify-center bg-center bg-cover bg-no-repeat bg-[url('{{ asset('img/colegio1.jpg') }}')] bg-gray-600 bg-blend-multiply h-[81vh] max-w-full overflow-hidden my-10">
        <div class="text-center ">
            <h1 class="text-2xl font-bold text-white">
                <em>Bienvenidos al sistema PIAR del colegio <span class="text-5xl text-[#3A8BC0]">Bethlemitas - Pereira</span></em>
            </h1>
            <p id="typed-text" class="font-normal text-gray-100 px-[120px] p-5"></p>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var typed = new Typed('#typed-text', {
                strings: [
                    "Esta plataforma está diseñada para optimizar el proceso de remisión de estudiantes a PIAR, mejorando la eficiencia en la gestión y facilitando el seguimiento y la intervención por parte de los profesionales involucrados."
                ],
                typeSpeed: 50,
                backSpeed: 15,
                backDelay: 2000,
                loop: true,
                
            });
        });
    </script>
@endsection