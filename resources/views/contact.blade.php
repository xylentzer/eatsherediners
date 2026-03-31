  @include('layouts.header')

  <section class="bg-white mt-10 py-16 px-6 md:px-20 text-center border-t border-gray-200">
    <div class="max-w-3xl mx-auto animate-fadeIn">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Get in Touch with EatsHere Diner</h2>
      <p class="text-gray-700 mb-8">
        Have questions, bulk orders, or feedback?
        We’d love to hear from you! Reach out through our contact details below or send us a message on Facebook Messenger.
      </p>

      <!-- Contact Info -->
      <div class="space-y-4 text-lg text-gray-800">
        <p>
          📞 <span class="font-semibold text-red-600">Contact Number:</span>
          <a href="tel:+639264763460" class="hover:underline hover:text-red-700 transition">0926 476 3460</a>
        </p>
        <p>
          📧 <span class="font-semibold text-red-600">Email:</span>
          <a href="mailto:eats.here7@gmail.com" class="hover:underline hover:text-red-700 transition">eats.here7@gmail.com</a>
        </p>
        <p>
          💬 <span class="font-semibold text-red-600">Facebook Page:</span>
          <a href="https://www.facebook.com/profile.php?id=100090985355554"
            target="_blank"
            class="hover:underline hover:text-red-700 transition">
            facebook.com/eatshere.diner
          </a>
        </p>
        <p class="text-gray-600 italic">
          You can also reach us directly through <a href="https://www.facebook.com/profile.php?id=100090985355554" target="_blank" class="text-red-600 font-semibold hover:underline">Messenger</a> for faster replies.
        </p>
      </div>

      <!-- Divider -->
      <div class="w-24 h-1 bg-red-600 mx-auto my-8 rounded-full"></div>

      <!-- Google Maps Embed for Bulihan, Silang -->
      <div class="w-full h-64 md:h-96 rounded-xl overflow-hidden shadow-lg mb-8">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7729.372688333353!2d120.96596454194348!3d14.22304897427171!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd8131d18f6b91%3A0xe49dcde3ef1f9b9c!2sBulihan%2C%20Silang%2C%20Cavite!5e0!3m2!1sen!2sph!4v1730516000000!5m2!1sen!2sph"
          width="100%"
          height="100%"
          style="border:0;"
          allowfullscreen=""
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>

      <!-- Footer Message -->
      <p class="text-gray-600 italic">
        “Your next delicious and exclusive meal starts right here — at EatsHere Diner.”
      </p>
    </div>
  </section>

  <!-- Fade Animation -->
  <style>
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

    .animate-fadeIn {
      animation: fadeIn 1s ease-out;
    }
  </style>

  @include('layouts.footer')