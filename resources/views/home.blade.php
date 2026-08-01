  @include('layouts.header')

  <div class="relative w-full h-screen overflow-hidden">
    <!-- Image -->
    <img
      src="{{ asset('storage/landingpage.jpg') }}"
      alt="Eats Here Diner"
      class="w-full h-full object-cover">

    <!-- Dark gradient overlay -->
    <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/60 to-transparent"></div>

    <!-- Center text and button -->
    <div class="absolute inset-0 flex items-center justify-start">
      <!-- Using a container and padding to align with header/footer -->
      <div id="heroText" class="container mx-38 maw-w-3x2 opacity-0">
        <h1 class="text-4xl md:text-6xl font-bold leading-tight drop-shadow-lg text-white">
          Craving something special? <br>
          <span class="text-red-600">Your meal starts here! </span>
        </h1>

        <!-- Order Now Button (starts hidden and will fade in slightly after heading) -->
        <a id="heroBtn" href="{{ route('test') }}"
          class="mt-8 inline-block bg-red-600 text-white font-semibold px-8 py-3 rounded-full shadow-lg hover:bg-red-700 transition duration-300 ease-in-out opacity-0">
          Order Now
        </a>
      </div>
    </div>
  </div>

  <!-- Custom animations -->
  <style>
    /* slide-in for the whole text block */
    @keyframes slideIn {
      0% {
        opacity: 0;
        transform: translateX(-30px);
      }

      100% {
        opacity: 1;
        transform: translateX(0);
      }
    }

    .animate-slideIn {
      animation: slideIn 1.2s cubic-bezier(.2, .9, .2, 1) forwards;
    }

    /* simple fade for the button, with a short delay set via inline style or class */
    @keyframes fadeInUp {
      0% {
        opacity: 0;
        transform: translateY(6px);
      }

      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .animate-fadeInUp {
      animation: fadeInUp 0.6s ease-out forwards;
      animation-delay: 0.55s;
      /* staggers after the heading */
    }

    /* ensure initial hidden state (redundant with inline classes but safe) */
    #heroText,
    #heroBtn {
      will-change: transform, opacity;
    }
  </style>

  <!-- Intersection Observer to trigger animations when scrolled into view -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const heroText = document.getElementById('heroText');
      const heroBtn = document.getElementById('heroBtn');

      if (!heroText) return;

      // Prepare elements: ensure they start hidden (useful if CSS didn't apply)
      heroText.style.opacity = 0;
      heroBtn.style.opacity = 0;

      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            // add classes that run CSS animations
            heroText.classList.add('animate-slideIn');
            heroBtn.classList.add('animate-fadeInUp');
          } else {
            // remove classes so animation can replay when scrolled back
            heroText.classList.remove('animate-slideIn');
            heroBtn.classList.remove('animate-fadeInUp');
            // reset inline opacity to 0 so it replays
            heroText.style.opacity = 0;
            heroBtn.style.opacity = 0;
          }
        });
      }, {
        threshold: 0.25 // tweak to decide when animation should start
      });

      observer.observe(heroText);
    });
  </script>




  </div>





  @include('layouts.footer')