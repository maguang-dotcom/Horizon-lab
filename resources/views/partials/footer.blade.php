<footer class="bg-slate-900 text-slate-400 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">

        <div>
            <span class="text-xl">
                <span class="font-bold text-white">Horizon</span>
                <span class="font-light text-slate-300">-LAB</span>
            </span>
            <p class="mt-4 text-sm leading-relaxed">
               kitgum district, Uganda<br>
                P.O. Box 1234, Kitgum
            </p>
            <p class="mt-3 text-sm">
                 <a href="mailto:info@horizon-lab.com" class="hover:text-white">info@horizon-lab.com</a>
            </p>
            <p class="text-sm">
                <a href="tel:+256700000000" class="hover:text-white">+256 700 000 000</a>
            </p>
        </div>

        <div>
            <h4 class="text-white font-semibold mb-4 text-sm">Explore</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('home') }}" class="hover:text-white">Home</a></li>
                <li><a href="{{ route('about-Us') }}" class="hover:text-white">About Us</a></li>
                <li><a href="{{ route('services-Repair') }}" class="hover:text-white">Service &amp; Repairs</a></li>
                <li><a href="{{ route('work-with-Us') }}" class="hover:text-white">Work With Us</a></li>
                <li><a href="{{ route('service-requests.create') }}" class="hover:text-white">Request a service</a></li>
            </ul>
        </div>

        <div>
            <h4 class="text-white font-semibold mb-4 text-sm">Services</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('services-Repair') }}" class="hover:text-white">Equipment servicing</a></li>
                <li><a href="{{ route('services-Repair') }}" class="hover:text-white">Repairs and testing</a></li>
                <li><a href="{{ route('services-Repair') }}" class="hover:text-white">Medical furniture</a></li>
            </ul>
        </div>

        <div>
            <h4 class="text-white font-semibold mb-4 text-sm">Get started</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('register') }}" class="hover:text-white">Request a service</a></li>
                <li><a href="{{ route('register') }}" class="hover:text-white">Join the network</a></li>
                <li><a href="mailto:info@horizon-lab.com" class="hover:text-white">Email our team</a></li>
            </ul>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 border-t border-slate-800 pt-6">
        <p class="text-sm text-center mb-3">
            For all your mobility needs, don't hesitate to contact us!
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
            <p>&copy; {{ date('Y') }} Horizon-LAB Ltd &nbsp;|&nbsp; Site by <a href="https://adandco.com/" class="hover:text-white" target="_blank" rel="noopener">AD&amp;CO</a></p>
            <a href="mailto:horizonlabs2025@gmail.com" class="hover:text-white">Contact the team</a>
        </div>
    </div>
</footer>