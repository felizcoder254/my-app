<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function showDashboard()
    {
        $user = Auth::user();

        $paper1 = $this->loadCategoryFiles('paper_1');
        $paper2 = $this->loadCategoryFiles('paper_2');
        $pastpapers = $this->loadCategoryFiles('pastpapers');
        $predictions = $this->loadCategoryFiles('predictions');

        return view('dashboard', compact('user', 'paper1', 'paper2', 'pastpapers', 'predictions'));
    }

    private function loadCategoryFiles($type)
    {
        $basePath = storage_path("app/public/nck/{$type}");
        $categoryDirs = glob($basePath . '/*', GLOB_ONLYDIR);
        $all = [];

        foreach ($categoryDirs as $dir) {
            $category = basename($dir);
            $files = glob($dir . '/*');
            $all[$category] = [];

            foreach ($files as $file) {
                $filename = basename($file);
                $all[$category][] = [
                    'name' => $filename,
                    'link' => url("/view/{$type}/{$category}/{$filename}")
                ];
            }
        }

        return $all;
    }

    public function previewDocAsHtml($paper, $category, $filename)
    {
        $path = storage_path("app/public/nck/{$paper}/{$category}/{$filename}");
        if (!file_exists($path)) abort(404);

        try {
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($path);
            $htmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');

            ob_start();
            $htmlWriter->save('php://output');
            $rawHtml = ob_get_clean();

            $enhancedHtml = $this->wrapQuestions($rawHtml);
        } catch (\Exception $e) {
            $enhancedHtml = "<p style='color:red;'>❌ Failed to render DOCX: {$e->getMessage()}</p>";
        }

        return view('docx_render', [
            'filename' => $filename,
            'html' => $enhancedHtml,
            'paper' => $paper,
            'category' => $category
        ]);
    }

    private function wrapQuestions($html)
    {
        $clean = preg_replace([
            '/<style\\b[^>]*>.*?<\\/style>/is',
            '/class="[^"]*"/i',
            '/style="[^"]*"/i',
            '/<table[^>]*>.*?<\\/table>/is',
            '/&nbsp;/i',
            '/PHPWord[^<]+/i',
            '/<p[^>]*>\\s*<\\/p>/i',
        ], '', $html);

        $lines = preg_split('/<p[^>]*>/', $clean);
        $output = '';
        $inBlock = false;
        $inExplanation = false;
        $inSubSection = false;

        foreach ($lines as $line) {
            $line = trim(strip_tags($line));
            if (!$line) continue;

            if (preg_match('/^Question\\s*\\d+[:.]/i', $line)) {
                if ($inBlock) {
                    if ($inSubSection) $output .= "</div>";
                    if ($inExplanation) $output .= "</div></div>";
                    $output .= "</div></div>";
                    $inSubSection = $inExplanation = false;
                }
                $output .= "<div class='question-block'>
                    <div class='question-header'>
                        <span class='quiz-text'>" . e($line) . "</span>
                        <button class='speak-btn' onclick='speakQuiz(this)' title='Read Aloud'>🔊</button>
                        <button onclick=\"toggleSpeech()\" class=\"speak-btn\">⏸ Pause</button>
                    </div>
                    <div class='question-body'>";
                $inBlock = true;
            } elseif (preg_match('/^[A-D]\\./', $line)) {
                $correct = str_contains($line, '✓') ? 'correct' : '';
                $text = str_replace(['✓', '[✓]'], '', $line);
                $output .= "<div class='option {$correct}'>" . e(trim($text)) . "</div>";
            } elseif (preg_match('/^(Indication|High-Yield Summary Points|Mnemonic.*?|Predictive Questions)[:]?$/i', $line, $matches)) {
                if (!$inExplanation) {
                    $output .= "<div class='explanation-wrapper'>
                        <button class='toggle-expl' onclick='toggleExplanation(this)'>Show Explanation ↓</button>
                        <div class='explanation' style='display:none;'>";
                    $inExplanation = true;
                }
                if ($inSubSection) $output .= "</div>";
                $label = e($matches[1]);
                $output .= "<button class='sub-toggle' onclick='toggleSub(this)'>{$label} ↓</button>
                            <div class='sub-section' style='display:none;'>";
                $inSubSection = true;
            } else {
                $output .= "<div>" . e($line) . "</div>";
            }
        }

        if ($inSubSection) $output .= "</div>";
        if ($inExplanation) $output .= "</div></div>";
        if ($inBlock) $output .= "</div></div>";

        return $output;
    }
}
