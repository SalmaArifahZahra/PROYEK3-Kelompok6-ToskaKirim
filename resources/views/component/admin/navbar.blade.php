<nav class="bg-[#5BC6BC] shadow-md h-16 fixed top-0 left-0 right-0 z-50">
    <div class="max-w-full mx-auto px-6 h-full flex items-center justify-between">

        <!-- Logo -->
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/icon_toska.png') }}" class="h-12 w-auto" alt="ToskaKirim Logo">
        </div>

        <div class="mx-4 mt-6 mb-6"> 
            @php
                $user = Auth::user();
                $isSuper = $user->peran === 'superadmin' || $user->peran === \App\Enums\RoleEnum::SUPERADMIN; 
            @endphp

            <div class="flex items-center gap-3 px-3 py-1 rounded-lg transition-all duration-300
                        {{ $isSuper 
                            ? 'bg-slate-800 border-slate-700 shadow-md'  
                            : 'bg-white/10 border border-white/10 hover:bg-white/20'        
                        }}">
                
                <div class="relative flex-shrink-0">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center shadow-inner
                                {{ $isSuper 
                                    ? 'bg-gradient-to-br from-amber-400 to-orange-500 text-white' 
                                    : 'bg-transparent' 
                                }}">
                        <i class="fas {{ $isSuper ? 'fa-crown' : 'fa-user-tie' }} {{ $isSuper ? 'text-xs' : 'text-white text-lg' }}"></i>
                    </div>
                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 {{ $isSuper ? 'border-slate-800' : 'border-white' }} rounded-full"></span>
                </div>

                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold truncate leading-tight {{ $isSuper ? 'text-white' : 'text-white' }}">
                        {{ explode(' ', $user->nama)[0] }}
                    </p>
                    <p class="text-[10px] font-semibold uppercase tracking-wider truncate mt-0.5 {{ $isSuper ? 'text-amber-400' : 'text-white' }}">
                        {{ $isSuper ? 'Super Admin' : 'Admin Staff' }}
                    </p>
                </div>
                
            </div>
        </div>

    </div>
</nav>
