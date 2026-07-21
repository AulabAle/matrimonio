<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BlockSiteIfPdfViewer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $pdfUrl = env('PDF_URL', 'https://pdf.invito/shared/invito-matrimonio-monica-erasmo.pdf');
        $pdfHost = parse_url($pdfUrl, PHP_URL_HOST);

        // 1. Host-based protection: if the request host matches the PDF domain,
        // restrict all access except for the invitation PDF itself.
        if ($pdfHost && $request->getHost() === $pdfHost) {
            if (!$request->routeIs('invito-pdf')) {
                return redirect()->away($pdfUrl);
            }
        }

        // 2. Session-based protection: fallback to session block for visitors who visited the PDF path
        if (session('block_site') && !(Auth::check() && Auth::user()->isAdmin())) {
            return redirect()->route('invito-pdf');
        }

        return $next($request);
    }
}
