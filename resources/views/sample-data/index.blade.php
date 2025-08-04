@extends('adminlte::page')

@section('title', 'Import Data Sampel - ' . company_info('name'))

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Import Data Sampel</h1>
            <p class="text-muted">Kelola data sampel untuk testing dan demo sistem</p>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Import Data Sampel</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <!-- Current Data Status -->
    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-database"></i>
                    Status Data Saat Ini
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="description-block border-right">
                            <span class="description-percentage text-success">
                                <i class="fas fa-coins"></i>
                            </span>
                            <h5 class="description-header">{{ number_format($dataCounts['transactions']) }}</h5>
                            <span class="description-text">Transaksi</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="description-block">
                            <span class="description-percentage text-info">
                                <i class="fas fa-book"></i>
                            </span>
                            <h5 class="description-header">{{ number_format($dataCounts['general_ledgers']) }}</h5>
                            <span class="description-text">Buku Besar</span>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-4">
                        <div class="description-block border-right">
                            <h5 class="description-header">{{ number_format($dataCounts['master_accounts']) }}</h5>
                            <span class="description-text">Akun</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="description-block border-right">
                            <h5 class="description-header">{{ number_format($dataCounts['master_units']) }}</h5>
                            <span class="description-text">Unit</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="description-block">
                            <h5 class="description-header">{{ number_format($dataCounts['master_inventories']) }}</h5>
                            <span class="description-text">Inventori</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sample Data Import Options -->
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-download"></i>
                    Pilihan Import Data Sampel
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Informasi:</strong> Data sampel berguna untuk testing dan demo sistem. 
                    Pilih set data yang ingin diimpor sesuai kebutuhan.
                </div>

                @foreach($sampleDataSets as $key => $dataSet)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="card-title mb-1">
                                    @if($key === 'complete_sample')
                                        <i class="fas fa-star"></i>
                                    @elseif($key === 'master_data')
                                        <i class="fas fa-cogs"></i>
                                    @elseif($key === 'transaction_data')
                                        <i class="fas fa-exchange-alt"></i>
                                    @elseif($key === 'ledger_data')
                                        <i class="fas fa-book"></i>
                                    @else
                                        <i class="fas fa-chart-line"></i>
                                    @endif
                                    {{ $dataSet['name'] }}
                                </h5>
                                <p class="card-text text-muted mb-2">{{ $dataSet['description'] }}</p>
                                <small class="text-info">
                                    <i class="fas fa-database"></i>
                                    Estimasi: {{ $dataSet['estimated_records'] }} record
                                </small>
                                @if(isset($dataSet['requires']))
                                    <br>
                                    <small class="text-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Memerlukan: {{ implode(', ', $dataSet['requires']) }}
                                    </small>
                                @endif
                            </div>
                            <div class="col-md-4 text-right">
                                <button type="button" 
                                        class="btn btn-outline-info btn-sm mb-2" 
                                        onclick="previewData('{{ $key }}')">
                                    <i class="fas fa-eye"></i> Preview
                                </button>
                                <br>
                                <button type="button" 
                                        class="btn btn-primary" 
                                        onclick="importData('{{ $key }}', '{{ $dataSet['name'] }}')">
                                    <i class="fas fa-download"></i> Import
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Import Confirmation Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <i class="fas fa-download"></i>
                    Konfirmasi Import Data
                </h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="importForm" method="POST" action="{{ route('sample-data.import') }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Perhatian:</strong> Import data sampel akan menambahkan data baru ke sistem. 
                        Pastikan Anda memahami konsekuensinya.
                    </div>
                    
                    <p>Anda akan mengimpor: <strong id="importDataSetName"></strong></p>
                    
                    <div id="dependencyCheck" class="alert alert-danger" style="display: none;">
                        <i class="fas fa-times-circle"></i>
                        <strong>Dependensi tidak terpenuhi:</strong>
                        <ul id="missingDependencies"></ul>
                    </div>
                    
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="confirmImport" name="confirm_import" required>
                            <label class="custom-control-label" for="confirmImport">
                                Saya memahami dan ingin melanjutkan import data sampel
                            </label>
                        </div>
                    </div>
                    
                    <input type="hidden" name="data_set" id="dataSetInput">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="confirmImportBtn">
                        <i class="fas fa-download"></i> Import Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <i class="fas fa-eye"></i>
                    Preview Data Sampel
                </h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="previewContent">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin"></i> Memuat preview...
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
function importData(dataSet, dataSetName) {
    $('#importDataSetName').text(dataSetName);
    $('#dataSetInput').val(dataSet);
    $('#confirmImport').prop('checked', false);
    $('#dependencyCheck').hide();
    
    // Check dependencies
    $.get('/sample-data/check-dependencies', {data_set: dataSet})
        .done(function(response) {
            if (!response.can_import) {
                $('#dependencyCheck').show();
                $('#missingDependencies').empty();
                response.missing_dependencies.forEach(function(dep) {
                    $('#missingDependencies').append('<li>' + dep + '</li>');
                });
                $('#confirmImportBtn').prop('disabled', true);
            } else {
                $('#confirmImportBtn').prop('disabled', false);
            }
        });
    
    $('#importModal').modal('show');
}

function previewData(dataSet) {
    $('#previewModal').modal('show');
    $('#previewContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Memuat preview...</div>');
    
    $.get('/sample-data/preview/' + dataSet)
        .done(function(response) {
            let content = '<h5>' + response.description + '</h5>';
            content += '<ul class="list-group list-group-flush">';
            response.sample_data.forEach(function(item) {
                content += '<li class="list-group-item">' + item + '</li>';
            });
            content += '</ul>';
            $('#previewContent').html(content);
        })
        .fail(function() {
            $('#previewContent').html('<div class="alert alert-danger">Gagal memuat preview data.</div>');
        });
}

// Enable/disable import button based on checkbox
$('#confirmImport').change(function() {
    if (!$('#dependencyCheck').is(':visible')) {
        $('#confirmImportBtn').prop('disabled', !this.checked);
    }
});
</script>
@stop