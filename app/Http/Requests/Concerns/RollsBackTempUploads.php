<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Storage;

trait RollsBackTempUploads
{
    protected function failedValidation(Validator $validator): void
    {
        $token = (string) $this->input('upload_token', '');

        if ($token !== '' && preg_match('/^[A-Za-z0-9_-]{10,100}$/', $token)) {
            Storage::disk('public')->deleteDirectory('uploads/tmp/' . $token);
        }

        parent::failedValidation($validator);
    }
}