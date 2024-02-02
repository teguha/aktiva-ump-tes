<?php

return [
    // CRUD
    'success' => [
        'saved'     => 'Data was successfully saved',
        'created'   => 'Data was successfully created',
        'updated'   => 'Data was successfully updated',
        'deleted'   => 'Data was successfully deleted',
        'activated' => 'Data was successfully activated',
        'disabled'  => 'Data was successfully inactivated',
        'authorized' => 'Data berhasil authorized.',
        'approved'  => 'Data was successfully approved',
        'rejected'  => 'Data was successfully rejected',
        'revision'  => 'Data was successfully asked for revision'
    ],
    'error' => [
        'saved'     => 'Data failed to save',
        'created'   => 'Data failed to create',
        'updated'   => 'Data failed to update',
        'deleted'   => 'Data failed to delete',
        'activated' => 'Data failed to active',
        'disabled'  => 'Data failed to inactivate',
        'authorized' => 'Data failed to authorize.',
        'approved'  => 'Data failed to approve',
        'rejected'  => 'Data failed to reject',
        'related'   => 'Data was used by other modules.',
    ],
    'confirm' => [
        'save' => [
            'title' => 'Are you sure?',
            'text' => 'Make sure the data is correct!',
            'ok' => 'Yes',
            'cancel' => 'Cancel',
        ],
        'delete' => [
            'title' => 'Are you sure?',
            'text' => 'Data yang telah dihapus tidak dapat dikembalikan!',
            'ok' => 'Delete',
            'cancel' => 'Cancel',
        ],
        'approve' => [
            'title' => 'Are you sure?',
            'text' => 'Approved data will be processed to the next step!',
            'ok' => 'Approve',
            'cancel' => 'Cancel',
        ],
        'authorize' => [
            'title' => 'Are you sure?',
            'text' => 'Authorized data will be processed to the next step!',
            'ok' => 'Authorized',
            'cancel' => 'Cancel',
        ],
        'reject' => [
            'title' => 'Are you sure?',
            'text' => 'Data that has been rejected will be returned for correction!',
            'ok' => 'Reject',
            'cancel' => 'Cancel',
        ],
    ],
];