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
    <title>@yield('title')</title>
</head>
<body class="bg-[#D5DBDB] ">
         <div>
            <nav class="bg-[#D5DBDB]  border-b border-gray-200 fixed z-30 w-full">
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

                        <a href="#" class="text-xl font-bold flex items-center lg:ml-2.5">
                           <img src="{{asset('img/R.png')}}" class="w-16 h-16 mr-2" alt="Windster Logo">
                           <span class="self-center whitespace-nowrap"> <em>Bethlemitas - PiarManager</em></span>
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
                        <div class="flex items-center space-x-3 md:order-2 md:space-x-0 rtl:space-x-reverse">
                           <span class="self-center mr-4 text-2xl whitespace-nowrap"><em>{{Auth::user()->name}} {{Auth::user()->last_name}}</em></span>
                           <button type="button" class="flex text-sm rounded-full md:me-0 focus:ring-4 focus:ring-gray-300  id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
                              <span class="sr-only">Open user menu</span>
                              <img class="w-10 h-10 rounded-full" src="{{ asset('img/icono-perfil.jpg')}}" alt="user photo">
                           </button>
                           <!-- Dropdown menu -->
                           <div class="z-50 hidden my-4 text-base list-none bg-[#D5DBDB] divide-y divide-gray-100 rounded-lg shadow dark:divide-gray-600" id="user-dropdown">
                              <div class="px-4 py-3">
                                 <span class="block text-sm italic font-bold text-black-800">{{Auth::user()->name}} {{Auth::user()->last_name}}</span>
                                 <span class="block text-sm truncate text-black-800 dark:text-gray-400">{{Auth::user()->email}}</span>
                              </div>
                              <ul class="py-2" aria-labelledby="user-menu-button ">

                                 <li class="flex items-center hover:bg-[#95A5A6]">
                                    <a href="#" class="flex items-center px-4 py-2 text-sm text-black-800 ">
                                       <svg class="w-6 mr-2 6"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                       </svg>
                                       <span>Mi perfil</span>
                                    </a>
                                 </li>

                                 <li class="flex items-center hover:bg-[#95A5A6]">
                                    <a href="{{ route('logout')}}" class="flex items-center px-4 py-2 text-sm text-black-800">
                                       <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                       </svg>
                                       <span>Cerrar sesión</span>
                                    </a>
                                 </li>

                              </ul>
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

                              <!-- Opciones de interfaz para el rol coordinador -->
                              @if (Auth::user()->hasRole('coordinador'))
                                 <!-- Usuarios -->
                                 <div x-data="{ isOpen: false}">
                                    <a @click="isOpen = !isOpen" class="flex items-center px-5 py-2 mt-4 hover:bg-[#95A5A6] hover:text-gray-900 hover:rounded-lg font-medium cursor-pointer">
                                       <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                          <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"></path>
                                          <path d="M6 18c0-2.21 3.58-4 6-4s6 1.79 6 4"></path>
                                      </svg>
                                      <span class="mx-3">Usuarios</span>
                                      <svg class="w-6 h-6 ml-auto" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                       <polygon points="12 16, 6 8, 18 8" />
                                   </svg>
                                    </a>

                                    <div x-show="isOpen" class="ml-10">
                                       <a href="{{ route('create.user') }}"
                                       class="flex items-center px-3  mt-2 hover:bg-[#95A5A6] hover:text-gray-900 hover:rounded-lg font-medium">
                                             <!-- Icono si es necesario -->
                                             <span class="mx-3">• Crear usuario</span>
                                       </a>

                                       <a href="{{ route('index.users') }}"
                                       class="flex items-center px-3  mt-2 hover:bg-[#95A5A6] hover:text-gray-900 hover:rounded-lg font-medium">
                                          <!-- Icono si es necesario -->
                                          <span class="mx-3">• Listar usuarios</span>
                                       </a>
                                    </div>
                                 </div>

                                 <!-- Asignaturas -->
                                 <div>
                                    <a href="{{ route('create.asignature') }}" class="flex items-center px-5 py-2 mt-4 hover:bg-[#95A5A6] hover:text-gray-900 hover:rounded-lg font-medium cursor-pointer">
                                       <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                          <path d="M2 4h6a2 2 0 0 1 2 2v12a2 2 0 0 0-2 2H2z"></path>
                                          <path d="M22 4h-6a2 2 0 0 0-2 2v12a2 2 0 0 1 2 2h6z"></path>
                                          <line x1="10" y1="6" x2="14" y2="6"></line>
                                          <line x1="10" y1="10" x2="14" y2="10"></line>
                                          <line x1="10" y1="14" x2="14" y2="14"></line>
                                      </svg>
                                      <span class="mx-3">Asignaturas</span>
                                    </a>
                                 </div>

                                 <!-- Grados -->
                                 <div>
                                    <a href="{{ route('create.degree') }}" class="flex items-center px-5 py-2 mt-4 hover:bg-[#95A5A6] hover:text-gray-900 hover:rounded-lg font-medium cursor-pointer">
                                       <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                          <line x1="3" y1="16" x2="21" y2="16"></line>
                                      </svg>
                                      <span class="mx-3">Grados</span>
                                    </a>
                                 </div>

                                 <!-- Grupos -->
                                 <div>
                                    <a href="{{ route('create.group') }}" class="flex items-center px-5 py-2 mt-4 hover:bg-[#95A5A6] hover:text-gray-900 hover:rounded-lg font-medium cursor-pointer">
                                       <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                          <!-- Persona central -->
                                          <circle cx="12" cy="8" r="3"></circle>
                                          <path d="M10 16c0-2 2-3 2-3s2 1 2 3"></path>
                                      
                                          <!-- Persona izquierda -->
                                          <circle cx="6" cy="9" r="2"></circle>
                                          <path d="M4.5 16c0-1.5 1.5-2.5 1.5-2.5s1.5 1 1.5 2.5"></path>
                                      
                                          <!-- Persona derecha -->
                                          <circle cx="18" cy="9" r="2"></circle>
                                          <path d="M16.5 16c0-1.5 1.5-2.5 1.5-2.5s1.5 1 1.5 2.5"></path>
                                      </svg>
                                      <span class="mx-3">Grupos</span>
                                    </a>
                                 </div>
                              @endif
                              
                              <!-- Opciones de interfaz para el rol docente -->
                              @if (Auth::user()->hasRole('docente'))
                                 <!-- Remitir estudiante -->
                                 <div>
                                    <a href="{{ route('create.referral') }}" class="flex items-center px-5 py-2 mt-4 hover:bg-[#95A5A6] hover:text-gray-900 hover:rounded-lg font-medium cursor-pointer">
                                       <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                          <path d="M9 17l-5-5 5-5"></path>
                                          <path d="M22 12H4"></path>
                                          <circle cx="16" cy="12" r="2"></circle>
                                        </svg>
                                      <span class="mx-3">Remitir estudiante</span>
                                    </a>
                                 </div>

                                 <!-- Listar estudiantes -->
                                 <div>
                                    <a href="{{ route('index.student.remitted') }}" class="flex items-center px-5 py-2 mt-4 hover:bg-[#95A5A6] hover:text-gray-900 hover:rounded-lg font-medium cursor-pointer">
                                       <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                          <!-- Persona central -->
                                          <circle cx="12" cy="8" r="3"></circle>
                                          <path d="M10 16c0-2 2-3 2-3s2 1 2 3"></path>
                                      
                                          <!-- Persona izquierda -->
                                          <circle cx="6" cy="9" r="2"></circle>
                                          <path d="M4.5 16c0-1.5 1.5-2.5 1.5-2.5s1.5 1 1.5 2.5"></path>
                                      
                                          <!-- Persona derecha -->
                                          <circle cx="18" cy="9" r="2"></circle>
                                          <path d="M16.5 16c0-1.5 1.5-2.5 1.5-2.5s1.5 1 1.5 2.5"></path>
                                       </svg>
                                      <span class="mx-3">Listar estudiantes remitidos</span>
                                    </a>
                                 </div>
                                 <!-- Aqui se van a listar los estudiantes que ya se encuentran en proceso PIAR para añadirles un acta -->
                                 <div>
                                    <a href="{{ route('addMinutes') }}" class="flex items-center px-5 py-2 mt-4 hover:bg-[#95A5A6] hover:text-gray-900 hover:rounded-lg font-medium cursor-pointer">
                                       <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                          <path d="M8 2h8a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"></path>
                                          <line x1="12" y1="8" x2="12" y2="16"></line>
                                          <line x1="8" y1="12" x2="16" y2="12"></line>
                                        </svg>
                                      <span class="mx-3">Añadir acta</span>
                                    </a>
                                 </div>
                              @endif
                              
                           </li>
                        </div>
                     </div>
                  </div>
               </div>
            </aside>
            <div id="main-content" class="bg-[#D5DBDB] relative overflow-y-auto lg:ml-64 w-[100%] lg:w-[85%]" style="position:absolute; top:5rem">
               <main>
                  <div class="max-w-full max-h-full px-6 my-3" >
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
         @yield('JS')

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