<x-modal name="modal-category" focusable>
    <div class="p-6 space-y-6">

        <h2 class="text-lg font-semibold text-gray-800">
            Kelola Kategori
        </h2>

        <!-- CREATE -->
        <div class="flex gap-3">

            <input wire:model="name" type="text" placeholder="Nama kategori..."
                class="flex-1 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">

            <input wire:model="color" type="color" class="w-12 h-10 border rounded-lg">

            <button wire:click="{{ $editId ? 'update' : 'create' }}"
                class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
                {{ $editId ? 'Update' : 'Tambah' }}
            </button>

        </div>

        <!-- LIST -->
        <div class="space-y-2">

            @foreach ($categories as $cat)
                <div class="flex items-center justify-between p-3 bg-white border rounded-xl shadow-sm">

                    <div class="flex items-center gap-3">

                        <div class="w-4 h-4 rounded-full" style="background: {{ $cat->color }}"></div>

                        <span class="text-sm text-gray-700">
                            {{ $cat->name }}
                        </span>

                    </div>

                    <div class="flex gap-3 text-sm">

                        <button wire:click="startEdit({{ $cat->id }})"
                            class="text-indigo-600 hover:text-indigo-800">
                            Edit
                        </button>

                        <button wire:click="delete({{ $cat->id }})" class="text-red-500 hover:text-red-700">
                            Hapus
                        </button>

                    </div>

                </div>
            @endforeach

        </div>

    </div>
</x-modal>
