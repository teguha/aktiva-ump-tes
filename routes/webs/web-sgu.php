<?php 
    /**
     * * Sewa Guna Usaha *
     * @author: Cecepns (cecep.pragma@gmail.com)
     */
    Route::namespace('Sgu')
        ->group(
            function () {
                // Pengajuan SGU
                Route::grid('sgu', 'SguController', ['with' => ['submit','reject', 'approval', 'tracking', 'print', 'history']]);
                Route::get('sgu/{record}/detail', 'SguController@detail')->name('sgu.detail');
                Route::post('sgu/{id}/updateSummary', 'SguController@updateSummary')->name('sgu.updateSummary');
            }
        );

?>