<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Certificate') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10 flex flex-col gap-y-5">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">#</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Nama</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Course</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Tanggal Dibuat</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $item)
                                <tr class="border-t border-gray-200 hover:bg-gray-50">
                                    <td class="px-4 py-2 text-sm text-gray-700">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-700">{{ $item->user->name }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-700">{{ $item->course->name }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-blue-600 hover:underline">
                                        <a href="{{ route('certificate.download', $item->id) }}">Download</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
