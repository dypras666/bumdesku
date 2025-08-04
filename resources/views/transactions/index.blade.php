@extends('adminlte::page')

@section('title', 'Daftar Transaksi')

@section('content_header')
    <h1>Daftar Transaksi</h1>
@stop

@section('content')
<div class="container-fluid" id="transactionApp">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Transaksi</h3>
                    <div class="card-tools">
                        <a href="{{ route('transactions.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Transaksi
                        </a>
                    </div>
                </div>
                
                <!-- Filter Form -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filterType">Jenis Transaksi</label>
                                <select id="filterType" class="form-control" v-model="filters.type" @change="loadTransactions">
                                    <option value="">Semua</option>
                                    <option value="income">Pemasukan</option>
                                    <option value="expense">Pengeluaran</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filterStatus">Status</label>
                                <select id="filterStatus" class="form-control" v-model="filters.status" @change="loadTransactions">
                                    <option value="">Semua</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Disetujui</option>
                                    <option value="rejected">Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="searchInput">Pencarian</label>
                                <input type="text" id="searchInput" class="form-control" 
                                       placeholder="Kode atau deskripsi..." 
                                       v-model="filters.search" 
                                       @input="debounceSearch">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="perPage">Per Halaman</label>
                                <select id="perPage" class="form-control" v-model="filters.per_page" @change="loadTransactions">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <button @click="resetFilters" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Reset Filter
                            </button>
                        </div>
                        <div class="col-md-6 text-right">
                            <span v-if="loading" class="text-muted">
                                <i class="fas fa-spinner fa-spin"></i> Memuat...
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Transaction Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Deskripsi</th>
                                <th>Akun</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Dibuat Oleh</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="loading">
                                <td colspan="9" class="text-center">
                                    <i class="fas fa-spinner fa-spin"></i> Memuat data...
                                </td>
                            </tr>
                            <tr v-else-if="transactions.length === 0">
                                <td colspan="9" class="text-center">Tidak ada data transaksi</td>
                            </tr>
                            <tr v-else v-for="transaction in transactions" :key="transaction.id">
                                <td>@{{ transaction.transaction_code }}</td>
                                <td>@{{ formatDate(transaction.transaction_date) }}</td>
                                <td>
                                    <span v-if="transaction.transaction_type === 'income'" class="badge badge-success">Pemasukan</span>
                                    <span v-else class="badge badge-danger">Pengeluaran</span>
                                </td>
                                <td>@{{ transaction.description }}</td>
                                <td>@{{ transaction.account ? transaction.account.nama_akun : '-' }}</td>
                                <td>@{{ formatCurrency(transaction.amount) }}</td>
                                <td>
                                    <span v-if="transaction.status === 'pending'" class="badge badge-warning">Pending</span>
                                    <span v-else-if="transaction.status === 'approved'" class="badge badge-success">Disetujui</span>
                                    <span v-else class="badge badge-danger">Ditolak</span>
                                </td>
                                <td>@{{ transaction.user ? transaction.user.name : '-' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a :href="`{{ route('transactions.index') }}/${transaction.id}`" 
                                           class="btn btn-info btn-sm" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <template v-if="transaction.status === 'pending'">
                                            <a :href="`{{ route('transactions.index') }}/${transaction.id}/edit`" 
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <button @click="approveTransaction(transaction)" 
                                                    class="btn btn-success btn-sm" 
                                                    title="Setujui">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            
                                            <button @click="showRejectModal(transaction)" 
                                                    class="btn btn-danger btn-sm" 
                                                    title="Tolak">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            
                                            <button @click="deleteTransaction(transaction)" 
                                                    class="btn btn-secondary btn-sm" 
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center" v-if="pagination.total > 0">
                    <div>
                        Menampilkan @{{ pagination.from }} sampai @{{ pagination.to }} 
                        dari @{{ pagination.total }} transaksi
                    </div>
                    <div>
                        <nav>
                            <ul class="pagination pagination-sm">
                                <li class="page-item" :class="{ disabled: pagination.current_page === 1 }">
                                    <button class="page-link" @click="changePage(pagination.current_page - 1)" 
                                            :disabled="pagination.current_page === 1">
                                        &laquo; Sebelumnya
                                    </button>
                                </li>
                                
                                <li v-for="page in visiblePages" :key="page" 
                                    class="page-item" :class="{ active: page === pagination.current_page }">
                                    <button class="page-link" @click="changePage(page)">@{{ page }}</button>
                                </li>
                                
                                <li class="page-item" :class="{ disabled: pagination.current_page === pagination.last_page }">
                                    <button class="page-link" @click="changePage(pagination.current_page + 1)" 
                                            :disabled="pagination.current_page === pagination.last_page">
                                        Selanjutnya &raquo;
                                    </button>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Transaksi</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Yakin ingin menolak transaksi <strong>@{{ selectedTransaction ? selectedTransaction.transaction_code : '' }}</strong>?</p>
                    <div class="form-group">
                        <label for="rejectNotes">Catatan Penolakan</label>
                        <textarea v-model="rejectNotes" id="rejectNotes" class="form-control" rows="3" required 
                                  placeholder="Masukkan alasan penolakan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button @click="rejectTransaction" class="btn btn-danger" :disabled="!rejectNotes.trim()">
                        Tolak Transaksi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
new Vue({
    el: '#transactionApp',
    data: {
        transactions: [],
        loading: false,
        filters: {
            type: '',
            status: '',
            search: '',
            per_page: 25
        },
        pagination: {
            current_page: 1,
            last_page: 1,
            per_page: 25,
            total: 0,
            from: 0,
            to: 0
        },
        selectedTransaction: null,
        rejectNotes: '',
        searchTimeout: null
    },
    mounted() {
        this.loadTransactions();
    },
    computed: {
        visiblePages() {
            const current = this.pagination.current_page;
            const last = this.pagination.last_page;
            const pages = [];
            
            // Show first page
            if (current > 3) pages.push(1);
            if (current > 4) pages.push('...');
            
            // Show pages around current
            for (let i = Math.max(1, current - 2); i <= Math.min(last, current + 2); i++) {
                pages.push(i);
            }
            
            // Show last page
            if (current < last - 3) pages.push('...');
            if (current < last - 2) pages.push(last);
            
            return pages;
        }
    },
    methods: {
        loadTransactions(page = 1) {
            this.loading = true;
            
            const params = {
                page: page,
                ...this.filters
            };
            
            axios.get('/api/transactions', { params })
                .then(response => {
                    this.transactions = response.data.data;
                    this.pagination = response.data.pagination;
                })
                .catch(error => {
                    console.error('Error loading transactions:', error);
                    this.$toast.error('Gagal memuat data transaksi');
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        
        debounceSearch() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.loadTransactions();
            }, 500);
        },
        
        resetFilters() {
            this.filters = {
                type: '',
                status: '',
                search: '',
                per_page: 25
            };
            this.loadTransactions();
        },
        
        changePage(page) {
            if (page >= 1 && page <= this.pagination.last_page) {
                this.loadTransactions(page);
            }
        },
        
        approveTransaction(transaction) {
            if (!confirm('Yakin ingin menyetujui transaksi ini?')) return;
            
            axios.post(`/api/transactions/${transaction.id}/approve`)
                .then(response => {
                    this.$toast.success('Transaksi berhasil disetujui');
                    this.loadTransactions(this.pagination.current_page);
                })
                .catch(error => {
                    console.error('Error approving transaction:', error);
                    this.$toast.error(error.response?.data?.message || 'Gagal menyetujui transaksi');
                });
        },
        
        showRejectModal(transaction) {
            this.selectedTransaction = transaction;
            this.rejectNotes = '';
            $('#rejectModal').modal('show');
        },
        
        rejectTransaction() {
            if (!this.rejectNotes.trim()) return;
            
            axios.post(`/api/transactions/${this.selectedTransaction.id}/reject`, {
                notes: this.rejectNotes
            })
            .then(response => {
                this.$toast.success('Transaksi berhasil ditolak');
                $('#rejectModal').modal('hide');
                this.loadTransactions(this.pagination.current_page);
            })
            .catch(error => {
                console.error('Error rejecting transaction:', error);
                this.$toast.error(error.response?.data?.message || 'Gagal menolak transaksi');
            });
        },
        
        deleteTransaction(transaction) {
            if (!confirm('Yakin ingin menghapus transaksi ini?')) return;
            
            axios.delete(`/api/transactions/${transaction.id}`)
                .then(response => {
                    this.$toast.success('Transaksi berhasil dihapus');
                    this.loadTransactions(this.pagination.current_page);
                })
                .catch(error => {
                    console.error('Error deleting transaction:', error);
                    this.$toast.error(error.response?.data?.message || 'Gagal menghapus transaksi');
                });
        },
        
        formatDate(date) {
            return new Date(date).toLocaleDateString('id-ID');
        },
        
        formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }
    }
});

// Toast notification helper
Vue.prototype.$toast = {
    success: function(message) {
        toastr.success(message);
    },
    error: function(message) {
        toastr.error(message);
    }
};
</script>
@stop