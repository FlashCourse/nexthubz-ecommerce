<x-app-layout>
    <section class="py-20 bg-secondary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center text-white">
            <div class="lg:text-center">
                <h2 class="text-orange-500 font-semibold tracking-wide text-4xl uppercase">Contact Us</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight sm:text-4xl">We'd Love to Hear From You
                </p>
                <p class="mt-4 max-w-2xl text-xl lg:mx-auto">Whether you have a question about features, pricing, need a
                    demo, or anything else, our team is ready to answer all your questions.</p>
            </div>
        </div>
    </section>

    <section class="bg-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-8 shadow-2xl rounded-lg">
                <h3 class="text-2xl font-semibold text-gray-900">Get In Touch</h3>
                <p class="mt-4 text-gray-600">Feel free to reach out to us using the form below. We aim to respond to
                    all queries within 24 hours.</p>
                <form action="" method="POST" class="mt-8 space-y-6">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" required
                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" required
                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="tel" name="phone" id="phone"
                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                        <textarea id="message" name="message" rows="4" required
                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                    </div>
                    <div class="text-right">
                        <x-button type="submit">Send
                            Message</x-button>
                    </div>
                </form>
            </div>
            <div class="bg-white p-8 shadow-2xl rounded-lg">
                <h3 class="text-2xl font-semibold text-gray-900">Contact Information</h3>
                <p class="mt-4 text-gray-600">You can also reach us via the following methods:</p>
                <ul class="mt-8 space-y-4">
                    <li class="flex items-center bg-gray-100 rounded-lg p-4">
                        <span class="bg-primary rounded-full p-2 flex items-center justify-center">
                            <i class="fa-solid fa-location-dot text-white"></i>
                        </span>
                        <span class="ml-3 text-gray-900">House No: 15 (4/B), Road No: 21, Sector-11, Uttara, Dhaka-1230,
                            Bangladesh</span>
                    </li>
                    <li class="flex items-center bg-gray-100 rounded-lg p-4">
                        <span class="bg-primary rounded-full p-2 flex items-center justify-center">
                            <i class="fas fa-phone-alt text-white"></i>
                        </span>
                        <span class="ml-3 text-gray-900">+88 01332-538580, +88 01332-538581</span>
                    </li>
                    <li class="flex items-center bg-gray-100 rounded-lg p-4">
                        <span class="bg-primary rounded-full p-2 flex items-center justify-center">
                            <i class="fas fa-envelope text-white"></i>
                        </span>
                        <span class="ml-3 text-gray-900">Hotline: +88 09638-000380</span>
                    </li>
                </ul>
            </div>


        </div>
    </section>

    <section class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h3 class="text-2xl font-semibold text-gray-900">Our Location</h3>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">Visit us at our office at the location below.
                </p>
            </div>
            <div class="mt-8">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d116748.6148106898!2d90.38441!3d23.875636!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c41b3c239da9%3A0xc6a4810d301550e!2sNEXT%20HUBZ%20-%20Digital%20Marketing%20%26%20Software%20Development%20Agency!5e0!3m2!1sen!2sus!4v1720439885255!5m2!1sen!2sus"
                    width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>

            </div>
        </div>
    </section>
</x-app-layout>
