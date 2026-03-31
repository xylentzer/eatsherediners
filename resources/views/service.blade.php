  @include('layouts.header')


  <section class="bg-gray-50 mt-10 py-16">
    <div class="container mx-auto px-8 md:px-24 text-center">
      <!-- Heading -->
      <div id="serviceHeader" class="opacity-0">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Services</h2>
        <p class="text-lg text-gray-700 mb-10 max-w-3xl mx-auto">
          At <span class="font-semibold text-red-600">EatsHere Diner</span>, we serve your cravings just the way you want them — whether it’s a <span class="font-semibold">hearty solo meal</span> or a <span class="font-semibold">bulk order</span> for gatherings, meetings, or celebrations.
          We’re here to make every dining experience simple, satisfying, and delicious.
        </p>
      </div>

      <!-- Two service cards -->
      <div id="serviceCards" class="grid md:grid-cols-2 gap-8 mb-16 opacity-0">
        <!-- Individual Order -->
        <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-2xl transition duration-300">
          <h3 class="text-2xl font-semibold text-red-600 mb-3">Solo Orders</h3>
          <p class="text-gray-600">
            Enjoy freshly prepared meals made with love — perfect for your daily cravings. Choose from our menu, customize your order, and enjoy a restaurant-quality meal anytime.
          </p>
        </div>

        <!-- Bulk Order -->
        <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-2xl transition duration-300">
          <h3 class="text-2xl font-semibold text-red-600 mb-3">Bulk Orders</h3>
          <p class="text-gray-600">
            Hosting an event or feeding your team? Our bulk meal sets are crafted to delight large groups with consistent quality and generous servings — delivered right on time.
          </p>
        </div>
      </div>

      <!-- Customer Reviews -->
      <div id="serviceReviews" class="opacity-0">
        <h3 class="text-3xl font-bold text-gray-900 mb-8">What Our Customers Say</h3>
        <div class="grid md:grid-cols-3 gap-8">
          <div class="review-card bg-white rounded-2xl shadow-md p-6 opacity-0">
            <p class="italic text-gray-700">“Highly recommended !! Hindi lang service nila ang on top but also their food & drinks of course hihi. Despite its budget-friendly price, they still serve quality products. i can vouch for thatttt 😉 also love how they treat their customers hihi super duper friendly !!!”</p>
            <p class="mt-4 font-semibold text-red-600">– Aliah I.</p>
          </div>

          <div class="review-card bg-white rounded-2xl shadow-md p-6 opacity-0">
            <p class="italic text-gray-700">“Highly recommended! EatsHere Diner provided our wedding crew meals on very short notice, and everything was absolutely delicious. Super accommodating, affordable, and easy to work with, thank you for making our day smoother! 🩵”</p>
            <p class="mt-4 font-semibold text-red-600">– Rona L.</p>
          </div>

          <div class="review-card bg-white rounded-2xl shadow-md p-6 opacity-0">
            <p class="italic text-gray-700">“I’ve visited EatsHere Diner three times now, their chicken wings are a flavor explosion that’ll have you coming back for more. On my first trip, I loved it. by the second and third, I brought my siblings along because good food is meant to be shared. With wings this GOOD! , they deserve a solid 5 star. Plus, their beverages are so impressive, they could give the big-name cafés a run for their money. EatsHere Diner: where the wings are unforgettable and the drinks are TOP-NOTCH!”</p>
            <p class="mt-4 font-semibold text-red-600">– Heir B.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Animation Styles -->
  <style>
    @keyframes fadeInUp {
      0% {
        opacity: 0;
        transform: translateY(40px);
      }

      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .animate-fadeInUp {
      animation: fadeInUp 1.2s ease-out forwards;
    }
  </style>

  <!-- Animation Script --> 
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const header = document.getElementById('serviceHeader');
      const cards = document.getElementById('serviceCards');
      const reviewsContainer = document.getElementById('serviceReviews');
      const reviewCards = document.querySelectorAll('.review-card');

      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            // Animate header
            if (entry.target.id === 'serviceHeader') {
              entry.target.classList.add('animate-fadeInUp');
            }

            // Animate service cards
            if (entry.target.id === 'serviceCards') {
              entry.target.classList.add('animate-fadeInUp');
            }

            // Animate reviews container & stagger individual cards
            if (entry.target.id === 'serviceReviews') {
              entry.target.classList.add('animate-fadeInUp');
              reviewCards.forEach((card, index) => {
                setTimeout(() => {
                  card.classList.add('animate-fadeInUp');
                }, index * 250); // staggered delay (250ms each)
              });
            }

            observer.unobserve(entry.target); // animate once per section
          }
        });
      }, {
        threshold: 0.2
      });

      observer.observe(header);
      observer.observe(cards);
      observer.observe(reviewsContainer);
    });
  </script>



  @include('layouts.footer')