<nav x-data="{ mobileMenuOpen: false }" class="fixed no-print w-full z-50 px-4 md:px-6 py-6">
    <div class="max-w-7xl mx-auto px-6 md:px-8 py-4 md:py-5 rounded-full flex justify-between items-center shadow-2xl relative transition-all duration-500"
        style="background: rgba(255, 255, 255, 0.4); 
                backdrop-filter: blur(20px); 
                -webkit-backdrop-filter: blur(20px); 
                border: 1px solid rgba(255, 255, 255, 0.5);
                box-shadow: 0 20px 50px rgba(51, 58, 115, 0.08);">

        <a href="homepage.php" class="relative z-50 flex items-center transition-transform hover:scale-105 duration-300">
            <img src="./assets/img/logo.png" alt="DangDang Studio Logo" class="h-8 md:h-10 w-auto object-contain">
        </a>

        <div class="hidden lg:flex gap-10 text-[10px] font-black uppercase tracking-[0.25em]" style="color: #333A73;">
            <a href="#about" class="hover:text-brandTeal transition-all hover:tracking-[0.35em]">About</a>
            <a href="#games" class="hover:text-brandTeal transition-all hover:tracking-[0.35em]">Games</a>
            <a href="#articles" class="hover:text-brandTeal transition-all hover:tracking-[0.35em]">Insight</a>
            <a href="#contact" class="hover:text-brandTeal transition-all hover:tracking-[0.35em]">Contact</a>
        </div>

        <div class="lg:hidden">
            <button @click="mobileMenuOpen = !mobileMenuOpen"
                class="w-10 h-10 flex flex-col items-center justify-center gap-1.5 z-50 focus:outline-none rounded-full transition-all"
                style="background-color: #333A73;">
                <span class="w-5 h-0.5 bg-white transition-all duration-300" :class="mobileMenuOpen ? 'rotate-45 translate-y-2' : ''"></span>
                <span class="w-5 h-0.5 bg-white transition-all duration-300" :class="mobileMenuOpen ? 'opacity-0' : ''"></span>
                <span class="w-5 h-0.5 bg-white transition-all duration-300" :class="mobileMenuOpen ? '-rotate-45 -translate-y-2' : ''"></span>
            </button>
        </div>

        <div
            x-show="mobileMenuOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
            @click.away="mobileMenuOpen = false"
            class="absolute top-full left-0 right-0 mt-4 mx-2 p-10 lg:hidden flex flex-col gap-8 text-center shadow-2xl rounded-[3rem]"
            style="background: rgba(255, 255, 255, 0.85); 
                    backdrop-filter: blur(25px); 
                    -webkit-backdrop-filter: blur(25px); 
                    border: 1px solid rgba(255, 255, 255, 0.5);
                    display: none;">
            <a @click="mobileMenuOpen = false" href="#about" class="text-xs font-black uppercase tracking-[0.3em] hover:text-brandTeal transition-all" style="color: #333A73;">About</a>
            <a @click="mobileMenuOpen = false" href="#games" class="text-xs font-black uppercase tracking-[0.3em] hover:text-brandTeal transition-all" style="color: #333A73;">Games</a>
            <a @click="mobileMenuOpen = false" href="#articles" class="text-xs font-black uppercase tracking-[0.3em] hover:text-brandTeal transition-all" style="color: #333A73;">Insight</a>
            <a @click="mobileMenuOpen = false" href="#contact" class="text-xs font-black uppercase tracking-[0.3em] hover:text-brandTeal transition-all" style="color: #333A73;">Contact</a>
        </div>
    </div>
</nav>
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>