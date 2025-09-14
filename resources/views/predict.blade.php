<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AI Prediction') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Resource Allocation Prediction</h1>
                    
                    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6">
                        <p class="font-bold">Total Resources Shared: {{ $totalResources }}</p>
                    </div>
                    
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                        <p class="font-bold">Prediction: {{ $prediction }}</p>
                    </div>
                    
                    <div class="mt-6">
                        <p class="mb-4">This prediction is based on a simple rule-based AI:</p>
                        <ul class="list-disc pl-6 mb-4">
                            <li>If total shared resources > 50: "High demand - allocate more!"</li>
                            <li>Otherwise: "Low demand"</li>
                        </ul>
                        <p>In a production system, this would be replaced with more sophisticated machine learning algorithms.</p>
                    </div>
                    
                    <div class="mt-6">
                        <a href="{{ route('dashboard') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
