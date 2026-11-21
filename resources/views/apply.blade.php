@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Patrick+Hand&display=swap" rel="stylesheet">

<div class="min-h-screen bg-[#FFE600] px-4 py-10" style="font-family: 'Patrick Hand', cursive;">
    <div class="mx-auto max-w-3xl">

        <a href="{{ route('scholarships.index') }}"
           class="mb-4 inline-block rounded-xl border-2 border-black bg-white px-4 py-1 text-sm shadow-[3px_3px_0_0_#000] hover:bg-[#B8FF00] focus:outline-none focus-visible:ring-4 focus-visible:ring-black">
            Kembali ke daftar beasiswa
        </a>

        {{-- Ringkasan beasiswa --}}
        <section class="mb-6 rounded-3xl border-2 border-black bg-[#FFFDF5] p-6 shadow-[6px_6px_0_0_#000]">
            <h1 class="text-3xl leading-tight">{{ $scholarship->name }}</h1>
            <p class="mt-1 text-gray-700">{{ $scholarship->institution }}</p>

            <div class="mt-4 rounded-xl border-2 border-black bg-[#EEF1F8] p-3 text-sm">
                <p class="font-bold">Syarat utama:</p>
                <p>{{ $scholarship->requirements }}</p>
            </div>

            <div class="mt-4 flex flex-wrap items-center gap-3 text-sm">
                <div class="rounded-xl border-2 border-black bg-[#FFD9A8] px-4 py-2 text-center">
                    <span class="block text-xs">Sisa kuota</span>
                    <span class="text-xl">{{ $scholarship->remaining_quota }}/{{ $scholarship->quota }}</span>
                </div>
                <span class="rounded-md border-2 border-black bg-[#9ED8FF] px-2 py-1">
                    Dibuka: {{ $scholarship->open_date->translatedFormat('d F Y') }}
                </span>
                <span class="rounded-md border-2 border-black bg-[#FFD27A] px-2 py-1">
                    Ditutup: {{ $scholarship->close_date->translatedFormat('d F Y') }}
                </span>
            </div>
        </section>

        {{-- Form --}}
        <form method="POST"
              action="{{ route('applications.store', $scholarship) }}"
              enctype="multipart/form-data"
              class="rounded-3xl border-2 border-black bg-[#FFFDF5] p-6 shadow-[6px_6px_0_0_#000]">
            @csrf

            <h2 class="mb-4 text-2xl">Formulir pengajuan</h2>

            @if ($errors->any())
                <div class="mb-5 rounded-xl border-2 border-black bg-[#FF8A80] p-3 text-sm" role="alert">
                    <p class="font-bold">Pengajuan belum bisa dikirim:</p>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-5">
                <label for="name" class="mb-1 block">Nama pelamar</label>
                <input id="name" type="text" value="{{ auth()->user()->name }}" readonly
                       class="w-full rounded-xl border-2 border-black bg-gray-100 px-3 py-2">
            </div>

            <div class="mb-5 grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="semester" class="mb-1 block">Semester saat ini</label>
                    <input id="semester" name="semester" type="number" min="1" max="14" required
                           value="{{ old('semester') }}"
                           class="w-full rounded-xl border-2 border-black bg-[#B8FF00] px-3 py-2 focus:outline-none focus-visible:ring-4 focus-visible:ring-black">
                    <p class="mt-1 text-xs text-gray-600">Minimal semester {{ $scholarship->min_semester }}</p>
                </div>
                <div>
                    <label for="ipk" class="mb-1 block">IPK</label>
                    <input id="ipk" name="ipk" type="number" step="0.01" min="0" max="4" required
                           value="{{ old('ipk') }}"
                           class="w-full rounded-xl border-2 border-black bg-[#B8FF00] px-3 py-2 focus:outline-none focus-visible:ring-4 focus-visible:ring-black">
                    <p class="mt-1 text-xs text-gray-600">Minimal IPK {{ number_format($scholarship->min_ipk, 2) }}</p>
                </div>
            </div>

            <div class="mb-6">
                <label for="document" class="mb-1 block">Berkas persyaratan (PDF, maks. 2 MB)</label>
                <input id="document" name="document" type="file" accept="application/pdf" required
                       class="w-full rounded-xl border-2 border-black bg-white px-3 py-2 file:mr-3 file:rounded-lg file:border-2 file:border-black file:bg-[#9ED8FF] file:px-3 file:py-1 focus:outline-none focus-visible:ring-4 focus-visible:ring-black">
                <p class="mt-1 text-xs text-gray-600">Gabungkan transkrip nilai resmi dan dokumen pendukung dalam satu file PDF.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <button type="submit"
                        class="rounded-xl border-2 border-black bg-[#1E88E5] px-6 py-2 text-white shadow-[4px_4px_0_0_#000] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0_0_#000] focus:outline-none focus-visible:ring-4 focus-visible:ring-black">
                    Kirim pengajuan
                </button>
                <a href="{{ route('scholarships.index') }}"
                   class="rounded-xl border-2 border-black bg-white px-6 py-2 shadow-[4px_4px_0_0_#000] hover:bg-gray-100 focus:outline-none focus-visible:ring-4 focus-visible:ring-black">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection