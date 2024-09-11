 <!-- Logo -->

 <a href="{{ route('home') }}" class="flex items-center text-orange-500">
     <img src="{{ asset('storage/' . $settings->get('site_logo', 'default-logo.png')) }}" height="50px" width="180px"
         alt="logo">
 </a>
