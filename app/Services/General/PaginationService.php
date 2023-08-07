<?php

namespace App\Services\General;

class PaginationService
{

    public static function paginate($data)
    {
        return [
            'current_page' => (int) $data['current_page'],
            'first_page_url' => $data['first_page_url'] ?? '',
            'last_page_url' => $data['last_page_url'] ?? '',
            'next_page_url' => $data['next_page_url'] ?? '',
            'prev_page_url' => $data['prev_page_url'] ?? '',
            'path' => $data['path'] ?? '',
            'per_page' => (int) $data['per_page'],
            'last_page' => (int) $data['last_page'] ?? '',
            'from' => (int) $data['from'],
            'to' => (int) $data['to'],
            'total' => (int) $data['total'],
        ];
    }

}
