<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ auth()->user()->name }}'s Student Portal
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-4">
                        Welcome, {{ auth()->user()->name }}!
                    </h3>
                    <p class="mb-2">
                        You are logged in to the Student Portal.
                    </p>
                    <p class="mb-2">
                        <strong>Email:</strong> {{ auth()->user()->email }}
                    </p>
                    <p class="mb-2">
                        <strong>Course:</strong> Bachelor of Science in Information Technology (BSIT)
                    </p>
                    <p class="mb-2">
                        <strong>Year Level:</strong> 3rd Year
                    </p>
                    <p class="mb-2">
                        <strong>Section:</strong> Section A
                    </p>
                    <p class="mb-4">
                        <strong>Account Created:</strong> {{ auth()->user()->created_at->format('F d, Y') }}
                    </p>

                    <div class="mt-6">
                        <a href="{{ route('student.resources') }}"
                           class="inline-block rounded bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700 transition">
                            View Student Resources
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
