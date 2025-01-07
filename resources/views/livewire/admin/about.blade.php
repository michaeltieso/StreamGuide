<div class="min-h-screen bg-gray-900">
    <!-- Page Content -->
    <div class="flex min-h-screen">
        <x-sidebar :categories="$categories" :links="$links" />

        <!-- Main Content -->
        <div class="flex-1">
            <main class="p-6">
                <div class="space-y-6">
                    <!-- About Section -->
                    <div class="p-4 sm:p-8 bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-4xl">
                            <div class="flex items-center mb-8">
                                <div class="flex-shrink-0">
                                    <img src="https://github.com/michaeltieso.png" alt="Michael Tieso" class="h-24 w-24 rounded-full object-cover">
                                </div>
                                <div class="ml-6">
                                    <h1 class="text-2xl font-bold text-white">Michael Tieso</h1>
                                    <p class="text-lg text-gray-300">Founder of Reggio Digital</p>
                                </div>
                            </div>

                            <div class="prose prose-invert max-w-none">
                                <h2>About Me</h2>
                                <p>
                                    I'm Michael Tieso, the founder of Reggio Digital. With a passion for digital innovation and user experience, 
                                    I specialize in creating intuitive and efficient web solutions that help businesses thrive in the digital space.
                                </p>

                                <h2>About Reggio Digital</h2>
                                <p>
                                    Reggio Digital is a digital agency focused on delivering high-quality web applications and digital solutions. 
                                    We believe in creating tools that not only solve problems but also provide an exceptional user experience.
                                </p>

                                <h2>About This Application</h2>
                                <p>
                                    StreamGuide is a comprehensive solution for managing and organizing streaming content. Built with modern 
                                    technologies and best practices, it provides an intuitive interface for managing guides, FAQs, and quick links 
                                    for your streaming community.
                                </p>

                                <h3>Key Features</h3>
                                <ul>
                                    <li>Intuitive guide management system</li>
                                    <li>Customizable categories and organization</li>
                                    <li>Quick links management</li>
                                    <li>FAQ system for common questions</li>
                                    <li>Modern, responsive design</li>
                                    <li>Dark mode for comfortable viewing</li>
                                </ul>

                                <div class="mt-8">
                                    <h3>Connect With Me</h3>
                                    <div class="flex space-x-4 mt-4">
                                        <a href="https://github.com/michaeltieso" target="_blank" 
                                           class="text-gray-300 hover:text-white transition-colors duration-200">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                        <a href="https://reggiodigital.com" target="_blank"
                                           class="text-gray-300 hover:text-white transition-colors duration-200">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div> 