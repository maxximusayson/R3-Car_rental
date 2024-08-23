<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestFilterController extends Controller
{
    private function filterMessage($message)
    {
        $patterns = [
            '/\b(sensitive_word1|confidential|top_secret)\b/i' => '[filtered]',
            '/\b(unwanted_phrase|do_not_share)\b/i' => '[filtered]',
        ];

        foreach ($patterns as $pattern => $replacement) {
            $message = preg_replace($pattern, $replacement, $message);
        }

        return $message;
    }

    public function testFilter()
    {
        $testMessages = [
            "This is a confidential document.",
            "Please do not share this top_secret info.",
            "This message contains sensitive_word1 and other details.",
            "No sensitive content here."
        ];

        $filteredMessages = array_map([$this, 'filterMessage'], $testMessages);

        return response()->json(['filtered_messages' => $filteredMessages]);
    }
}
