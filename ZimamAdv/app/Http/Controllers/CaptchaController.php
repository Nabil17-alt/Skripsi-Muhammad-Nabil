<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CaptchaController extends Controller
{
    public function generate()
    {
        // Generate random string
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // omit ambiguous characters like I, O, 0, 1
        $code = '';
        for ($i = 0; $i < 5; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }

        // Store code in session (case-insensitive checking later)
        Session::put('captcha_code', strtoupper($code));

        // Create SVG image
        $width = 150;
        $height = 45;

        $svg = "<svg xmlns='http://www.w3.org/2000/svg' width='{$width}' height='{$height}' viewBox='0 0 {$width} {$height}'>";
        
        // Background with subtle gray/white gradient
        $svg .= "<rect width='100%' height='100%' fill='#f1f5f9'/>";

        // Draw some grid/noise lines
        for ($i = 0; $i < 5; $i++) {
            $x1 = rand(0, $width);
            $y1 = rand(0, $height);
            $x2 = rand(0, $width);
            $y2 = rand(0, $height);
            $colors = ['#cbd5e1', '#94a3b8', '#cbd5e1', '#e2e8f0', '#a1a1aa'];
            $color = $colors[array_rand($colors)];
            $svg .= "<line x1='{$x1}' y1='{$y1}' x2='{$x2}' y2='{$y2}' stroke='{$color}' stroke-width='".rand(1, 2)."' opacity='0.7'/>";
        }

        // Draw noise curves
        for ($i = 0; $i < 2; $i++) {
            $x1 = rand(5, 20);
            $y1 = rand(10, 35);
            $cx = rand(30, 70);
            $cy = rand(5, 40);
            $x2 = rand(80, 140);
            $y2 = rand(10, 35);
            $svg .= "<path d='M {$x1} {$y1} Q {$cx} {$cy}, {$x2} {$y2}' fill='none' stroke='#94a3b8' stroke-width='2' opacity='0.5'/>";
        }

        // Draw noise dots
        for ($i = 0; $i < 40; $i++) {
            $cx = rand(0, $width);
            $cy = rand(0, $height);
            $r = rand(1, 2);
            $svg .= "<circle cx='{$cx}' cy='{$cy}' r='{$r}' fill='#94a3b8' opacity='0.6'/>";
        }

        // Draw the text characters
        $charWidth = $width / 6;
        for ($i = 0; $i < strlen($code); $i++) {
            $char = $code[$i];
            $x = ($i + 0.5) * $charWidth + rand(-3, 3);
            $y = $height / 2 + rand(4, 8);
            $angle = rand(-20, 20);
            
            // Choose elegant dark/brand colors
            $colors = ['#0f172a', '#1e293b', '#334155', '#1e3a8a', '#0369a1', '#047857'];
            $color = $colors[array_rand($colors)];
            $fontSize = rand(22, 26);
            
            $svg .= "<text x='{$x}' y='{$y}' font-family='Arial, Helvetica, sans-serif' font-weight='bold' font-size='{$fontSize}' fill='{$color}' transform='rotate({$angle} {$x} {$y})'>{$char}</text>";
        }

        $svg .= "</svg>";

        return response($svg)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
