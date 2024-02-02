<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/home');
Auth::routes();

Route::middleware('auth')
    ->group(
        function () {
            Route::namespace('Dashboard')
                ->group(
                    function () {
                        Route::get('home', 'DashboardController@index')->name('home');
                        Route::post('progress', 'DashboardController@progress')->name('dashboard.progress');
                        Route::post('chartFinding', 'DashboardController@chartFinding')->name('dashboard.chartFinding');
                        Route::post('chartFollowup', 'DashboardController@chartFollowup')->name('dashboard.chartFollowup');
                        Route::post('chartStage', 'DashboardController@chartStage')->name('dashboard.chartStage');
                        Route::post('chartUmp', 'DashboardController@chartUmp')->name('dashboard.chartUmp');
                        Route::post('chartTermin', 'DashboardController@chartTermin')->name('dashboard.chartTermin');
                        Route::post('chartSaran', 'DashboardController@chartSaran')->name('dashboard.chartSaran');
                        Route::post('chartUser', 'DashboardController@chartUser')->name('dashboard.chartUser');
                        Route::post('chartStage', 'DashboardController@chartStage')->name('dashboard.chartStage');
                        Route::get('language/{lang}/setLang', 'DashboardController@setLang')->name('setLang');
                    }
                );

            Route::namespace('Monitoring')
                ->name('monitoring.')
                ->prefix('monitoring')
                ->group(
                    function () {
                        Route::grid('monitoring-aktiva', 'MonitoringAktivaController', [
                            'with' => ['submit', 'approval', 'reject', 'tracking', 'history'],
                        ]);
                        Route::grid('monitoring-sgu', 'MonitoringSguController', [
                            'with' => ['submit', 'approval', 'reject', 'tracking', 'history'],
                        ]);
                    }
                );

            // Laporan
            Route::namespace('Laporan')
                ->name('laporan.')
                ->prefix('laporan')
                ->group(
                    function () {
                        Route::grid('laporan-aktiva', 'LaporanAktivaController', [
                            'with' => ['submit', 'approval', 'reject', 'tracking', 'history'],
                        ]);
                        Route::grid('laporan-sgu', 'LaporanSguController', [
                            'with' => ['submit', 'approval', 'reject', 'tracking', 'history'],
                        ]);
                        // pembayaran
                        Route::post('laporan-pembayaran/grid', 'LaporanPembayaranController@grid')->name('laporan-pembayaran.grid');
                        Route::get('laporan-pembayaran', 'LaporanPembayaranController@index')->name('laporan-pembayaran.index');
                        Route::get('laporan-pembayaran/ump-pengajuan', 'LaporanPembayaranController@indexUmpPengajuan')->name('laporan-pembayaran.ump-pengajuan');
                        Route::post('laporan-pembayaran/gridUmpPengajuan', 'LaporanPembayaranController@gridUmpPengajuan')->name('laporan-pembayaran.gridUmpPengajuan');

                        Route::get('laporan-pembayaran/ump-perpanjangan', 'LaporanPembayaranController@indexUmpPerpanjangan')->name('laporan-pembayaran.ump-perpanjangan');
                        Route::post('laporan-pembayaran/gridUmpPerpanjangan', 'LaporanPembayaranController@gridUmpPerpanjangan')->name('laporan-pembayaran.gridUmpPerpanjangan');

                        Route::get('laporan-pembayaran/ump-pembayaran', 'LaporanPembayaranController@indexUmpPembayaran')->name('laporan-pembayaran.ump-pembayaran');
                        Route::post('laporan-pembayaran/gridUmpPembayaran', 'LaporanPembayaranController@gridUmpPembayaran')->name('laporan-pembayaran.gridUmpPembayaran');

                        Route::get('laporan-pembayaran/ump-pertanggungjawaban', 'LaporanPembayaranController@indexUmpPertanggungjawaban')->name('laporan-pembayaran.ump-pertanggungjawaban');
                        Route::post('laporan-pembayaran/gridUmpPertanggungjawaban', 'LaporanPembayaranController@gridUmpPertanggungjawaban')->name('laporan-pembayaran.gridUmpPertanggungjawaban');

                        Route::get('laporan-pembayaran/termin-pengajuan', 'LaporanPembayaranController@indexTerminPengajuan')->name('laporan-pembayaran.termin-pengajuan');
                        Route::post('laporan-pembayaran/gridTerminPengajuan', 'LaporanPembayaranController@gridTerminPengajuan')->name('laporan-pembayaran.gridTerminPengajuan');

                        Route::get('laporan-pembayaran/termin-pembayaran', 'LaporanPembayaranController@indexTerminPembayaran')->name('laporan-pembayaran.termin-pembayaran');
                        Route::post('laporan-pembayaran/gridTerminPembayaran', 'LaporanPembayaranController@gridTerminPembayaran')->name('laporan-pembayaran.gridTerminPembayaran');

                        // pelepasan
                        Route::grid('laporan-penghapusan', 'LaporanPenghapusanController', [
                            'with' => ['submit', 'approval', 'reject', 'tracking', 'history'],
                        ]);
                        Route::grid('laporan-penjualan', 'LaporanPenjualanController', [
                            'with' => ['submit', 'approval', 'reject', 'tracking', 'history'],
                        ]);
                        Route::grid('laporan-mutasi', 'LaporanMutasiController', [
                            'with' => ['submit', 'approval', 'reject', 'tracking', 'history'],
                        ]);
                        Route::grid('laporan-pemeriksaan', 'LaporanPemeriksaanController', [
                            'with' => ['submit', 'approval', 'reject', 'tracking', 'history'],
                        ]);
                    }
                );

            // Ajax
            Route::prefix('ajax')
                ->name('ajax.')
                ->group(
                    function () {
                        Route::post('saveTempFiles', 'AjaxController@saveTempFiles')->name('saveTempFiles');
                        Route::get('testNotification/{emails}', 'AjaxController@testNotification')->name('testNotification');
                        Route::post('userNotification', 'AjaxController@userNotification')->name('userNotification');
                        Route::get('userNotification/{notification}/read', 'AjaxController@userNotificationRead')->name('userNotificationRead');
                        // Ajax Modules
                        Route::get('city-options', 'AjaxController@cityOptions')->name('cityOptions');
                        Route::post('city-options-root', 'AjaxController@cityOptionsRoot')->name('cityOptionsRoot');
                        Route::post('selectObject', 'AjaxController@selectObject')->name('selectObject');
                        Route::post('selectMasaPenggunaan', 'AjaxController@selectMasaPenggunaan')->name('selectMasaPenggunaan');
                        Route::post('selectMataAnggaran', 'AjaxController@selectMataAnggaran')->name('selectMataAnggaran');
                        Route::post('selectCOA', 'AjaxController@selectCOA')->name('selectCOA');
                        Route::post('selectVendor', 'AjaxController@selectVendor')->name('selectVendor');
                        Route::post('{search}/selectRole', 'AjaxController@selectRole')->name('selectRole');
                        Route::post('{search}/selectStruct', 'AjaxController@selectStruct')->name('selectStruct');
                        Route::post('selectAktiva', 'AjaxController@selectAktiva')->name('selectAktiva');
                        Route::post('getAktivaById', 'AjaxController@getAktivaById')->name('getAktivaById');
                        Route::post('getBankAccountById', 'AjaxController@getBankAccountById')->name('getBankAccountById');
                        Route::post('selectPembelianAktiva', 'AjaxController@selectPembelianAktiva')->name('selectPembelianAktiva');
                        Route::post('selectPembelianAktivaById', 'AjaxController@getSelectPembelianAktivaById')->name('getSelectPembelianAktivaById');
                        Route::post('selectBankAccountById', 'AjaxController@getSelectBankAccountById')->name('getSelectBankAccountById');
                        Route::post('{search}/selectPosition', 'AjaxController@selectPosition')->name('selectPosition');
                        Route::post('{search}/selectUser', 'AjaxController@selectUser')->name('selectUser');
                        Route::post('{search}/selectKelompok', 'AjaxController@selectKelompok')->name('selectKelompok');
                        Route::post('{search}/selectBankAccount', 'AjaxController@selectBankAccount')->name('selectBankAccount');
                        Route::post('{search}/selectBank', 'AjaxController@selectBank')->name('selectBank');
                        Route::post('{search}/selectProvince', 'AjaxController@selectProvince')->name('selectProvince');
                        Route::post('{search}/selectCity', 'AjaxController@selectCity')->name('selectCity');
                        Route::post('{search}/provinceOptions', 'AjaxController@provinceOptions')->name('provinceOptions');
                    }
                );

            Route::namespace('Aktiva')
                ->group(
                    function () {
                        Route::grid('aktiva', 'AktivaController');
                        Route::post('aktiva/{record}/mutasi-grid', 'AktivaController@mutasiGrid')->name('aktiva.mutasiGrid');

                        Route::grid('pengajuan-pembelian', 'PembelianAktivaController', [
                            'with' => ['submit', 'approval', 'reject', 'tracking', 'history', 'print'],
                        ]); //ke index
                        
                        Route::post('pengajuan-pembelian/{id}/updateSummary', 'PembelianAktivaController@updateSummary')->name('pengajuan-pembelian.updateSummary');
                        // detail
                        Route::get('pengajuan-pembelian/{record}/detail', 'PembelianAktivaController@detail')->name('pengajuan-pembelian.detail');
                        Route::post('pengajuan-pembelian/{record}/detailGrid', 'PembelianAktivaController@detailGrid')->name('pengajuan-pembelian.detailGrid');
                        Route::get('pengajuan-pembelian/{record}/detailCreate', 'PembelianAktivaController@detailCreate')->name('pengajuan-pembelian.detailCreate');
                        Route::post('pengajuan-pembelian/{record}/detailStore', 'PembelianAktivaController@detailStore')->name('pengajuan-pembelian.detailStore');
                        Route::get('pengajuan-pembelian/{detail}/detailShow', 'PembelianAktivaController@detailShow')->name('pengajuan-pembelian.detailShow');
                        Route::get('pengajuan-pembelian/{detail}/detailEdit', 'PembelianAktivaController@detailEdit')->name('pengajuan-pembelian.detailEdit');
                        Route::patch('pengajuan-pembelian/{detail}/detailUpdate', 'PembelianAktivaController@detailUpdate')->name('pengajuan-pembelian.detailUpdate');
                        Route::delete('pengajuan-pembelian/{detail}/detailDestroy', 'PembelianAktivaController@detailDestroy')->name('pengajuan-pembelian.detailDestroy');
                    }
            );

            Route::namespace('OperationalCost')
                ->group(
                    function () {
                        Route::grid('operational-cost', 'OperationalCostController', [
                            'with' => ['submit', 'approval', 'reject', 'tracking', 'history', 'print'],
                        ]);
                        Route::post('operational-cost/{id}/updateSummary', 'OperationalCostController@updateSummary')->name('operational-cost.updateSummary');
                        // detail
                        Route::get('operational-cost/{record}/detail', 'OperationalCostController@detail')->name('operational-cost.detail');
                        Route::post('operational-cost/{record}/detailGrid', 'OperationalCostController@detailGrid')->name('operational-cost.detailGrid');
                        Route::get('operational-cost/{record}/detailCreate', 'OperationalCostController@detailCreate')->name('operational-cost.detailCreate');
                        Route::post('operational-cost/{record}/detailStore', 'OperationalCostController@detailStore')->name('operational-cost.detailStore');
                        Route::get('operational-cost/{detail}/detailShow', 'OperationalCostController@detailShow')->name('operational-cost.detailShow');
                        Route::get('operational-cost/{detail}/detailEdit', 'OperationalCostController@detailEdit')->name('operational-cost.detailEdit');
                        Route::patch('operational-cost/{detail}/detailUpdate', 'OperationalCostController@detailUpdate')->name('operational-cost.detailUpdate');
                        Route::delete('operational-cost/{detail}/detailDestroy', 'OperationalCostController@detailDestroy')->name('operational-cost.detailDestroy');
                    }
            );

            Route::namespace('Ump')
                ->name('ump.')
                ->prefix('ump')
                ->group(
                    function () {
                        Route::name('pengajuan-ump.')
                            ->prefix('pengajuan-ump')
                            ->group(
                                function () {
                                    Route::post('{record}/revise', 'PengajuanController@revise')->name('revise');
                                    Route::post('{record}/cancel', 'PengajuanController@cancel')->name('cancel');
                                    Route::get('{record}/verification', 'PengajuanController@verification')->name('verification');
                                    Route::post('{record}/verify', 'PengajuanController@verify')->name('verify');
                                    Route::get('{record}/payment', 'PengajuanController@payment')->name('payment');
                                    Route::post('{record}/pay', 'PengajuanController@pay')->name('pay');
                                    Route::get('{record}/confirmation', 'PengajuanController@confirmation')->name('confirmation');
                                    Route::post('{record}/confirm', 'PengajuanController@confirm')->name('confirm');
                                }
                            );
                        Route::grid('pengajuan-ump', 'PengajuanController', [
                            'with' => ['submit', 'reject', 'approval', 'tracking', 'history', 'create', 'store', 'print'],
                        ]);
                        
                        Route::name('pj-ump.')
                            ->prefix('pj-ump')
                            ->group(
                                function () {
                                    Route::post('{record}/revise', 'PjController@revise')->name('revise');
                                    Route::post('{record}/cancel', 'PjController@cancel')->name('cancel');
                                    Route::get('{record}/verification', 'PjController@verification')->name('verification');
                                    Route::post('{record}/verify', 'PjController@verify')->name('verify');
                                    Route::get('{record}/transfer', 'PjController@transfer')->name('transfer');
                                    Route::post('{record}/pay', 'PjController@pay')->name('pay');
                                    Route::get('{record}/detail/add', 'PjController@addDetail')->name('detail.add');
                                    Route::post('{record}/submitDetail', 'PjController@submitDetail')->name('detail.submitDetail');
                                    Route::post('{record}/detail', 'PjController@detailPenggunaanAnggaran')->name('detail');
                                    Route::post('{record}/detailJurnal', 'PjController@detailJurnalGrid')->name('detail.jurnal');
                                }
                            );
                        Route::grid('pj-ump', 'PjController', [
                            'with' => ['submit', 'approval', 'tracking', 'history', 'print'],
                        ]);    
                        
                        Route::name('pembayaran-ump.')
                        ->prefix('pembayaran-ump')
                        ->group(
                            function(){
                                Route::post('gridDetail', 'PembayaranController@gridDetail')->name('gridDetail');
                                Route::get('{record}/extend', 'PembayaranController@extend')->name('extend');
                                Route::post('{record}/revise', 'PembayaranController@revise')->name('revise');                    
                                Route::post('{record}/cancel', 'PembayaranController@cancel')->name('cancel');      
                                Route::get('{record}/verification', 'PembayaranController@verification')->name('verification');
                                Route::post('{record}/verify', 'PembayaranController@verify')->name('verify');
                            }
                        );
                        Route::grid('pembayaran-ump', 'PembayaranController', [
                            'with' => ['submit', 'approval', 'tracking', 'history', 'reject', 'print'],
                        ]);        

                        Route::name('perpanjangan-ump.')
                            ->prefix('perpanjangan-ump')
                            ->group(
                                function () {
                                    Route::post('gridDetail', 'PerpanjanganController@gridDetail')->name('gridDetail');
                                    Route::get('{record}/extend', 'PerpanjanganController@extend')->name('extend');
                                    Route::post('{record}/revise', 'PerpanjanganController@revise')->name('revise');
                                    Route::post('{record}/cancel', 'PerpanjanganController@cancel')->name('cancel');
                                    Route::get('{record}/verification', 'PerpanjanganController@verification')->name('verification');
                                    Route::post('{record}/verify', 'PerpanjanganController@verify')->name('verify');
                                }
                            );
                        Route::grid('perpanjangan-ump', 'PerpanjanganController', [
                            'with' => ['submit', 'approval', 'tracking', 'history', 'reject', 'print'],
                        ]);

                        Route::name('pembatalan-ump.')
                            ->prefix('pembatalan-ump')
                            ->group(
                                function () {
                                    Route::post('gridDetail', 'PembatalanController@gridDetail')->name('gridDetail');
                                    Route::get('{record}/extend', 'PembatalanController@extend')->name('extend');
                                    Route::post('{record}/revise', 'PembatalanController@revise')->name('revise');
                                    Route::post('{record}/cancel', 'PembatalanController@cancel')->name('cancel');
                                    Route::get('{record}/verification', 'PembatalanController@verification')->name('verification');
                                    Route::post('{record}/verify', 'PembatalanController@verify')->name('verify');
                                }
                            );
                        Route::grid('pembatalan-ump', 'PembatalanController', [
                            'with' => ['submit', 'approval', 'tracking', 'history', 'reject', 'print'],
                        ]);
                    }
                );
            Route::namespace('Termin')
                ->name('termin.')
                ->prefix('termin')
                ->group(
                    function () {
                        // pengajuan
                        Route::grid('pengajuan', 'TerminPengajuanController', [
                            'with' => ['submit', 'reject', 'approval', 'tracking', 'history', 'print'],
                        ]);
                        Route::post('pengajuan/{id}/updateSummary', 'TerminPengajuanController@updateSummary')->name('pengajuan.updateSummary');
                        // detail
                        Route::get('pengajuan/{record}/detail', 'TerminPengajuanController@detail')->name('pengajuan.detail');
                        Route::post('pengajuan/{record}/detailGrid', 'TerminPengajuanController@detailGrid')->name('pengajuan.detailGrid');
                        Route::get('pengajuan/{record}/detailCreate', 'TerminPengajuanController@detailCreate')->name('pengajuan.detailCreate');
                        Route::post('pengajuan/{record}/detailStore', 'TerminPengajuanController@detailStore')->name('pengajuan.detailStore');
                        Route::get('pengajuan/{detail}/detailShow', 'TerminPengajuanController@detailShow')->name('pengajuan.detailShow');
                        Route::patch('pengajuan/{detail}/detailEdit', 'TerminPengajuanController@detailEdit')->name('pengajuan.detailEdit');
                        Route::post('pengajuan/{detail}/detailUpdate', 'TerminPengajuanController@detailUpdate')->name('pengajuan.detailUpdate');
                        Route::delete('pengajuan/{detail}/detailDestroy', 'TerminPengajuanController@detailDestroy')->name('pengajuan.detailDestroy');

                        // pembayaran
                        Route::grid('pembayaran', 'TerminPembayaranController', [
                            'with' => ['submit', 'reject', 'approval', 'tracking', 'history', 'print'],
                        ]);
                        Route::post('pembayaran/{id}/updateSummary', 'TerminPembayaranController@updateSummary')->name('pembayaran.updateSummary');
                        // detail
                        Route::get('pembayaran/{record}/detail', 'TerminPembayaranController@detail')->name('pembayaran.detail');
                        Route::post('pembayaran/{record}/detailGrid', 'TerminPembayaranController@detailGrid')->name('pembayaran.detailGrid');
                        Route::get('pembayaran/{record}/detailCreate', 'TerminPembayaranController@detailCreate')->name('pembayaran.detailCreate');
                        Route::post('pembayaran/{record}/detailStore', 'TerminPembayaranController@detailStore')->name('pembayaran.detailStore');
                        Route::get('pembayaran/{detail}/detailShow', 'TerminPembayaranController@detailShow')->name('pembayaran.detailShow');
                        Route::get('pembayaran/{detail}/detailEdit', 'TerminPembayaranController@detailEdit')->name('pembayaran.detailEdit');
                        Route::patch('pembayaran/{detail}/detailUpdate', 'TerminPembayaranController@detailUpdate')->name('pembayaran.detailUpdate');
                        Route::delete('pembayaran/{detail}/detailDestroy', 'TerminPembayaranController@detailDestroy')->name('pembayaran.detailDestroy');
                    }
                );

                Route::namespace('Pemeriksaan')
                ->group(
                    function () {
                        Route::grid('pemeriksaan', 'PemeriksaanController', [
                            'with' => ['submit', 'approval', 'reject', 'tracking', 'history', 'print'],
                        ]);
                        // detail
                        Route::get('pemeriksaan/{record}/detail', 'PemeriksaanController@detail')->name('pemeriksaan.detail');
                        Route::post('pemeriksaan/{record}/detailGrid', 'PemeriksaanController@detailGrid')->name('pemeriksaan.detailGrid');
                        Route::get('pemeriksaan/{record}/detailCreate', 'PemeriksaanController@detailCreate')->name('pemeriksaan.detailCreate');
                        Route::post('pemeriksaan/{record}/detailStore', 'PemeriksaanController@detailStore')->name('pemeriksaan.detailStore');
                        Route::get('pemeriksaan/{detail}/detailShow', 'PemeriksaanController@detailShow')->name('pemeriksaan.detailShow');
                        Route::get('pemeriksaan/{detail}/detailEdit', 'PemeriksaanController@detailEdit')->name('pemeriksaan.detailEdit');
                        Route::patch('pemeriksaan/{detail}/detailUpdate', 'PemeriksaanController@detailUpdate')->name('pemeriksaan.detailUpdate');
                        Route::delete('pemeriksaan/{detail}/detailDestroy', 'PemeriksaanController@detailDestroy')->name('pemeriksaan.detailDestroy');
                    }
                );

            // Pelepasan Aktiva
            Route::namespace('PelepasanAktiva')
                ->name('pelepasan-aktiva.')
                ->group(
                    function () {
                        // PENGHAPUSAN
                        Route::get('penghapusan/pengajuan', 'PenghapusanAktivaController@pengajuan')->name('penghapusan.pengajuan');
                        Route::grid('penghapusan', 'PenghapusanAktivaController', [
                            'with' => ['submit', 'approval', 'reject', 'tracking', 'history', 'print'],
                        ]);
                        Route::post('penghapusan/{id}/updateSummary', 'PenghapusanAktivaController@updateSummary')->name('penghapusan.updateSummary');
                        // detail
                        Route::get('penghapusan/{record}/detail', 'PenghapusanAktivaController@detail')->name('penghapusan.detail');
                        Route::post('penghapusan/{record}/detailGrid', 'PenghapusanAktivaController@detailGrid')->name('penghapusan.detailGrid');
                        Route::get('penghapusan/{record}/detailCreate', 'PenghapusanAktivaController@detailCreate')->name('penghapusan.detailCreate');
                        Route::post('penghapusan/{record}/detailStore', 'PenghapusanAktivaController@detailStore')->name('penghapusan.detailStore');
                        Route::get('penghapusan/{detail}/detailShow', 'PenghapusanAktivaController@detailShow')->name('penghapusan.detailShow');
                        Route::get('penghapusan/{detail}/detailEdit', 'PenghapusanAktivaController@detailEdit')->name('penghapusan.detailEdit');
                        Route::patch('penghapusan/{detail}/detailUpdate', 'PenghapusanAktivaController@detailUpdate')->name('penghapusan.detailUpdate');
                        Route::delete('penghapusan/{detail}/detailDestroy', 'PenghapusanAktivaController@detailDestroy')->name('penghapusan.detailDestroy');

                        // PELAKSANAAN PENGHAPUSAN
                        Route::grid('pelaksanaan-penghapusan', 'PelaksanaanPenghapusanController', [
                            'with' => ['submit', 'approval', 'reject', 'tracking', 'history', 'print'],
                        ]);
                        Route::get('pelaksanaan-penghapusan/{penghapusan}/edit', 'PelaksanaanPenghapusanController@edit')->name('pelaksanaan-penghapusan.edit');
                        Route::post('pelaksanaan-penghapusan/{penghapusan}/detail-grid', 'PelaksanaanPenghapusanController@detailGrid')->name('pelaksanaan-penghapusan.detailGrid');

                        // PENJUALAN
                        Route::grid('penjualan', 'PenjualanAktivaController', [
                            'with' => ['submit', 'approval', 'reject', 'tracking', 'history', 'print'],
                        ]);
                        Route::post('penjualan/{id}/updateSummary', 'PenjualanAktivaController@updateSummary')->name('penjualan.updateSummary');
                        // detail
                        Route::get('penjualan/{record}/detail', 'PenjualanAktivaController@detail')->name('penjualan.detail');
                        Route::post('penjualan/{record}/detailGrid', 'PenjualanAktivaController@detailGrid')->name('penjualan.detailGrid');
                        Route::get('penjualan/{record}/detailCreate', 'PenjualanAktivaController@detailCreate')->name('penjualan.detailCreate');
                        Route::post('penjualan/{record}/detailStore', 'PenjualanAktivaController@detailStore')->name('penjualan.detailStore');
                        Route::get('penjualan/{detail}/detailShow', 'PenjualanAktivaController@detailShow')->name('penjualan.detailShow');
                        Route::get('penjualan/{detail}/detailEdit', 'PenjualanAktivaController@detailEdit')->name('penjualan.detailEdit');
                        Route::patch('penjualan/{detail}/detailUpdate', 'PenjualanAktivaController@detailUpdate')->name('penjualan.detailUpdate');
                        Route::delete('penjualan/{detail}/detailDestroy', 'PenjualanAktivaController@detailDestroy')->name('penjualan.detailDestroy');

                        // PELAKSANAAN PENJUALAN
                        Route::grid('pelaksanaan-penjualan', 'PelaksanaanPenjualanController', [
                            'with' => ['submit', 'approval', 'reject', 'tracking', 'history', 'print'],
                        ]);
                        Route::post('pelaksanaan-penjualan/{penjualan}/detail-grid', 'PelaksanaanPenjualanController@detailGrid')->name('pelaksanaan-penjualan.detailGrid');
                    }
                );

            // Mutasi Aktiva
            Route::namespace('Mutasi')
                ->group(
                    function () {
                        Route::grid('pengajuan-mutasi', 'PengajuanMutasiController', [
                            'with' => ['submit', 'approval', 'reject', 'tracking', 'history', 'print'],
                        ]);
                        // detail
                        Route::get('pengajuan-mutasi/{record}/detail', 'PengajuanMutasiController@detail')->name('pengajuan-mutasi.detail');
                        Route::post('pengajuan-mutasi/{record}/detailGrid', 'PengajuanMutasiController@detailGrid')->name('pengajuan-mutasi.detailGrid');
                        Route::get('pengajuan-mutasi/{record}/detailCreate', 'PengajuanMutasiController@detailCreate')->name('pengajuan-mutasi.detailCreate');
                        Route::post('pengajuan-mutasi/{record}/detailStore', 'PengajuanMutasiController@detailStore')->name('pengajuan-mutasi.detailStore');
                        Route::get('pengajuan-mutasi/{detail}/detailShow', 'PengajuanMutasiController@detailShow')->name('pengajuan-mutasi.detailShow');
                        Route::get('pengajuan-mutasi/{detail}/detailEdit', 'PengajuanMutasiController@detailEdit')->name('pengajuan-mutasi.detailEdit');
                        Route::patch('pengajuan-mutasi/{detail}/detailUpdate', 'PengajuanMutasiController@detailUpdate')->name('pengajuan-mutasi.detailUpdate');
                        Route::delete('pengajuan-mutasi/{detail}/detailDestroy', 'PengajuanMutasiController@detailDestroy')->name('pengajuan-mutasi.detailDestroy');

                        Route::grid('pelaksanaan-mutasi', 'PelaksanaanMutasiController', [
                            'with' => ['submit', 'approval', 'reject', 'tracking', 'history'],
                        ]);
                        Route::post('pelaksanaan-mutasi/{record}/detailGrid', 'PelaksanaanMutasiController@detailGrid')->name('pelaksanaan-mutasi.detailGrid');
                    }
                );

            // Master
            Route::namespace('Master')
                ->prefix('master')
                ->name('master.')
                ->group(
                    function () {
                        Route::namespace('Org')
                            ->prefix('org')
                            ->name('org.')
                            ->group(
                                function () {
                                    Route::grid('root', 'RootController');
                                    Route::grid('boc', 'BocController');

                                    Route::get('bod/import', 'BodController@import')->name('bod.import');
                                    Route::post('bod/importSave', 'BodController@importSave')->name('bod.importSave');
                                    Route::grid('bod', 'BodController');

                                    Route::get('division/import', 'DivisionController@import')->name('division.import');
                                    Route::post('division/importSave', 'DivisionController@importSave')->name('division.importSave');
                                    Route::grid('division', 'DivisionController');

                                    Route::get('department/import', 'DepartmentController@import')->name('department.import');
                                    Route::post('department/importSave', 'DepartmentController@importSave')->name('department.importSave');
                                    Route::grid('department', 'DepartmentController');

                                    Route::get('branch/import', 'BranchController@import')->name('branch.import');
                                    Route::post('branch/importSave', 'BranchController@importSave')->name('branch.importSave');
                                    Route::grid('branch', 'BranchController');

                                    Route::get('subbranch/import', 'SubbranchController@import')->name('subbranch.import');
                                    Route::post('subbranch/importSave', 'SubbranchController@importSave')->name('subbranch.importSave');
                                    Route::grid('subbranch', 'SubbranchController');

                                    // Route::grid('cash', 'CashController');
                                    // Route::grid('payment', 'PaymentController');

                                    Route::grid('group', 'GroupController');

                                    Route::get('position/import', 'PositionController@import')->name('position.import');
                                    Route::post('position/importSave', 'PositionController@importSave')->name('position.importSave');
                                    Route::grid('position', 'PositionController');

                                    Route::get('kelompok/import', 'KelompokController@import')->name('kelompok.import');
                                    Route::post('kelompok/importSave', 'KelompokController@importSave')->name('kelompok.importSave');
                                    Route::grid('kelompok', 'KelompokController');
                                }
                            );

                        // Route::namespace('Pay')
                        // ->name('pay.')
                        // ->prefix('pay')
                        // ->group(
                        //     function () {
                        //         Route::get('payment/import', 'PaymentController@import')->name('payment.import');
                        //         Route::post('payment/importSave', 'PaymentController@importSave')->name('payment.importSave');
                        //         Route::grid('payment', 'PaymentController');
                        //     }
                        // );

                        Route::namespace('Geo')
                            ->name('geo.')
                            ->prefix('geo')
                            ->group(
                                function () {
                                    Route::get('prov/import', 'ProvinceController@import')->name('prov.import');
                                    Route::post('prov/importSave', 'ProvinceController@importSave')->name('prov.importSave');
                                    Route::grid('prov', 'ProvinceController');

                                    Route::get('city/import', 'CityController@import')->name('city.import');
                                    Route::post('city/importSave', 'CityController@importSave')->name('city.importSave');
                                    Route::grid('city', 'CityController');
                                }
                            );

                        // Route::namespace('Item')
                        //     ->name('item.')
                        //     ->prefix('item')
                        //     ->group(
                        //         function () {
                        //             Route::get('barang/import', 'BarangController@import')->name('barang.import');
                        //             Route::post('barang/importSave', 'BarangController@importSave')->name('barang.importSave');
                        //             Route::grid('barang', 'BarangController');

                        //         }
                        //     );

                        Route::namespace('Penggunaan')
                            ->group(
                                function () {
                                    Route::get('penggunaan/import', 'PenggunaanController@import')->name('penggunaan.import');
                                    Route::post('penggunaan/importSave', 'PenggunaanController@importSave')->name('penggunaan.importSave');
                                    Route::grid('penggunaan', 'PenggunaanController');
                                }
                            );

                        Route::namespace('CaraPembayaran')
                            ->name('cara_pembayaran.')
                            ->prefix('cara-pembayaran')
                            ->group(
                                function () {
                                    Route::get('import', 'CaraPembayaranController@import')->name('import');
                                    Route::post('importSave', 'CaraPembayaranController@importSave')->name('importSave');
                                    Route::grid("", "CaraPembayaranController");
                                    Route::post("/grid", "CaraPembayaranController@grid")->name('grid');
                                    // Route::get("{record}", "CaraPembayaranController@show")->name("show");
                                    // Route::get("{record}/edit", "CaraPembayaranController@edit")->name("update");
                                    // Route::patch("{record}/edit", "CaraPembayaranController@update");
                                    // Route::delete("{record}", "CaraPembayaranController@destroy")->name("delete");
                                    // Route::post("/grid", "CaraPembayaranController@grid")->name("grid");
                                }
                            );

                        // Route::namespace('MataAnggaran')
                        //     ->name('mata_anggaran.')
                        //     ->prefix('mata-anggaran')
                        //     ->group(
                        //         function () {
                        //             Route::get('import', 'MataAnggaranController@import')->name('import');
                        //             Route::post('importSave', 'MataAnggaranController@importSave')->name('importSave');
                        //             Route::grid("", "MataAnggaranController");
                        //             Route::post("/grid", "MataAnggaranController@grid")->name("grid");
                        //             // Route::get("{record}", "MataAnggaranController@show")->name("show");
                        //             // Route::get("{record}/edit", "MataAnggaranController@edit")->name("update");
                        //             // Route::patch("{record}/edit", "MataAnggaranController@update");
                        //             // Route::delete("{record}", "MataAnggaranController@destroy")->name("delete");
                        //             // Route::post("/grid", "MataAnggaranController@grid")->name("grid");
                        //         }
                        //     );
                        /**
                         * Route Jenis Pembayaran
                         * @author Rahmatulah Sidik <sidik.pragma@gmail.com>
                         */
                        // Route::namespace("JenisPembayaran")
                        //     ->name("jenis_pembayaran.")
                        //     ->prefix("jenis-pembayaran")
                        //     ->group(
                        //         function () {
                        //             Route::get('import', 'JenisPembayaranController@import')->name('import');
                        //             Route::post('importSave', 'JenisPembayaranController@importSave')->name('importSave');
                        //             Route::grid("", "JenisPembayaranController");
                        //             Route::post("/grid", "JenisPembayaranController@grid")->name("grid");
                        //             // Route::get("{record}", "JenisPembayaranController@show")->name("show");
                        //             // Route::get("{record}/edit", "JenisPembayaranController@edit")->name("update");
                        //             // Route::patch("{record}/edit", "JenisPembayaranController@update");
                        //             // Route::delete("{record}", "JenisPembayaranController@destroy")->name("delete");
                        //         }
                        //     );

                        Route::namespace("SkemaPembayaran")
                            ->name("skema_pembayaran.")
                            ->prefix("skema-pembayaran")
                            ->group(
                                function () {
                                    Route::get('import', 'SkemaPembayaranController@import')->name('import');
                                    Route::post('importSave', 'SkemaPembayaranController@importSave')->name('importSave');
                                    Route::grid("", "SkemaPembayaranController");
                                    Route::post("/grid", "SkemaPembayaranController@grid")->name("grid");
                                    // Route::get("{record}", "SkemaPembayaranController@show")->name("show");
                                    // Route::get("{record}/edit", "SkemaPembayaranController@edit")->name("update");
                                    // Route::patch("{record}/edit", "SkemaPembayaranController@update");
                                    // Route::delete("{record}", "SkemaPembayaranController@destroy")->name("delete");
                                }
                            );

                        /**
                         * Route Master Barang
                         * @author Rahmatulah Sidik <sidik.pragma@gmail.com>
                         */
                        Route::namespace("Barang")
                            ->name("vendor.")
                            ->prefix("vendor")
                            ->group(
                                function () {
                                    Route::get('import', 'VendorController@import')->name('import');
                                    Route::post('importSave', 'VendorController@importSave')->name('importSave');
                                    Route::grid("", "VendorController");
                                    Route::post("/grid", "VendorController@grid")->name("grid");
                                    Route::get('vendor/addBank', 'VendorController@addBank')->name('addBank');
                                    // Route::get("{record}", "VendorController@show")->name("show");
                                    // Route::get("{record}/edit", "VendorController@edit")->name("update");
                                    // Route::patch("{record}/edit", "VendorController@update");
                                    // Route::delete("{record}", "VendorController@destroy")->name("delete");
                                }
                            );

                        Route::namespace("Barang")
                            ->name("tipe_barang.")
                            ->prefix("tipe-barang")
                            ->group(
                                function () {
                                    Route::get('import', 'TipeBarangController@import')->name('import');
                                    Route::post('importSave', 'TipeBarangController@importSave')->name('importSave');
                                    Route::grid("", "TipeBarangController");
                                    Route::post("/grid", "TipeBarangController@grid")->name("grid");
                                }
                            );

                        Route::namespace("Barang")
                            ->name("barang.")
                            ->prefix("barang")
                            ->group(
                                function () {
                                    Route::get('import', 'BarangController@import')->name('import');
                                    Route::post('importSave', 'BarangController@importSave')->name('importSave');
                                    Route::grid("", "BarangController");
                                    Route::post("/grid", "BarangController@grid")->name("grid");
                                }
                            );

                        Route::namespace('RekeningBank')
                            ->name('rekening_bank.')
                            ->prefix('rekening-bank')
                            ->group(
                                function () {
                                    Route::get('import', 'RekeningBankController@import')->name('import');
                                    Route::post('importSave', 'RekeningBankController@importSave')->name('importSave');
                                    Route::grid("", "RekeningBankController");
                                    Route::post("/grid", "RekeningBankController@grid")->name("grid");
                                }
                            );
                        Route::namespace('Bank')
                            ->group(
                                function () {
                                    Route::grid("bank", "BankController");
                                }
                            );
                        Route::grid("bank-account", "Bank\BankAccountController");
                        Route::namespace('MataAnggaran')
                            ->group(
                                function () {
                                    Route::grid("mata-anggaran", "MataAnggaranController");
                                    Route::get('getDetailMataAnggaran', 'MataAnggaranController@getDetailMataAnggaran')->name('getDetailMataAnggaran');
                                }
                            );
                        Route::namespace('MasaPenggunaan')
                            ->group(
                                function () {
                                    Route::grid('masa-penggunaan', 'MasaPenggunaanController');
                                }
                            );
                        Route::namespace('Jurnal')
                            ->group(
                                function () {
                                    Route::grid('coa', 'CoaController');
                                    Route::get('getDetailCOA', 'CoaController@getDetailCOA')->name('getDetailCOA');
                                    Route::grid('template', 'TemplateController');
                                    Route::post('template/{id}/entry', 'TemplateController@entry')->name('template.entry');
                                    Route::post('template/{id}/submitEntry', 'TemplateController@submitEntry')->name('template.submitEntry');
                                    Route::get('template/{id}/entry/add', 'TemplateController@addEntry')->name('template.entry.add');
                                }
                            );
                    }
                );

            // Setting
            Route::namespace('Setting')
                ->prefix('setting')
                ->name('setting.')
                ->group(
                    function () {
                        Route::namespace('Role')
                            ->group(
                                function () {
                                    Route::get('role/import', 'RoleController@import')->name('role.import');
                                    Route::post('role/importSave', 'RoleController@importSave')->name('role.importSave');
                                    Route::get('role/{record}/permit', 'RoleController@permit')->name('role.permit');
                                    Route::patch('role/{record}/grant', 'RoleController@grant')->name('role.grant');
                                    Route::grid('role', 'RoleController');
                                }
                            );
                        Route::namespace('Flow')
                            ->group(
                                function () {
                                    Route::get('flow/import', 'FlowController@import')->name('flow.import');
                                    Route::post('flow/importSave', 'FlowController@importSave')->name('flow.importSave');
                                    Route::grid('flow', 'FlowController', ['with' => ['history']]);
                                }
                            );
                        Route::namespace('User')
                            ->group(
                                function () {
                                    Route::get('user/import', 'UserController@import')->name('user.import');
                                    Route::post('user/importSave', 'UserController@importSave')->name('user.importSave');
                                    Route::post('user/{record}/resetPassword', 'UserController@resetPassword')->name('user.resetPassword');
                                    Route::get('user/addBank', 'UserController@addBank')->name('user.addBank');
                                    Route::grid('user', 'UserController');

                                    Route::get('profile', 'ProfileController@index')->name('profile.index');
                                    Route::post('profile', 'ProfileController@updateProfile')->name('profile.updateProfile');
                                    Route::get('profile/notification', 'ProfileController@notification')->name('profile.notification');
                                    Route::post('profile/gridNotification', 'ProfileController@gridNotification')->name('profile.gridNotification');
                                    Route::get('profile/activity', 'ProfileController@activity')->name('profile.activity');
                                    Route::post('profile/gridActivity', 'ProfileController@gridActivity')->name('profile.gridActivity');
                                    Route::get('profile/changePassword', 'ProfileController@changePassword')->name('profile.changePassword');
                                    Route::post('profile/changePassword', 'ProfileController@updatePassword')->name('profile.updatePassword');
                                }
                            );
                        Route::namespace('Activity')
                            ->group(
                                function () {
                                    Route::get('activity/export', 'ActivityController@export')->name('activity.export');
                                    Route::grid('activity', 'ActivityController');
                                }
                            );
                    }
                );

            // Web Transaction Modules
            foreach (\File::allFiles(__DIR__ . '/webs') as $file) {
                require $file->getPathname();
            }
        }
    );
