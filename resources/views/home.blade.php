@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white">
<!-- Include Header -->
@include('layouts.header')

<!-- Hero Section -->
<section class="relative w-full min-h-[707px] flex items-center bg-[url('/megamendung/7.png')] bg-cover bg-center overflow-hidden">
    <div class="container mx-auto px-6 flex items-center justify-between">
        <!-- Text Content -->
        <div class="w-1/2 pr-12 z-10">
            <h1 class="text-5xl font-bold mb-6 leading-tight">Tempat di Mana Imajinasi dan Tradisi Berpadu</h1>
            <p class="text-xl mb-8">Setiap Batik Memiliki Cerita. Sekarang, Giliranmu untuk Menulisnya.</p>            <a href="/katalog">
                <button class="px-8 py-3 bg-black text-white text-lg font-bold rounded-full transition 
                             hover:bg-yellow-400 hover:shadow-lg active:scale-95">
                    Mulai Belanja
                </button>
            </a>
        </div>

        <!-- Slider -->
        <div class="w-1/2 relative h-[600px]">
            <div class="slider-container relative w-full h-full">                <!-- Slides -->
                <div class="slides relative w-full h-full">
                    <div class="slide absolute inset-0 opacity-100 transition-all duration-700 transform">
                        <a href="/produk/1" class="block w-full h-full hover:opacity-90 transition-opacity">
                            <img src="/megamendung/megamendungpng.png" alt="Batik Megamendung" class="w-full h-full object-contain">
                        </a>
                    </div>
                    <div class="slide absolute inset-0 opacity-0 transition-all duration-700 transform translate-x-full">
                        <a href="/produk/2" class="block w-full h-full hover:opacity-90 transition-opacity">
                            <img src="/megamendung/home2png.png" alt="Batik Parang" class="w-full h-full object-contain">
                        </a>
                    </div>
                    <div class="slide absolute inset-0 opacity-0 transition-all duration-700 transform translate-x-full">
                        <a href="/produk/3" class="block w-full h-full hover:opacity-90 transition-opacity">
                            <img src="/megamendung/home3png.png" alt="Batik Lasem" class="w-full h-full object-contain">
                        </a>
                    </div>
                </div>

                <!-- Navigation Arrows -->
                <button onclick="moveSlide(-1)" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black/50 text-white p-3 rounded-full hover:bg-black/70 z-20 transition-all hover:scale-110">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button onclick="moveSlide(1)" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black/50 text-white p-3 rounded-full hover:bg-black/70 z-20 transition-all hover:scale-110">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <!-- Dots -->
                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-3 z-20">
                    <button onclick="setSlide(0)" class="w-3 h-3 rounded-full bg-black/50 hover:bg-black transition-all hover:scale-110 dot active"></button>
                    <button onclick="setSlide(1)" class="w-3 h-3 rounded-full bg-black/50 hover:bg-black transition-all hover:scale-110 dot"></button>
                    <button onclick="setSlide(2)" class="w-3 h-3 rounded-full bg-black/50 hover:bg-black transition-all hover:scale-110 dot"></button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Slider Script -->
<script>
let currentSlide = 0;
const slides = document.querySelectorAll('.slide');
const dots = document.querySelectorAll('.dot');
let autoSlideInterval;

function showSlide(n) {
    // Hide all slides first
    slides.forEach(slide => {
        slide.style.opacity = '0';
        slide.style.transform = 'translateX(100%)';
    });
    dots.forEach(dot => dot.classList.remove('active'));
    
    // Calculate the new current slide
    currentSlide = (n + slides.length) % slides.length;
    
    // Show the current slide
    slides[currentSlide].style.opacity = '1';
    slides[currentSlide].style.transform = 'translateX(0)';
    dots[currentSlide].classList.add('active');
    
    // Reset auto slide timer
    resetAutoSlide();
}

function moveSlide(direction) {
    showSlide(currentSlide + direction);
}

function setSlide(n) {
    showSlide(n);
}

function resetAutoSlide() {
    // Clear existing interval
    clearInterval(autoSlideInterval);
    // Start new interval
    autoSlideInterval = setInterval(() => moveSlide(1), 4000);
}

// Initialize auto slide
resetAutoSlide();
showSlide(0);

// Pause auto slide when user hovers over slider
document.querySelector('.slider-container').addEventListener('mouseenter', () => {
    clearInterval(autoSlideInterval);
});

// Resume auto slide when user leaves slider
document.querySelector('.slider-container').addEventListener('mouseleave', resetAutoSlide);
</script>

<style>
.dot.active {
    @apply bg-black;
}
</style>

