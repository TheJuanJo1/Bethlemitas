<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @yield('css')
    @stack('styles')
    <title>@yield('title')</title>
</head>
<body class="bg-[#D5DBDB] ">
         <div>
            @php
               $user = Auth::user();
               $photoPath = null;
               foreach (['jpg', 'jpeg', 'png'] as $ext) {
                  $path = public_path("Imagenes_Perfil/perfil_{$user->number_documment}.$ext");
                  if (file_exists($path)) {
                        $photoPath = asset("Imagenes_Perfil/perfil_{$user->number_documment}.$ext");
                        break;
                  }
               }
            @endphp
            <nav class="bg-[#D5DBDB] border-b border-gray-200 fixed z-[100] w-full">
               <div class="px-3 py-3 lg:px-5 lg:pl-3">
                  <div class="flex items-center justify-between">
                     <div class="flex items-center justify-start">

                        <button id="toggleSidebarMobile" aria-expanded="true" aria-controls="sidebar" class="p-2 mr-2 text-gray-600 rounded cursor-pointer lg:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 focus:ring-2 focus:ring-gray-100">
                           <svg id="toggleSidebarMobileHamburger" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                           </svg>
                           <svg id="toggleSidebarMobileClose" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                           </svg>
                        </button>

                       <a href="{{ route('dashboard') }}" class="text-xl font-bold flex items-center lg:ml-2.5">
                           <img src="{{ asset('img/R.png') }}" class="w-16 h-16 mr-2" alt="Windster Logo">
                           <span class="self-center whitespace-nowrap">
                              <em>Bethlemitas - PiarManager</em>
                           </span>
                        </a>
                     
                        <div class="flex items-center" style="display:none">
                           <button id="toggleSidebarMobileSearch" type="button" class="p-2 text-gray-500 rounded-lg lg:hidden hover:text-gray-900 hover:bg-gray-100">
                              <span class="sr-only">Search</span>
                              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                              </svg>
                           </button>
                        </div>

                     </div>
                     <div class="flex items-center">
                        <div class="flex items-center space-x-4 md:order-2" x-data="{ profileOpen: false }">
                           <span class="hidden md:block self-center text-lg font-medium text-slate-700 italic">
                              {{ Auth::user()->name }} {{ Auth::user()->last_name }}
                           </span>
                           
                           <!-- Contenedor Relativo para el Dropdown -->
                           <div class="relative">
                              <button
                                 @click="profileOpen = !profileOpen"
                                 @click.away="profileOpen = false"
                                 type="button"
                                 class="flex text-sm rounded-full focus:ring-4 focus:ring-blue-300 transition-all duration-200 transform hover:scale-105"
                                 id="user-menu-button">
                                 <span class="sr-only">Open user menu</span>
                                 <img class="w-11 h-11 rounded-full object-cover border-2 border-white shadow-sm" 
                                      src="{{ $photoPath ?? asset('img/icono-perfil.jpg') }}" alt="Foto de perfil">
                              </button>

                              <!-- Dropdown menu corregido con z-index alto y posición absoluta -->
                              <div 
                                 x-show="profileOpen"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-3 w-64 bg-white divide-y divide-slate-100 rounded-2xl shadow-2xl ring-1 ring-black ring-opacity-5 focus:outline-none z-[9999]" 
                                 style="display: none;">
                                 
                                 <div class="px-5 py-4 bg-slate-50 rounded-t-2xl">
                                    <p class="text-sm font-bold text-slate-900 truncate">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</p>
                                    <p class="text-xs font-medium text-slate-500 truncate mt-0.5">{{ Auth::user()->email }}</p>
                                 </div>
                                 
                                 <ul class="py-2">
                                    <li>
                                       <a href="{{ route('profile') }}" class="group flex items-center px-5 py-2.5 text-sm text-slate-700 hover:bg-blue-50 transition-colors">
                                          <div class="p-1.5 bg-blue-100 text-blue-600 rounded-lg mr-3 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                             <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                             </svg>
                                          </div>
                                          <span class="font-medium">Mi Perfil</span>
                                       </a>
                                    </li>
                                    <li>
                                       <a href="{{ route('logout') }}" class="group flex items-center px-5 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                          <div class="p-1.5 bg-red-100 text-red-600 rounded-lg mr-3 group-hover:bg-red-600 group-hover:text-white transition-colors">
                                             <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                             </svg>
                                          </div>
                                          <span class="font-medium">Cerrar Sesión</span>
                                       </a>
                                    </li>
                                 </ul>
                              </div>
                           </div>
                        </div>
                     </div>

                  </div>
                  </div>
               </div>
            </nav>
            <aside id="sidebar" class="fixed top-0 left-0 z-20 flex-col flex-shrink-0 hidden w-64 h-full pt-16 duration-75 lg:flex transition-width" aria-label="Sidebar">
               <div class="relative flex-1 flex flex-col min-h-0 border-r border-gray-200 bg-[#D5DBDB]  pt-0">
                  <div class="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto">
                     <div class="flex-1 px-3 bg-[#D5DBDB] divide-y space-y-1">
                        <ul class="pb-2 space-y-2">
               
                           <li>

                              <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-2 mt-4 bg-[#95A5A6] text-gray-900 rounded-lg font-medium" >
                                 <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                       d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z">
                                    </path>
                                 </svg>
                                 <span class="mx-3">Página Principal</span>
                              </a>

                              <!-- Opciones de interfaz para el rol Coordinador -->
                              @if ($user->hasRole('coordinador'))
                <div class="pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Gestión Administrativa</div>
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center w-full p-3 text-gray-900 transition rounded-lg hover:bg-[#95A5A6] group">
                        <i class="bi bi-people-fill text-xl mr-3"></i>
                        <span class="flex-1 text-left whitespace-nowrap">Usuarios</span>
                        <i class="bi" :class="open ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                    </button>
                    <div x-show="open" class="py-2 space-y-2 ml-6">
                        <a href="{{ route('create.user') }}" class="flex items-center p-2 text-gray-700 transition rounded-lg hover:bg-gray-200">Crear usuario</a>
                        <a href="{{ route('index.users') }}" class="flex items-center p-2 text-gray-700 transition rounded-lg hover:bg-gray-200">Listar usuarios</a>
                    </div>
                </div>
                <a href="{{ route('create.area') }}" class="flex items-center p-3 text-gray-900 rounded-lg hover:bg-[#95A5A6]"><i class="bi bi-book-half text-xl mr-3"></i>Areas</a>
                <a href="{{ route('create.group') }}" class="flex items-center p-3 text-gray-900 rounded-lg hover:bg-[#95A5A6]"><i class="bi bi-microsoft-teams text-xl mr-3"></i>Grupos</a>
            @endif

            @if ($user->hasRole('docente'))
                <div class="pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Módulo Docente</div>
                <a href="{{ route('create.referral') }}" class="flex items-center p-3 text-gray-900 rounded-lg hover:bg-[#95A5A6]"><i class="bi bi-person-plus-fill text-xl mr-3"></i>Remitir estudiante</a>
                <a href="{{ route('index.student.remitted') }}" class="flex items-center p-3 text-gray-900 rounded-lg hover:bg-[#95A5A6]"><i class="bi bi-list-check text-xl mr-3"></i>Estudiantes remitidos</a>
                <a href="{{ route('addMinutes') }}" class="flex items-center p-3 text-gray-900 rounded-lg hover:bg-[#95A5A6]"><i class="bi bi-file-earmark-text-fill text-xl mr-3"></i>Estudiantes en PIAR</a>
            @endif

            @if ($user->hasRole('psicoorientador'))
                <div class="pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Psicopedagogía</div>
                <a href="{{ route('create.referral') }}" class="flex items-center p-3 text-gray-900 rounded-lg hover:bg-[#95A5A6]"><i class="bi bi-arrow-left-right text-xl mr-3"></i>Remitir estudiante</a>
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center w-full p-3 text-gray-900 transition rounded-lg hover:bg-[#95A5A6]">
                        <i class="bi bi-person-badge-fill text-xl mr-3"></i>
                        <span class="flex-1 text-left whitespace-nowrap">Estudiantes</span>
                        <i class="bi" :class="open ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                    </button>
                    <div x-show="open" class="py-2 space-y-2 ml-6 text-sm">
                        <a href="{{ route('index.student.remitted.psico') }}" class="block p-2 text-gray-700 hover:bg-gray-200 rounded">• Remitidos</a>
                        <a href="{{ route('psico.students.piar') }}" class="block p-2 text-gray-700 hover:bg-gray-200 rounded">• En PIAR</a>
                        <a href="{{ route('psico.students.active') }}" class="block p-2 text-gray-700 hover:bg-gray-200 rounded">• Activos</a>
                    </div>
                </div>
            @endif
                              
                           </li>
                        </div>
                     </div>
                  </div>
               </div>
            </aside>
            <div id="main-content" class="bg-[#D5DBDB] min-h-screen lg:ml-64 transition-all duration-300">
               <main class="pt-20 pb-8">
                  <div class="px-2 md:px-6">
                     @yield('content')
                  </div>
               </main>
            </div>
         </div>
         <script async defer src="https://buttons.github.io/buttons.js"></script>
         <script src="https://demo.themesberg.com/windster/app.bundle.js"></script>
         <script src="https://cdn.tailwindcss.com"></script>
         <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
         <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
         @stack('scripts')

         {{-- Sweetalet de exito --}}
         @if (session('success'))
            <script>
               const Toast = Swal.mixin({
                     toast: true,
                     position: 'top-end',
                     iconColor: 'white',
                     customClass: {
                        popup: 'colored-toast',
                     },
                     showConfirmButton: false,
                     timer: 2500,
                     timerProgressBar: true,
               });
               Toast.fire({
                     icon: 'success',
                     title: '{{ session('success') }}',
               });
            </script>
         @endif

         {{-- Sweetalet de error --}}
         @if (session('error'))
            <script>
               const Toast = Swal.mixin({
                     toast: true,
                     position: 'top-end',
                     iconColor: 'white',
                     customClass: {
                        popup: 'colored-toast',
                     },
                     showConfirmButton: false,
                     timer: 4500,
                     timerProgressBar: true,
               });
               Toast.fire({
                     icon: 'error',
                     title: '{{ session('error') }}',
               });
            </script>
         @endif

         {{-- Sweetalet de info --}}
         @if (session('info'))
            <script>
               const Toast = Swal.mixin({
                     toast: true,
                     position: 'top-end',
                     iconColor: 'white',
                     customClass: {
                        popup: 'colored-toast',
                     },
                     showConfirmButton: false,
                     timer: 2500,
                     timerProgressBar: true,
               });
               Toast.fire({
                     icon: 'info',
                     title: '{{ session('info') }}',
               });
            </script>
         @endif
   </div>
</body>  
</html>