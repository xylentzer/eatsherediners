  @include('layouts.header')

  <!-- About Us Section -->
  <section class="relative w-full h-[70vh] overflow-hidden">
    <!-- Background Image -->
    <img
      src="{{ asset('storage/ourstoriesbg.jpg') }}"
      alt="EatsHere Diner"
      class="w-full h-full object-cover">

    <!-- Dark Overlay -->
    <div class="absolute inset-0 bg-black/60"></div>

    <!-- Centered Text -->
    <div class="absolute inset-0 flex flex-col items-center justify-center text-center text-white px-6">
      <h1 class="text-5xl md:text-6xl font-bold mb-3 animate-slideInLeft">
        Our Story
      </h1>
      <p class="text-lg md:text-xl text-gray-200 max-w-2xl animate-fadeIn delay-200">
        Where sweetness began and dining excellence continues — discover the journey of EatsHere Diner.
      </p>
    </div>
  </section>

  <!-- About Content -->
  <section class="bg-gray-50 py-20 px-6 md:px-24 text-center">
    <div class="max-w-4xl mx-auto animate-fadeIn">
      <!-- Title -->
      <h2 class="text-4xl font-bold text-gray-900 mb-6">
        About <span class="text-red-600">EatsHere Diner</span>
      </h2>

      <!-- Story -->
      <p class="text-gray-700 text-lg leading-relaxed mb-6 animate-fadeIn delay-100">
        <span class="font-semibold text-red-600">EatsHere Diner</span> was founded in <span class="font-semibold">2022</span>
        in <span class="font-semibold">Bulihan, Silang, Cavite</span> by <span class="font-semibold">Ms. Ayen Panganiban</span>
        and her co-founder, <span class="font-semibold">Ms. Aira Panganiban-Ortega</span>. The business began as a modest
        online dessert shop, offering an array of delectable sweets that quickly captured the attention of the local community
        through digital ordering platforms.
      </p>

      <p class="text-gray-700 text-lg leading-relaxed mb-6 animate-fadeIn delay-200">
        In <span class="font-semibold">2023</span>, EatsHere Diner expanded its offerings beyond desserts, introducing a variety
        of savory and hearty meals while still focusing primarily on online ordering. The business also began catering to
        larger groups and celebrations with <span class="font-semibold">bulk ordering</span>, ensuring that even bigger orders
        retained the same quality and attention to detail that customers had come to expect.
      </p>

      <p class="text-gray-700 text-lg leading-relaxed mb-10 animate-fadeIn delay-300">
        Today, EatsHere Diner continues to thrive with the support of its loyal customers and the power of
        <span class="font-semibold text-red-600">social media</span>, reaching new audiences while maintaining its original
        mission: delivering warmth, convenience, and delicious food to every customer. With the dedication of Ms. Ayen
        Panganiban and Ms. Aira Panganiban-Ortega, EatsHere Diner aims to delight communities while preserving the personal
        touch and quality that marked its humble beginnings.
      </p>


      <!-- Divider -->
      <div class="w-24 h-1 bg-red-600 mx-auto my-8 rounded-full animate-fadeIn delay-400"></div>

      <!-- Credits -->
      <div class="bg-red-300 shadow-md rounded-2xl py-6 px-4 md:px-8 max-w-2xl mx-auto border border-gray-200 animate-fadeIn delay-500">
        This proposed project for <span class="font-semibold text-red-600">EatsHere Diner</span> was developed and designed
        by a dedicated team of students from
        <span class="font-semibold">AMA Computer College Biñan Campus</span>:
        </p>


        <!-- Team Images -->
        <div class="mt-10 flex flex-col md:flex-row justify-center items-center gap-8">

          <!-- Rix Card -->
          <div x-data="{ open: false }" class="text-center">
            <div @click="open = true" class="cursor-pointer animate-fadeIn delay-600">
              <img src="{{ asset('storage/rix.jpg') }}" alt="Rix" class="w-32 h-32 rounded-full object-cover mx-auto shadow-md">
              <p class="mt-2 font-semibold text-gray-800">Rix</p>
              <p class="text-sm text-gray-500">Developer</p>
            </div>

            <!-- Modal -->
            <div x-show="open" class="fixed inset-0 bg-black/50 flex justify-center items-center z-50" x-transition>
              <div @click.away="open = false" class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 relative">
                <button @click="open = false" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">&times;</button>
                <h2 class="text-2xl font-bold mb-2">Rix</h2>
                <p class="text-gray-700 mb-4">Developer at EatsHere Diner. Passionate about creating seamless user experiences and functional web apps.</p>
                <p class="text-sm text-gray-500">Skills: Laravel, PHP, Tailwind CSS, JavaScript</p>
              </div>
            </div>
          </div>

          <!-- Trishtan Card -->
          <div x-data="{ open: false }" class="text-center">
            <div @click="open = true" class="cursor-pointer animate-fadeIn delay-700">
              <img src="{{ asset('storage/tantan.jpg') }}" alt="Trishtan" class="w-32 h-32 rounded-full object-cover mx-auto shadow-md">
              <p class="mt-2 font-semibold text-gray-800">Trishtan</p>
              <p class="text-sm text-gray-500">Designer</p>
            </div>

            <!-- Modal -->
            <div x-show="open" class="fixed inset-0 bg-black/50 flex justify-center items-center z-50" x-transition>
              <div @click.away="open = false" class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 relative">
                <button @click="open = false" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">&times;</button>
                <h2 class="text-2xl font-bold mb-2">Trishtan</h2>
                <p class="text-gray-700 mb-4">Designer at EatsHere Diner. Specializes in UI/UX design and crafting visually appealing interfaces.</p>
                <p class="text-sm text-gray-500">Skills: Figma, Adobe XD, Photoshop, Illustrator</p>
              </div>
            </div>
          </div>

          <!-- El Card -->
          <div x-data="{ open: false }" class="text-center">
            <div @click="open = true" class="cursor-pointer animate-fadeIn delay-800">
              <img src="{{ asset('storage/el.jpg') }}" alt="El" class="w-32 h-32 rounded-full object-cover mx-auto shadow-md">
              <p class="mt-2 font-semibold text-gray-800">El</p>
              <p class="text-sm text-gray-500">Researcher</p>
            </div>

            <!-- Modal -->
            <div x-show="open" class="fixed inset-0 bg-black/50 flex justify-center items-center z-50" x-transition>
              <div @click.away="open = false" class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 relative">
                <button @click="open = false" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">&times;</button>
                <h2 class="text-2xl font-bold mb-2">El</h2>
                <p class="text-gray-700 mb-4">Researcher at EatsHere Diner. Focused on market analysis, customer behavior, and enhancing the user experience.</p>
                <p class="text-sm text-gray-500">Skills: Data Analysis, Surveys, UX Research, Reporting</p>
              </div>
            </div>
          </div>

        </div>

        <!-- Alpine.js -->
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

      </div>
    </div>
  </section>

  <!-- Animations -->
  <style>
    @keyframes slideInLeft {
      from {
        opacity: 0;
        transform: translateX(-100px);
      }

      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .animate-slideInLeft {
      animation: slideInLeft 1.3s ease-out forwards;
    }

    .animate-fadeIn {
      animation: fadeIn 1.5s ease-out forwards;
      opacity: 0;
    }

    .delay-100 {
      animation-delay: 0.2s;
    }

    .delay-200 {
      animation-delay: 0.4s;
    }

    .delay-300 {
      animation-delay: 0.6s;
    }

    .delay-400 {
      animation-delay: 0.8s;
    }

    .delay-500 {
      animation-delay: 1s;
    }

    .delay-600 {
      animation-delay: 1.2s;
    }

    .delay-700 {
      animation-delay: 1.4s;
    }

    .delay-800 {
      animation-delay: 1.6s;
    }
  </style>





  @include('layouts.footer')