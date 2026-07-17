@php
    $selectedBiayaIds = $promo?->pembayarans?->pluck('id')->all() ?? [];
    $eligibleBiayaCount = $promoPembayarans
        ->when($promo?->jenjang, fn ($items) => $items->where('jenjang', $promo->jenjang))
        ->count();
    $biayaMode = $promo && count($selectedBiayaIds) !== $eligibleBiayaCount
        ? 'satu'
        : 'semua';
@endphp

<div class="form-section">
    <div class="section-title">
        <h4>Informasi Promo</h4>
    </div>

    <div class="form-grid">
        <div class="form-group">
            <label>Cakupan Gelombang</label>
            <select name="cakupan_gelombang">
                <option value="semua" @selected(! $promo?->gelombang_pendaftaran_id)>
                    Semua gelombang akan datang
                </option>
                <option value="satu" @selected($promo?->gelombang_pendaftaran_id)>
                    Salah satu gelombang
                </option>
            </select>
        </div>

        <div class="form-group" data-gelombang-field>
            <label>Pilih Gelombang</label>
            <select name="gelombang_pendaftaran_id">
                <option value="">Pilih gelombang</option>
                @foreach ($promoGelombangs as $gelombang)
                    <option value="{{ $gelombang->id }}"
                        @selected($promo?->gelombang_pendaftaran_id === $gelombang->id)>
                        {{ $gelombang->nama_gelombang }}
                        ({{ $gelombang->tanggal_mulai?->format('d/m/Y') }} -
                        {{ $gelombang->tanggal_selesai?->format('d/m/Y') }})
                    </option>
                @endforeach

                @if ($promo?->gelombangPendaftaran && ! $promoGelombangs->contains('id', $promo->gelombang_pendaftaran_id))
                    <option value="{{ $promo->gelombangPendaftaran->id }}" selected>
                        {{ $promo->gelombangPendaftaran->nama_gelombang }}
                    </option>
                @endif
            </select>
        </div>

        <div class="form-group">
            <label>Jenjang Promo</label>
            <select name="jenjang">
                <option value="" @selected(! $promo?->jenjang)>Semua Jenjang</option>
                <option value="SMP" @selected($promo?->jenjang === 'SMP')>SMP</option>
                <option value="SMK" @selected($promo?->jenjang === 'SMK')>SMK</option>
            </select>
        </div>

        <div class="form-group">
            <label>Cakupan Biaya</label>
            <select name="cakupan_biaya">
                <option value="semua" @selected($biayaMode === 'semua')>
                    Semua biaya sesuai jenjang
                </option>
                <option value="satu" @selected($biayaMode === 'satu')>
                    Pilih biaya
                </option>
            </select>
        </div>

        <div class="form-group full" data-biaya-list>
            <label>Pilih Biaya</label>
            <div class="checkbox-wrapper">
                @foreach ($promoPembayarans as $pembayaran)
                    <label>
                        <input type="checkbox"
                            name="pembayaran_ids[]"
                            value="{{ $pembayaran->id }}"
                            @checked(in_array($pembayaran->id, $selectedBiayaIds))>
                        {{ $pembayaran->jenjang }} -
                        {{ $pembayaran->nama_pembayaran }}
                        ({{ $pembayaran->kategori }})
                    </label>
                @endforeach
            </div>
        </div>

        <div class="form-group">
            <label>Tipe Promo</label>
            <select name="tipe">
                <option value="nominal" @selected(($promo?->tipe ?? 'nominal') === 'nominal')>
                    Nominal
                </option>
                <option value="persentase" @selected($promo?->tipe === 'persentase')>
                    Persentase
                </option>
                <option value="gratis_biaya" @selected($promo?->tipe === 'gratis_biaya')>
                    Gratis Biaya
                </option>
            </select>
        </div>

        <div class="form-group">
            <label>Nilai Promo</label>
            <input type="number"
                name="nilai"
                value="{{ $promo?->nilai ?? 0 }}"
                min="0"
                placeholder="Contoh: 20 atau 50000">
        </div>

        <div class="form-group">
            <label>Kuota Promo</label>
            <input type="number"
                name="kuota"
                value="{{ $promo?->kuota }}"
                min="1"
                placeholder="Kosongkan jika tanpa batas">
        </div>

        <div class="form-group">
            <label>Status Promo</label>
            <select name="is_active">
                <option value="1" @selected($promo?->is_active ?? true)>Aktif</option>
                <option value="0" @selected($promo && ! $promo->is_active)>Nonaktif</option>
            </select>
        </div>

        <div class="form-group full">
            <label>Nama Promo</label>
            <input type="text"
                name="nama_promo"
                value="{{ $promo?->nama_promo }}"
                placeholder="Contoh: Potongan DSP 10%">
        </div>

        <div class="form-group full">
            <label>Deskripsi Promo</label>
            <textarea name="keterangan"
                rows="4"
                placeholder="Tulis deskripsi singkat promo">{{ $promo?->keterangan }}</textarea>
        </div>
    </div>
</div>