<!-- Content Section -->
<section class="container mx-auto px-4 py-20">
    <!-- Box 1 -->
    <div class="box max-w-4xl mx-auto mb-32 flex items-center gap-12 opacity-0 translate-y-12 transition-all duration-700">
        <img src="/megamendung/flower1.png" alt="Flower Decoration" class="w-40 h-40 object-contain">
        <div>
            <h2 class="text-3xl font-bold mb-4">Dilahirkan di Desa, Dipersembahkan untuk Dunia</h2>
            <p class="text-lg text-gray-700">
                Batikku bermula dari desa-desa batik yang kami dampingi secara langsung. Kami hadir sebagai jembatan antara tradisi yang tulus dan dunia yang modern.
            </p>
        </div>
    </div>

    <!-- Box 2 -->
    <div class="box max-w-4xl mx-auto mb-32 flex flex-row-reverse items-center gap-12 opacity-0 translate-y-12 transition-all duration-700">
        <img src="/megamendung/flower2.png" alt="Flower Decoration" class="w-40 h-40 object-contain">
        <div class="text-right">
            <h2 class="text-3xl font-bold mb-4">Menceritakan Budaya Lewat Motif</h2>
            <p class="text-lg text-gray-700">
                Setiap motif batik memiliki kisah tentang cinta, tanah air, dan kehidupan. Kami percaya, memakai batik adalah cara paling elegan untuk bercerita.
            </p>
        </div>
    </div>

    <!-- Box 3 -->
    <div class="box max-w-4xl mx-auto mb-32 flex items-center gap-12 opacity-0 translate-y-12 transition-all duration-700">
        <img src="/megamendung/flower3.png" alt="Flower Decoration" class="w-40 h-40 object-contain">
        <div>
            <h2 class="text-3xl font-bold mb-4">Eksklusif. Etis. Penuh Arti.</h2>
            <p class="text-lg text-gray-700">
                Batikku bukan hanya label. Ia adalah komitmen untuk menghadirkan batik dengan nilai, memberi ruang pada para pengrajin, dan menghargai apa yang bermakna.
            </p>
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const boxes = document.querySelectorAll(".box");
    
    function checkScroll() {
        boxes.forEach(box => {
            const boxTop = box.getBoundingClientRect().top;
            const boxBottom = box.getBoundingClientRect().bottom;
            const windowHeight = window.innerHeight;
            
            if (boxTop < windowHeight * 0.85 && boxBottom > 0) {
                box.style.opacity = "1";
                box.style.transform = "translateY(0)";
            }
        });
    }

    // Check on load
    checkScroll();
    
    // Check on scroll
    window.addEventListener("scroll", checkScroll);
});
</script>

<!-- Produk Slider Section -->
@if(isset($produkSlider) && $produkSlider->count())
<section class="container mx-auto px-4 py-16">
    <h2 class="text-2xl md:text-3xl font-bold text-center mb-8 font-righteous">Katalog Produk Terbaru</h2>
    <div class="relative">
        <div id="produkSlider" class="flex gap-6 overflow-x-auto pb-4 snap-x">
            @foreach($produkSlider as $produk)
                <div class="min-w-[220px] max-w-xs snap-center">
                    @include('components.product-card', ['produk' => $produk])
                </div>
            @endforeach
        </div>
        <!-- Optional: Arrow navigation -->
        <!--
        <button onclick="scrollProdukSlider(-1)" class="absolute left-0 top-1/2 -translate-y-1/2 bg-black/60 text-white p-2 rounded-full z-10">&#8592;</button>
        <button onclick="scrollProdukSlider(1)" class="absolute right-0 top-1/2 -translate-y-1/2 bg-black/60 text-white p-2 rounded-full z-10">&#8594;</button>
        -->
    </div>
</section>
@endif

<script>
function scrollProdukSlider(dir) {
    const slider = document.getElementById('produkSlider');
    slider.scrollBy({ left: dir * 250, behavior: 'smooth' });
}
</script>

<!-- Footer -->
<footer class="bg-black py-16">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-2 gap-16">
        <!-- Brand Info -->
        <div class="space-y-6">
            <h2 class="text-4xl font-bold tracking-tight text-white">Batikku</h2>
            <p class="text-gray-300 max-w-md">
                Menghubungkan keindahan batik tradisional Indonesia dengan dunia modern melalui platform e-commerce yang mengedepankan kualitas dan keaslian.
            </p>
            <div class="flex items-center space-x-4 text-sm text-gray-400">
                <span>© 2025 Batikku</span>
                <a href="/about" class="hover:text-white transition-colors">About</a>
            </div>
        </div>

        <!-- Contact & Location -->
        <div class="space-y-6">
            <div class="space-y-3">
                <h3 class="font-medium text-white">Batikku Head Office</h3>
                <p class="text-gray-300 italic">
                    "Kita masih gapunya kantor, kantor fiktif hehe"<br>
                    <span class="text-white">¯\_(ツ)_/¯</span>
                </p>
            </div>
            <div class="space-y-3">
                <h3 class="font-medium text-white">Hubungi Kami</h3>
                <p class="text-gray-300 italic">
                    "Lewat telepati aja ya..."<br>
                    <span class="text-white">━━━━━━━∈(･ω･)∋━━━━━━</span>
                </p>
            </div>
        </div>
    </div>
</footer>

</div>
@endsection

@push('styles')
<!-- Google Fonts: Righteous -->
<link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
<style>
    .font-righteous { font-family: 'Righteous', cursive; }
</style>
@endpush
