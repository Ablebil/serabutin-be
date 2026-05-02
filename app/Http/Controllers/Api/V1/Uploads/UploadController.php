<?php

namespace App\Http\Controllers\Api\V1\Uploads;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UploadController extends Controller
{
	public function store(Request $request): JsonResponse
	{
		$validator = Validator::make(
			$request->all(),
			[
				'file' => ['required', 'file', 'max:5120', 'mimes:jpg,jpeg,png'],
			],
			[
				'file.required' => __('uploads.validation.file_required'),
				'file.file' => __('uploads.validation.file_invalid'),
				'file.max' => __('uploads.validation.file_too_large'),
				'file.mimes' => __('uploads.validation.file_unsupported'),
			]
		);

		$validator->after(function ($validator) use ($request): void {
			if (!$request->hasFile('file')) {
				return;
			}

			$file = $request->file('file');

			if (is_null($file)) {
				return;
			}

			$handle = @fopen($file->getRealPath(), 'rb');

			if ($handle === false) {
				$validator->errors()->add('file', __('uploads.validation.file_invalid'));
				return;
			}

			$head = fread($handle, 512);
			fclose($handle);

			$finfo = new \finfo(FILEINFO_MIME_TYPE);
			$mimeType = $finfo->buffer($head ?: '');

			$allowedMimeTypes = ['image/jpeg', 'image/png'];

			if (!in_array($mimeType, $allowedMimeTypes, true)) {
				$validator->errors()->add('file', __('uploads.validation.file_invalid'));
			}
		});

		$validator->validate();

		$file = $request->file('file');

		$finfo = new \finfo(FILEINFO_MIME_TYPE);
		$mimeType = $finfo->file($file->getRealPath());

		$extension = $mimeType === 'image/png' ? 'png' : 'jpg';
		$filename = (string) Str::uuid() . '.' . $extension;
		$path = 'avatars/' . $filename;

		Storage::disk('public')->putFileAs('avatars', $file, $filename);

		return $this->success(
			__('uploads.upload.success'),
			['url' => Storage::disk('public')->url($path)],
			201
		);
	}
}
