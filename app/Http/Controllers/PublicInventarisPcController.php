<?php

namespace App\Http\Controllers;

use App\Models\InventarisMeja;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class PublicInventarisPcController extends Controller
{
    public function show(string $token): View
    {
        $pc = InventarisMeja::with('lab')->where('qr_token', strtolower($token))->firstOrFail();

        $statuses = [
            'CPU' => $pc->cpu_status ?: 'normal',
            'Monitor' => $pc->monitor_status ?: 'normal',
            'Keyboard' => $pc->keyboard_status ?: 'normal',
            'Mouse' => $pc->mouse_status ?: 'normal',
            'Windows' => $pc->windows_status ?: 'normal',
        ];
        $conditions = [
            'CPU' => $pc->cpu_kondisi ?: 'normal',
            'Monitor' => $pc->monitor_kondisi ?: 'normal',
            'Keyboard' => $pc->keyboard_kondisi ?: 'normal',
            'Mouse' => $pc->mouse_kondisi ?: 'normal',
            'Kursi' => $pc->kursi_kondisi ?: 'normal',
        ];
        $needsAttention = collect($statuses)->contains(fn ($value) => $value !== 'normal')
            || collect($conditions)->contains(fn ($value) => $value !== 'normal');

        return view('public.inventaris.pc', compact('pc', 'statuses', 'conditions', 'needsAttention'));
    }

    public function qr(string $token, Request $request): Response
    {
        $pc = InventarisMeja::where('qr_token', strtolower($token))->firstOrFail();
        $publicPath = route('public.inventaris.pc.show', ['token' => $pc->qr_token], false);
        $publicUrl = rtrim((string) config('app.url'), '/').'/'.ltrim($publicPath, '/');

        $renderer = new ImageRenderer(new RendererStyle(280, 8), new SvgImageBackEnd);
        $svg = (new Writer($renderer))->writeString($publicUrl);
        $filename = sprintf('qr-pc-lab-%d-unit-%d.svg', $pc->id_lab, $pc->nomor_meja);
        $disposition = $request->boolean('download') ? 'attachment' : 'inline';

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml; charset=UTF-8',
            'Content-Disposition' => $disposition.'; filename="'.$filename.'"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }
}
