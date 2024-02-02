<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('No Surat') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="pengajuan_no_surat" class="form-control" placeholder="{{ __('No Surat') }}"
                    value="{{ $pj->no_surat }}" disabled>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Perihal Pembayaran') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="perihal" class="form-control" placeholder="{{ __('Perihal Pembayaran') }}"
                    value="{{ $pj->perihal  }}" disabled>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Nominal Pembayaran') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="nominal" class="form-control" placeholder="{{ __('Nominal') }}"
                    value="{{ 'Rp '.number_format($pj->aktiva->getTotalHarga(), 0, ',', '.') }}" disabled>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Bank') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="bank" class="form-control" placeholder="{{ __('Bank') }}"
                    value="{{ $rekening->bank->name  }}" disabled>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('No Rekening') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="no_rekening" class="form-control" placeholder="{{ __('No Rekening') }}"
                    value="{{ $rekening->no_rekening }}" disabled>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Nama Pemilik Rekening') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="nama_pemilik_rekening" class="form-control" placeholder="{{ __('Nama Pemilik Rekening') }}"
                    value="{{ $rekening->pemilik->name  }}" disabled>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('No Handphone') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="phone" class="form-control" placeholder="{{ __('No Handphone') }}"
                    value="{{ $pic->phone }}" disabled>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Email') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="email" class="form-control" placeholder="{{ __('Email') }}"
                    value="{{ $pic->email  }}" disabled>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('File Pengajuan') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">

            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl Jatuh Tempo') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
               <input type="text" name="tgl_jatuh_tempo" class="form-control" placeholder="{{ __('Tgl Jatuh Tempo') }}"
                    value="{{ $pj->show_tgl_jatuh_tempo ?? $pj->getTglJatuhTempo()  }}" readonly>
            </div>
        </div>
    </div>
</div>


