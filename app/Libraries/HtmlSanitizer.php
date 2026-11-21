<?php

namespace App\Libraries;

/**
 * Sanitasi HTML berbasis allowlist (DOMDocument) untuk mencegah Stored XSS
 * sambil tetap mempertahankan format artikel/berita yang aman.
 */
class HtmlSanitizer
{
    /**
     * Daftar tag HTML yang diperbolehkan pada konten berita.
     */
    private const ALLOWED_TAGS = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's',
        'ul', 'ol', 'li',
        'h2', 'h3', 'h4', 'h5', 'h6',
        'blockquote', 'pre', 'code', 'hr',
        'a', 'img', 'span', 'div',
        'table', 'thead', 'tbody', 'tr', 'th', 'td',
    ];

    /**
     * Tag berbahaya yang harus dihapus beserta seluruh isi di dalamnya.
     */
    private const DROP_WITH_CONTENT_TAGS = [
        'script', 'style', 'iframe', 'object', 'embed', 'applet',
        'form', 'input', 'button', 'textarea', 'select', 'option',
        'svg', 'math', 'meta', 'link', 'base', 'noscript', 'template',
    ];

    /**
     * Atribut yang diizinkan per tag.
     */
    private const ALLOWED_ATTRIBUTES = [
        'a'   => ['href', 'title', 'target', 'rel'],
        'img' => ['src', 'alt', 'title'],
    ];

    /**
     * Bersihkan konten HTML dari tag/atribut berbahaya (XSS).
     */
    public static function clean(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        // Jika DOMDocument tidak tersedia, fallback ke strip_tags aman + esc
        if (!class_exists(\DOMDocument::class)) {
            return nl2br(esc(strip_tags($html)));
        }

        $previousState = libxml_use_internal_errors(true);

        $dom     = new \DOMDocument('1.0', 'UTF-8');
        $wrapped = '<?xml encoding="UTF-8"><div id="__sanitizer_root__">' . $html . '</div>';

        $loaded = $dom->loadHTML(
            $wrapped,
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET
        );

        libxml_clear_errors();
        libxml_use_internal_errors($previousState);

        if (!$loaded) {
            return nl2br(esc(strip_tags($html)));
        }

        $xpath = new \DOMXPath($dom);
        $nodes = $xpath->query('//*[@id="__sanitizer_root__"]');
        $root  = ($nodes && $nodes->length > 0) ? $nodes->item(0) : null;

        if (!$root) {
            return nl2br(esc(strip_tags($html)));
        }

        self::sanitizeNode($root);

        $output = '';
        foreach ($root->childNodes as $child) {
            $output .= $dom->saveHTML($child);
        }

        return trim($output);
    }

    /**
     * Traversal rekursif untuk membersihkan elemen dan atribut berbahaya.
     */
    private static function sanitizeNode(\DOMNode $node): void
    {
        if (!$node->hasChildNodes()) {
            return;
        }

        // Salin daftar child karena kita dapat memodifikasi DOM saat iterasi
        $children = [];
        foreach ($node->childNodes as $child) {
            $children[] = $child;
        }

        foreach ($children as $child) {
            if ($child instanceof \DOMComment) {
                $node->removeChild($child);
                continue;
            }

            if ($child instanceof \DOMElement) {
                $tagName = strtolower($child->tagName);

                // Hapus tag berbahaya beserta seluruh isinya
                if (in_array($tagName, self::DROP_WITH_CONTENT_TAGS, true)) {
                    $node->removeChild($child);
                    continue;
                }

                // Bersihkan child terlebih dahulu
                self::sanitizeNode($child);

                // Jika tag tidak masuk allowlist, pertahankan teks/child aman lalu buang tag pembungkusnya
                if (!in_array($tagName, self::ALLOWED_TAGS, true)) {
                    while ($child->firstChild) {
                        $node->insertBefore($child->firstChild, $child);
                    }
                    $node->removeChild($child);
                    continue;
                }

                // Filter atribut sesuai allowlist per tag
                $allowedAttrs  = self::ALLOWED_ATTRIBUTES[$tagName] ?? [];
                $attrsToRemove = [];

                if ($child->hasAttributes()) {
                    foreach ($child->attributes as $attr) {
                        $attrName = strtolower($attr->name);
                        if (!in_array($attrName, $allowedAttrs, true)) {
                            $attrsToRemove[] = $attr->name;
                            continue;
                        }

                        if ($attrName === 'href' && !self::isSafeUrl($attr->value, true)) {
                            $attrsToRemove[] = $attr->name;
                        } elseif ($attrName === 'src' && !self::isSafeUrl($attr->value, false)) {
                            $attrsToRemove[] = $attr->name;
                        } elseif ($attrName === 'target') {
                            if (strtolower(trim($attr->value)) !== '_blank') {
                                $attrsToRemove[] = $attr->name;
                            }
                        }
                    }
                }

                foreach ($attrsToRemove as $attrName) {
                    $child->removeAttribute($attrName);
                }

                // Paksa rel="noopener noreferrer" jika tautan membuka tab baru
                if ($tagName === 'a' && $child->hasAttribute('target')) {
                    $child->setAttribute('rel', 'noopener noreferrer');
                }
            }
        }
    }

    /**
     * Validasi skema URL untuk mencegah javascript:, vbscript:, data:, dll.
     */
    private static function isSafeUrl(string $url, bool $allowMailto = false): bool
    {
        $clean = trim(html_entity_decode($url, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        if ($clean === '') {
            return false;
        }

        // Hapus karakter kontrol/whitespace tersembunyi sebelum memeriksa skema
        $normalized = preg_replace('/[\x00-\x20\x7F]+/', '', $clean);
        if ($normalized === null || $normalized === '') {
            return false;
        }

        // Izinkan relative path atau anchor
        if (str_starts_with($normalized, '/') && !str_starts_with($normalized, '//')) {
            return true;
        }
        if ($allowMailto && str_starts_with($normalized, '#')) {
            return true;
        }

        $scheme         = strtolower((string) parse_url($normalized, PHP_URL_SCHEME));
        $allowedSchemes = $allowMailto ? ['http', 'https', 'mailto'] : ['http', 'https'];

        return in_array($scheme, $allowedSchemes, true);
    }
}
