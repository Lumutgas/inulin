@extends('admin.layout', ['pageTitle' => 'KATALOG LAYANAN'])

@section('content')
<div class="space-y-6" x-data="{
    createModal: false,
    editModal: false,
    editData: {
        id: '',
        name: '',
        slug: '',
        price: '',
        duration: '',
        description: '',
        included_items: '',
        supported_os: '',
        active: true
    },

    openEdit(service) {
        this.editData = {
            id: service.id,
            name: service.name,
            slug: service.slug,
            price: service.price,
            duration: service.duration,
            description: service.description,
            included_items: (service.included_items || []).join('\n'),
            supported_os: (service.supported_os || []).join(', '),
            active: service.active
        };
        this.editModal = true;
    }
}">

    <!-- Top Action Bar -->
    <div class="rounded-xl bg-[#121822] border border-slate-800 p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h2 class="font-heading font-bold text-lg text-white">Daftar Katalog Layanan</h2>
            <p class="text-xs text-slate-400 font-mono mt-0.5">Perubahan harga langsung berdampak pada halaman publik & ringkasan booking.</p>
        </div>

        <button type="button" @click="createModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-mono text-xs font-bold transition-all shadow-sm">
            <span class="material-symbols-outlined text-[18px]">add</span>
            <span>Tambah Layanan Baru</span>
        </button>
    </div>

    <!-- Services Table -->
    <div class="rounded-xl border border-slate-800 bg-[#121822] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#0C111D] border-b border-slate-800 text-slate-400 font-mono uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="p-4">Nama Layanan</th>
                        <th class="p-4">Tarif Database</th>
                        <th class="p-4">Durasi</th>
                        <th class="p-4">Paket & OS</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($services as $service)
                        <tr class="hover:bg-[#161F2C] transition-colors {{ $service->trashed() ? 'opacity-50' : '' }}">
                            <td class="p-4">
                                <div class="font-bold text-white text-sm">{{ $service->name }}</div>
                                <div class="font-mono text-slate-400 text-[11px] mt-0.5">/services/{{ $service->slug }}</div>
                                <div class="text-slate-400 line-clamp-1 mt-1 max-w-sm">{{ $service->description }}</div>
                            </td>

                            <td class="p-4 font-mono font-bold text-amber-400 text-sm whitespace-nowrap">
                                Rp{{ number_format($service->price, 0, ',', '.') }}
                            </td>

                            <td class="p-4 font-mono text-slate-300 whitespace-nowrap">
                                {{ $service->duration ?: 60 }} Menit
                            </td>

                            <td class="p-4 text-[11px] text-slate-400">
                                <div>{{ count($service->included_items ?? []) }} Item Termasuk</div>
                                <div class="text-slate-500 truncate max-w-[200px]">{{ implode(', ', $service->supported_os ?? []) }}</div>
                            </td>

                            <td class="p-4 whitespace-nowrap">
                                <form method="POST" action="{{ route('admin.services.toggle', $service) }}">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-mono font-semibold transition-colors {{ $service->active ? 'bg-emerald-950/60 text-emerald-400 border border-emerald-800/40 hover:bg-emerald-900/60' : 'bg-slate-800 text-slate-500 border border-slate-700 hover:text-white' }}">
                                        {{ $service->active ? '• AKTIF' : 'NONAKTIF' }}
                                    </button>
                                </form>
                            </td>

                            <td class="p-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" @click="openEdit({{ json_encode($service) }})" class="p-1.5 rounded bg-slate-800 hover:bg-slate-700 text-amber-400 hover:text-amber-300" title="Edit Layanan">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                    </button>

                                    <form method="POST" action="{{ route('admin.services.delete', $service) }}" onsubmit="return confirm('Hapus layanan {{ $service->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded bg-slate-800 hover:bg-rose-950/40 text-slate-400 hover:text-rose-400" title="Hapus Layanan">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-500 font-mono">
                                Belum ada katalog layanan tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($services->hasPages())
            <div class="p-4 border-t border-slate-800 bg-[#0C111D]">
                {{ $services->links() }}
            </div>
        @endif
    </div>

    <!-- CREATE MODAL -->
    <div x-show="createModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm overflow-y-auto">
        <div @click.away="createModal = false" class="w-full max-w-lg rounded-2xl bg-[#121822] border border-slate-800 p-6 shadow-2xl space-y-4 my-8">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="font-heading font-bold text-base text-white">Tambah Layanan Baru</h3>
                <button type="button" @click="createModal = false" class="text-slate-400 hover:text-white">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.services.store') }}" class="space-y-4 text-xs">
                @csrf

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Nama Layanan *</label>
                    <input type="text" name="name" required placeholder="cth. Instal Ulang Windows" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Tarif (Rp) *</label>
                        <input type="number" name="price" required min="0" step="5000" placeholder="50000" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Durasi (Menit)</label>
                        <input type="number" name="duration" min="15" value="60" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Deskripsi Lengkap *</label>
                    <textarea name="description" rows="3" required placeholder="Deskripsi teknis dan manfaat layanan..." class="w-full bg-[#070A0F] border border-slate-800 rounded-lg p-3 text-white focus:outline-none focus:border-[#F59E0B]"></textarea>
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Item yang Termasuk (1 per baris)</label>
                    <textarea name="included_items" rows="3" placeholder="Sistem Operasi Resmi (64-Bit)&#10;Driver Lengkap & Teruji&#10;Garansi 14 Hari" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg p-3 text-white font-mono focus:outline-none focus:border-[#F59E0B]"></textarea>
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Sistem Operasi yang Didukung (Pisahkan Koma)</label>
                    <input type="text" name="supported_os" placeholder="Windows 11 Pro, Windows 10, Ubuntu LTS" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white font-mono focus:outline-none focus:border-[#F59E0B]">
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" id="create_active" name="active" value="1" checked class="rounded bg-[#070A0F] border-slate-700 text-[#F59E0B]">
                    <label for="create_active" class="text-slate-300">Aktifkan layanan di katalog publik</label>
                </div>

                <div class="pt-4 border-t border-slate-800 flex justify-end gap-2">
                    <button type="button" @click="createModal = false" class="px-4 py-2 rounded-lg bg-slate-800 text-slate-300 font-mono">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-[#F59E0B] text-slate-950 font-mono font-bold">
                        Simpan Layanan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm overflow-y-auto">
        <div @click.away="editModal = false" class="w-full max-w-lg rounded-2xl bg-[#121822] border border-slate-800 p-6 shadow-2xl space-y-4 my-8">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="font-heading font-bold text-base text-white">Edit Katalog Layanan</h3>
                <button type="button" @click="editModal = false" class="text-slate-400 hover:text-white">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <form method="POST" :action="'/admin/services/' + editData.id" class="space-y-4 text-xs">
                @csrf @method('PATCH')

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Nama Layanan *</label>
                    <input type="text" name="name" x-model="editData.name" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Slug URL *</label>
                    <input type="text" name="slug" x-model="editData.slug" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Tarif (Rp) *</label>
                        <input type="number" name="price" x-model="editData.price" required min="0" step="5000" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Durasi (Menit)</label>
                        <input type="number" name="duration" x-model="editData.duration" min="15" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Deskripsi Lengkap *</label>
                    <textarea name="description" x-model="editData.description" rows="3" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg p-3 text-white focus:outline-none focus:border-[#F59E0B]"></textarea>
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Item yang Termasuk (1 per baris)</label>
                    <textarea name="included_items" x-model="editData.included_items" rows="3" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg p-3 text-white font-mono focus:outline-none focus:border-[#F59E0B]"></textarea>
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Sistem Operasi yang Didukung (Pisahkan Koma)</label>
                    <input type="text" name="supported_os" x-model="editData.supported_os" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white font-mono focus:outline-none focus:border-[#F59E0B]">
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" id="edit_active" name="active" value="1" :checked="editData.active" class="rounded bg-[#070A0F] border-slate-700 text-[#F59E0B]">
                    <label for="edit_active" class="text-slate-300">Aktifkan layanan di katalog publik</label>
                </div>

                <div class="pt-4 border-t border-slate-800 flex justify-end gap-2">
                    <button type="button" @click="editModal = false" class="px-4 py-2 rounded-lg bg-slate-800 text-slate-300 font-mono">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-[#F59E0B] text-slate-950 font-mono font-bold">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
