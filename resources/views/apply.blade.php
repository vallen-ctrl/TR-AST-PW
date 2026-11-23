<x-app-layout>
    <div class="mx-auto max-w-4xl">
        <a href="{{ route('dashboard') }}"
           class="mb-5 inline-block rounded-xl border-[3px] border-black bg-white px-4 py-1.5 font-hand text-base shadow-[3px_3px_0_0_#000] transition hover:-translate-y-0.5 hover:bg-[#B8FF00] focus:outline-none focus-visible:ring-4 focus-visible:ring-black/30">
            Kembali
        </a>

        <div class="mb-6">
            <span class="neo-badge bg-[#8A2BE2] text-white">Pengajuan</span>
            <h1 class="mt-3 font-display text-3xl font-extrabold leading-tight sm:text-4xl">Ajukan beasiswa</h1>
        </div>

        <div class="space-y-6">

            {{-- Ringkasan beasiswa --}}
            <section class="neo-card p-5 sm:p-8">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <h2 class="font-display text-2xl font-extrabold leading-tight sm:text-3xl">{{ $beasiswa->Nama }}</h2>
                    <span class="neo-badge {{ $beasiswa->status === 'dibuka' ? 'bg-[#B8FF00]' : 'bg-[#FF8A80]' }} text-black">
                        {{ ucfirst($beasiswa->status) }}
                    </span>
                </div>

                <p class="mt-2 font-hand text-lg text-black/70">{{ $beasiswa->Deskripsi }}</p>

                <div class="mt-4 rounded-xl border-2 border-black bg-[#EEF1F8] p-3">
                    <p class="font-display text-sm font-extrabold">Syarat utama:</p>
                    <p class="font-hand text-base">{{ $beasiswa->SyaratUtama }}</p>
                </div>

                <div class="mt-4 flex flex-wrap items-center gap-3">
                    <div class="rounded-xl border-2 border-black bg-[#FFD9A8] px-4 py-2 text-center shadow-[2px_2px_0_0_#000]">
                        <span class="block font-hand text-xs">Sisa kuota</span>
                        <span class="font-display text-xl font-extrabold">{{ $beasiswa->JumlahKursi - $beasiswa->SudahMasuk }}/{{ $beasiswa->JumlahKursi }}</span>
                    </div>
                    <span class="rounded-md border-2 border-black bg-[#9ED8FF] px-2 py-1 font-hand text-sm">
                        Dibuka: {{ \Carbon\Carbon::parse($beasiswa->Dibuka)->translatedFormat('d F Y') }}
                    </span>
                    <span class="rounded-md border-2 border-black bg-[#FFD27A] px-2 py-1 font-hand text-sm">
                        Ditutup: {{ \Carbon\Carbon::parse($beasiswa->Ditutup)->translatedFormat('d F Y') }}
                    </span>
                    <span class="rounded-md border-2 border-black bg-white px-2 py-1 font-hand text-sm">
                        Jenjang: {{ $beasiswa->Jenjang }}
                    </span>
                </div>
            </section>

            {{-- Form --}}
            <section class="neo-card p-5 sm:p-8">
                <div class="max-w-xl">
                    <header>
                        <h2 class="font-display text-xl font-extrabold text-black">Formulir pengajuan</h2>
                        <p class="mt-1 font-hand text-base text-black/60">Isi data kampusmu dan unggah berkas persyaratan dalam satu file PDF.</p>
                    </header>

                    <form method="POST"
                          action="{{ route('applications.store', $beasiswa->id) }}"
                          enctype="multipart/form-data"
                          class="mt-6 space-y-5">
                        @csrf

                        @if ($errors->any())
                            <div class="rounded-xl border-2 border-black bg-[#FF8A80] p-3 font-hand text-base" role="alert">
                                <p class="font-display text-sm font-extrabold">Pengajuan belum bisa dikirim:</p>
                                <ul class="list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div>
                            <x-input-label for="pelamar" value="Nama pelamar" />
                            <x-text-input id="pelamar" type="text" class="mt-1 block w-full bg-gray-100" :value="auth()->user()->name" readonly />
                        </div>

                        <div>
                            <x-input-label for="NamaUniversitas" value="Nama universitas" />
                            <x-text-input id="NamaUniversitas" name="NamaUniversitas" type="text" maxlength="255" class="mt-1 block w-full" :value="old('NamaUniversitas')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('NamaUniversitas')" />
                        </div>

                        <div>
                            <x-input-label for="Semester" value="Semester saat ini" />
                            <x-text-input id="Semester" name="Semester" type="number" min="1" max="14" placeholder="Contoh: 5" class="mt-1 block w-full" :value="old('Semester')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('Semester')" />
                        </div>
                        
                        <div>
                            <x-input-label for="IPK" value="IPK" />
                            <x-text-input id="IPK" name="IPK" type="number" step="0.01" min="0" max="4" placeholder="Contoh: 3.75" class="mt-1 block w-full" :value="old('IPK')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('IPK')" />
                        </div>
                        
                        <div>
                            <x-input-label for="AlamatUniv" value="Alamat universitas" />
                            <textarea id="AlamatUniv" name="AlamatUniv" rows="3" required
                                      class="mt-1 block w-full rounded-xl border-[3px] border-black bg-white px-3 py-2 font-hand text-base focus:outline-none focus:ring-4 focus:ring-[#8A2BE2]/30">{{ old('AlamatUniv') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('AlamatUniv')" />
                        </div>

                        <div>
                            <x-input-label for="document" value="Berkas persyaratan (PDF, maks. 2 MB)" />
                            <input id="document" name="document" type="file" accept="application/pdf" required
                                   class="mt-1 block w-full rounded-xl border-[3px] border-black bg-white px-3 py-2 font-hand text-base file:mr-3 file:rounded-lg file:border-2 file:border-black file:bg-[#9ED8FF] file:px-3 file:py-1 file:font-hand focus:outline-none focus:ring-4 focus:ring-[#8A2BE2]/30">
                            <x-input-error class="mt-2" :messages="$errors->get('document')" />
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <x-primary-button>Kirim pengajuan</x-primary-button>
                            <a href="{{ route('dashboard') }}"
                               class="rounded-xl border-[3px] border-black bg-white px-4 py-2 font-hand text-base shadow-[3px_3px_0_0_#000] transition hover:-translate-y-0.5 hover:bg-[#B8FF00] focus:outline-none focus-visible:ring-4 focus-visible:ring-black/30">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>