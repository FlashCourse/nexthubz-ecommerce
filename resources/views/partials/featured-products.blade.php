   {{-- Featured Products --}}
   <section class="py-8 px-4">
       <div class="mx-auto max-w-7xl">
           <div class="text-center mb-8">
               <h2 class="text-3xl text-primary font-semibold mb-2">Featured Products</h2>
               <p class="text-muted">Check out our top picks</p>
           </div>
           <div class="grid grid-cols-2 gap-8 mx-auto max-w-7xl sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
               {{-- Displaying Livewire Product Cards --}}
               @foreach ($products as $product)
                   <x-product-card :product=$product />
               @endforeach
           </div>
           <div class="text-center mt-8">
               <a href="/product/search"
                   class="inline-block px-6 py-3 bg-primary text-light rounded-md hover:bg-secondary transition duration-300">See
                   More</a>
           </div>
       </div>
   </section>
