<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Student Resources
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-xl font-bold mb-4">
                        Hello, {{ auth()->user()->name }}!
                    </h3>
                    <p class="mb-4">
                        These learning resources are available to registered students.
                    </p>
                    <ul class="list-disc pl-6 space-y-2">
                        <li>Lesson 1: Introduction to Laravel</li>
                        <li>Lesson 2: Routes and Controllers</li>
                        <li>Lesson 3: Database Migrations</li>
                        <li>Lesson 4: Laravel Breeze Authentication</li>
                        <li>Lesson 5: Blade Templating and Components</li>
                        <li>Lesson 6: Eloquent ORM and Database Relationships</li>
                    </ul>

                    <a href="{{ route('dashboard') }}" class="inline-block mt-6 text-indigo-600 underline">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
