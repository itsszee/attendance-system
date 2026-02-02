<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Dashboard
        </h2>
    </x-slot>

    <div class="p-6">
        Halo, {{ auth()->user()->name }} 👋
    </div>
</x-app-layout>
