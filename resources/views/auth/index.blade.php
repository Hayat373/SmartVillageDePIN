<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Community Hub') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Community Resource Sharing</h1>
                    
                    @if($resources->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b">Resource Type</th>
                                        <th class="py-2 px-4 border-b">Amount</th>
                                        <th class="py-2 px-4 border-b">Shared By</th>
                                        <th class="py-2 px-4 border-b">Hedera Transaction</th>
                                        <th class="py-2 px-4 border-b">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($resources as $resource)
                                        <tr>
                                            <td class="py-2 px-4 border-b">{{ ucfirst($resource->type) }}</td>
                                            <td class="py-2 px-4 border-b">{{ $resource->amount }}</td>
                                            <td class="py-2 px-4 border-b">{{ $resource->user->name }}</td>
                                            <td class="py-2 px-4 border-b">
                                                <a href="https://testnet.mirrornode.hedera.com/api/v1/topics/{{ env('HEDERA_TOPIC_ID', '0.0.67890') }}/messages/{{ $resource->hedera_tx_id }}" 
                                                   target="_blank" class="text-blue-500 hover:underline">
                                                    {{ $resource->hedera_tx_id }}
                                                </a>
                                            </td>
                                            <td class="py-2 px-4 border-b">{{ $resource->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">No resources have been shared yet.</p>
                    @endif
                    
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