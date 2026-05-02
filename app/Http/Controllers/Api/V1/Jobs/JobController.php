<?php

namespace App\Http\Controllers\Api\V1\Jobs;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class JobController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->success('Jobs feed');
    }

    public function store(): JsonResponse
    {
        return $this->success('Job created');
    }

    public function show(): JsonResponse
    {
        return $this->success('Job detail');
    }

    public function update(): JsonResponse
    {
        return $this->success('Job updated');
    }

    public function destroy(): JsonResponse
    {
        return $this->success('Job deleted');
    }

    public function updateStatus(): JsonResponse
    {
        return $this->success('Job status updated');
    }
}
