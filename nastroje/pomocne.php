<?php

/**
 * Pomocné funkce
 */

function extract_front_matter(&$text) {
    preg_match_all('/^---$/m', $text, $matches, PREG_OFFSET_CAPTURE);

    // no front matter found
    if (!isset($matches[0][1])) {
        return[];
    }

    $frontMatterEnd = $matches[0][1][1];

    // parse front matter
    $frontMatter = substr($text, 4, $frontMatterEnd - 5);
    $frontMatter = explode("\n", $frontMatter);
    $new = [];
    foreach ($frontMatter as $line) {
        $pos = strpos($line, ':');
        $key = substr($line, 0, $pos);
        $val = substr($line, $pos + 1);
        $val = trim($val);
        $new[$key] = $val;
    }
    $frontMatter = $new;

    // remove front matter from actual text
    $text = substr($text, $frontMatterEnd + 4);

    return $frontMatter;
}

function str_replace_nth($search, $replace, $subject, $nth) {
    $found = preg_match_all('/'.preg_quote($search).'/', $subject, $matches, PREG_OFFSET_CAPTURE);
    if (false !== $found && $found > $nth) {
        return substr_replace($subject, $replace, $matches[0][$nth][1], strlen($search));
    }
    return $subject;
}
