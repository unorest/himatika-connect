<?php

namespace App\Libraries;

/**
 * FPDF - Free PDF generation library
 * Adapted and optimized for CodeIgniter 4 and PHP 8.2+
 */
#[\AllowDynamicProperties]
class FPDF
{
    protected int $page = 0;
    protected int $n = 2;
    protected array $offsets = [];
    protected string $buffer = '';
    protected array $pages = [];
    protected int $state = 0;
    protected bool $compress = true;
    protected float $k;
    protected string $DefOrientation = 'P';
    protected string $CurOrientation = 'P';
    protected array $StdPageSizes = [];
    protected array $DefPageSize = [];
    protected array $CurPageSize = [];
    protected array $PageSizes = [];
    protected float $wPt, $hPt;
    protected float $w, $h;
    protected float $lMargin, $tMargin, $rMargin, $bMargin;
    protected float $x, $y;
    protected float $lasth;
    protected float $LineWidth;
    protected string $fontpath = '';
    protected array $CoreFonts = [];
    protected array $fonts = [];
    protected array $FontFiles = [];
    protected array $diffs = [];
    protected string $FontFamily = '';
    protected string $FontStyle = '';
    protected bool $underline = false;
    protected array $CurrentFont = [];
    protected float $FontSizePt = 12;
    protected float $FontSize = 12;
    protected string $DrawColor = '0 G';
    protected string $FillColor = '0 g';
    protected string $TextColor = '0 g';
    protected bool $ColorFlag = false;
    protected float $ws = 0;
    protected array $images = [];
    protected array $PageLinks = [];
    protected array $links = [];
    protected bool $AutoPageBreak = true;
    protected float $PageBreakTrigger;
    protected bool $InHeader = false;
    protected bool $InFooter = false;
    protected float $ZoomMode;
    protected string $LayoutMode;
    protected string $title = '';
    protected string $subject = '';
    protected string $author = '';
    protected string $keywords = '';
    protected string $creator = 'HIMATIKA Connect CI4';
    protected string $AliasNbPages = '{nb}';
    protected string $PDFVersion = '1.4';

    public function __construct(string $orientation = 'P', string $unit = 'mm', string|array $size = 'A4')
    {
        $this->CoreFonts = [
            'courier' => 'Courier', 'courierB' => 'Courier-Bold', 'courierI' => 'Courier-Oblique', 'courierBI' => 'Courier-BoldOblique',
            'helvetica' => 'Helvetica', 'helveticaB' => 'Helvetica-Bold', 'helveticaI' => 'Helvetica-Oblique', 'helveticaBI' => 'Helvetica-BoldOblique',
            'times' => 'Times-Roman', 'timesB' => 'Times-Bold', 'timesI' => 'Times-Italic', 'timesBI' => 'Times-BoldItalic',
            'symbol' => 'Symbol', 'zapfdingbats' => 'ZapfDingbats'
        ];

        // Unit scale
        $this->k = match ($unit) {
            'pt' => 1.0,
            'mm' => 72.0 / 25.4,
            'cm' => 72.0 / 2.54,
            'in' => 72.0,
            default => 72.0 / 25.4,
        };

        $this->StdPageSizes = [
            'a3' => [841.89, 1190.55],
            'a4' => [595.28, 841.89],
            'a5' => [420.94, 595.28],
            'letter' => [612, 792],
            'legal' => [612, 1008]
        ];

        $size = $this->_getpagesize($size);
        $this->DefPageSize = $size;
        $this->CurPageSize = $size;

        $orientation = strtolower($orientation);
        if ($orientation === 'p' || $orientation === 'portrait') {
            $this->DefOrientation = 'P';
            $this->w = $size[0];
            $this->h = $size[1];
        } else {
            $this->DefOrientation = 'L';
            $this->w = $size[1];
            $this->h = $size[0];
        }

        $this->CurOrientation = $this->DefOrientation;
        $this->wPt = $this->w * $this->k;
        $this->hPt = $this->h * $this->k;

        // Margins (10 mm)
        $margin = 28.35 / $this->k;
        $this->SetMargins($margin, $margin);
        $this->cMargin = $margin / 10;
        $this->LineWidth = 0.567 / $this->k;
        $this->SetAutoPageBreak(true, 2 * $margin);
        $this->SetDisplayMode('default');
        $this->compress = function_exists('gzcompress');
    }

    public function SetMargins(float $left, float $top, ?float $right = null): void
    {
        $this->lMargin = $left;
        $this->tMargin = $top;
        $this->rMargin = $right ?? $left;
    }

    public function SetAutoPageBreak(bool $auto, float $margin = 0): void
    {
        $this->AutoPageBreak = $auto;
        $this->bMargin = $margin;
        $this->PageBreakTrigger = $this->h - $margin;
    }

    public function SetDisplayMode(string|float $zoom, string $layout = 'continuous'): void
    {
        $this->ZoomMode = is_numeric($zoom) ? (float)$zoom : 0;
        $this->LayoutMode = $layout;
    }

    public function SetTitle(string $title, bool $isUTF8 = false): void
    {
        $this->title = $title;
    }

    public function SetAuthor(string $author, bool $isUTF8 = false): void
    {
        $this->author = $author;
    }

    public function AddPage(string $orientation = '', string|array $size = '', float $rotation = 0): void
    {
        if ($this->state === 0) {
            $this->Open();
        }

        $family = $this->FontFamily;
        $style = $this->FontStyle . ($this->underline ? 'U' : '');
        $fontsize = $this->FontSizePt;
        $lw = $this->LineWidth;
        $dc = $this->DrawColor;
        $fc = $this->FillColor;
        $tc = $this->TextColor;
        $cf = $this->ColorFlag;

        if ($this->page > 0) {
            $this->_endpage();
        }

        $this->_beginpage($orientation, $size, $rotation);
        $this->_out('2 J');
        $this->LineWidth = $lw;
        $this->_out(sprintf('%.2F w', $lw * $this->k));

        if ($family) {
            $this->SetFont($family, $style, $fontsize);
        }
        $this->DrawColor = $dc;
        if ($dc !== '0 G') $this->_out($dc);
        $this->FillColor = $fc;
        if ($fc !== '0 g') $this->_out($fc);
        $this->TextColor = $tc;
        $this->ColorFlag = $cf;
    }

    public function Open(): void
    {
        $this->state = 1;
    }

    public function SetDrawColor(int $r, ?int $g = null, ?int $b = null): void
    {
        if (($r === 0 && $g === 0 && $b === 0) || $g === null) {
            $this->DrawColor = sprintf('%.3F G', $r / 255);
        } else {
            $this->DrawColor = sprintf('%.3F %.3F %.3F RG', $r / 255, $g / 255, $b / 255);
        }
        if ($this->page > 0) $this->_out($this->DrawColor);
    }

    public function SetFillColor(int $r, ?int $g = null, ?int $b = null): void
    {
        if (($r === 0 && $g === 0 && $b === 0) || $g === null) {
            $this->FillColor = sprintf('%.3F g', $r / 255);
        } else {
            $this->FillColor = sprintf('%.3F %.3F %.3F rg', $r / 255, $g / 255, $b / 255);
        }
        $this->ColorFlag = ($this->FillColor !== $this->TextColor);
        if ($this->page > 0) $this->_out($this->FillColor);
    }

    public function SetTextColor(int $r, ?int $g = null, ?int $b = null): void
    {
        if (($r === 0 && $g === 0 && $b === 0) || $g === null) {
            $this->TextColor = sprintf('%.3F g', $r / 255);
        } else {
            $this->TextColor = sprintf('%.3F %.3F %.3F rg', $r / 255, $g / 255, $b / 255);
        }
        $this->ColorFlag = ($this->FillColor !== $this->TextColor);
    }

    public function SetLineWidth(float $width): void
    {
        $this->LineWidth = $width;
        if ($this->page > 0) {
            $this->_out(sprintf('%.2F w', $width * $this->k));
        }
    }

    public function Line(float $x1, float $y1, float $x2, float $y2): void
    {
        $this->_out(sprintf('%.2F %.2F m %.2F %.2F l S', $x1 * $this->k, ($this->h - $y1) * $this->k, $x2 * $this->k, ($this->h - $y2) * $this->k));
    }

    public function Rect(float $x, float $y, float $w, float $h, string $style = ''): void
    {
        $op = match (strtolower($style)) {
            'f' => 'f',
            'b', 'fd', 'df' => 'B',
            default => 'S',
        };
        $this->_out(sprintf('%.2F %.2F %.2F %.2F re %s', $x * $this->k, ($this->h - $y) * $this->k, $w * $this->k, -$h * $this->k, $op));
    }

    public function SetFont(string $family, string $style = '', float $size = 0): void
    {
        $family = strtolower($family);
        if ($family === '') $family = $this->FontFamily;
        if ($family === 'arial') $family = 'helvetica';
        $style = strtoupper($style);
        if (str_contains($style, 'U')) {
            $this->underline = true;
            $style = str_replace('U', '', $style);
        } else {
            $this->underline = false;
        }
        if ($style === 'IB') $style = 'BI';
        if ($size == 0) $size = $this->FontSizePt;

        if ($this->FontFamily === $family && $this->FontStyle === $style && $this->FontSizePt === $size) {
            return;
        }

        $fontkey = $family . $style;
        if (!isset($this->fonts[$fontkey])) {
            if (isset($this->CoreFonts[$fontkey])) {
                $this->fonts[$fontkey] = ['i' => count($this->fonts) + 1, 'type' => 'core', 'name' => $this->CoreFonts[$fontkey], 'up' => -100, 'ut' => 50, 'cw' => []];
            } else {
                $fontkey = 'helvetica';
                if (!isset($this->fonts[$fontkey])) {
                    $this->fonts[$fontkey] = ['i' => count($this->fonts) + 1, 'type' => 'core', 'name' => 'Helvetica', 'up' => -100, 'ut' => 50, 'cw' => []];
                }
            }
        }

        $this->FontFamily = $family;
        $this->FontStyle = $style;
        $this->FontSizePt = $size;
        $this->FontSize = $size / $this->k;
        $this->CurrentFont = &$this->fonts[$fontkey];
        if ($this->page > 0) {
            $this->_out(sprintf('BT /F%d %.2F Tf ET', $this->CurrentFont['i'], $this->FontSizePt));
        }
    }

    public function SetFontSize(float $size): void
    {
        if ($this->FontSizePt === $size) return;
        $this->FontSizePt = $size;
        $this->FontSize = $size / $this->k;
        if ($this->page > 0) {
            $this->_out(sprintf('BT /F%d %.2F Tf ET', $this->CurrentFont['i'] ?? 1, $this->FontSizePt));
        }
    }

    public function SetXY(float $x, float $y): void
    {
        $this->SetY($y);
        $this->SetX($x);
    }

    public function SetX(float $x): void
    {
        $this->x = ($x >= 0) ? $x : $this->w + $x;
    }

    public function SetY(float $y): void
    {
        $this->x = $this->lMargin;
        $this->y = ($y >= 0) ? $y : $this->h + $y;
    }

    public function GetX(): float
    {
        return $this->x;
    }

    public function GetY(): float
    {
        return $this->y;
    }

    public function GetPageWidth(): float
    {
        return $this->w;
    }

    public function GetPageHeight(): float
    {
        return $this->h;
    }

    public function Cell(float $w, float $h = 0, string $txt = '', string|int $border = 0, int $ln = 0, string $align = '', bool $fill = false, string $link = ''): void
    {
        $k = $this->k;
        if ($this->y + $h > $this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AutoPageBreak) {
            $x = $this->x;
            $ws = $this->ws;
            if ($ws > 0) {
                $this->ws = 0;
                $this->_out('0 Tw');
            }
            $this->AddPage($this->CurOrientation);
            $this->x = $x;
            if ($ws > 0) {
                $this->ws = $ws;
                $this->_out(sprintf('%.3F Tw', $ws * $k));
            }
        }
        if ($w == 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }

        $s = '';
        if ($fill || $border == 1) {
            $op = ($fill && $border == 1) ? 'B' : ($fill ? 'f' : 'S');
            $s = sprintf('%.2F %.2F %.2F %.2F re %s ', $this->x * $k, ($this->h - $this->y) * $k, $w * $k, -$h * $k, $op);
        }

        if ($txt !== '') {
            $width = $this->GetStringWidth($txt);
            $dx = match (strtoupper($align)) {
                'R' => $w - $this->cMargin - $width,
                'C' => ($w - $width) / 2,
                default => $this->cMargin,
            };
            if ($this->ColorFlag) {
                $s .= 'q ' . $this->TextColor . ' ';
            }
            $txt2 = str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $txt);
            $s .= sprintf('BT %.2F %.2F Td (%s) Tj ET', ($this->x + $dx) * $k, ($this->h - ($this->y + 0.5 * $h + 0.3 * $this->FontSize)) * $k, $txt2);
            if ($this->underline) {
                $s .= ' ' . $this->_dounderline($this->x + $dx, $this->y + 0.5 * $h + 0.3 * $this->FontSize, $txt);
            }
            if ($this->ColorFlag) {
                $s .= ' Q';
            }
        }

        if ($s) {
            $this->_out($s);
        }
        $this->lasth = $h;
        if ($ln > 0) {
            $this->y += $h;
            if ($ln == 1) $this->x = $this->lMargin;
        } else {
            $this->x += $w;
        }
    }

    public function GetStringWidth(string $s): float
    {
        // Simple approximate character width calculation for standard font
        return strlen($s) * ($this->FontSizePt * 0.53 / $this->k);
    }

    public function Image(string $file, ?float $x = null, ?float $y = null, float $w = 0, float $h = 0, string $type = '', string $link = ''): void
    {
        if (!isset($this->images[$file])) {
            if (!file_exists($file)) {
                return;
            }
            $info = $this->_parseimage($file);
            $info['i'] = count($this->images) + 1;
            $this->images[$file] = $info;
        } else {
            $info = $this->images[$file];
        }

        if ($w == 0 && $h == 0) {
            $w = -96;
            $h = -96;
        }
        if ($w < 0) $w = -$info['w'] * 72 / $w / $this->k;
        if ($h < 0) $h = -$info['h'] * 72 / $h / $this->k;
        if ($w == 0) $w = $h * $info['w'] / $info['h'];
        if ($h == 0) $h = $w * $info['h'] / $info['w'];

        $x ??= $this->x;
        $y ??= $this->y;

        $this->_out(sprintf('q %.2F 0 0 %.2F %.2F %.2F cm /I%d Do Q', $w * $this->k, $h * $this->k, $x * $this->k, ($this->h - ($y + $h)) * $this->k, $info['i']));
    }

    public function Output(string $dest = '', string $name = '', bool $isUTF8 = false): string
    {
        if ($this->state < 3) {
            $this->Close();
        }

        $dest = strtoupper($dest);
        if ($dest === '') {
            $dest = $name === '' ? 'I' : 'F';
        }

        switch ($dest) {
            case 'I':
                $this->_checkoutput();
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="' . ($name ?: 'doc.pdf') . '"');
                header('Cache-Control: private, max-age=0, must-revalidate');
                header('Pragma: public');
                echo $this->buffer;
                return '';
            case 'D':
                $this->_checkoutput();
                header('Content-Type: application/x-download');
                header('Content-Disposition: attachment; filename="' . ($name ?: 'doc.pdf') . '"');
                header('Cache-Control: private, max-age=0, must-revalidate');
                header('Pragma: public');
                echo $this->buffer;
                return '';
            case 'F':
                file_put_contents($name, $this->buffer);
                return '';
            case 'S':
            default:
                return $this->buffer;
        }
    }

    public function Close(): void
    {
        if ($this->state === 3) return;
        if ($this->page === 0) $this->AddPage();
        $this->_endpage();
        $this->_enddoc();
    }

    protected function _getpagesize(string|array $size): array
    {
        if (is_string($size)) {
            $size = strtolower($size);
            if (!isset($this->StdPageSizes[$size])) {
                $size = 'a4';
            }
            $a = $this->StdPageSizes[$size];
            return [$a[0] / $this->k, $a[1] / $this->k];
        }
        if ($size[0] > $size[1]) {
            return [$size[1], $size[0]];
        }
        return $size;
    }

    protected function _beginpage(string $orientation, string|array $size, float $rotation): void
    {
        $this->page++;
        $this->pages[$this->page] = '';
        $this->state = 2;
        $this->x = $this->lMargin;
        $this->y = $this->tMargin;
        $this->FontFamily = '';

        if ($orientation === '') {
            $orientation = $this->DefOrientation;
        } else {
            $orientation = strtoupper($orientation[0]);
        }
        if ($orientation !== $this->CurOrientation) {
            $this->CurOrientation = $orientation;
            $this->w = ($orientation === 'P') ? $this->CurPageSize[0] : $this->CurPageSize[1];
            $this->h = ($orientation === 'P') ? $this->CurPageSize[1] : $this->CurPageSize[0];
            $this->wPt = $this->w * $this->k;
            $this->hPt = $this->h * $this->k;
            $this->PageBreakTrigger = $this->h - $this->bMargin;
        }
    }

    protected function _endpage(): void
    {
        $this->state = 1;
    }

    protected function _dounderline(float $x, float $y, string $txt): string
    {
        $up = $this->CurrentFont['up'] ?? -100;
        $ut = $this->CurrentFont['ut'] ?? 50;
        $w = $this->GetStringWidth($txt) + $this->ws * substr_count($txt, ' ');
        return sprintf('%.2F %.2F %.2F %.2F re f', $x * $this->k, ($this->h - ($y - $up / 1000 * $this->FontSize)) * $this->k, $w * $this->k, -$ut / 1000 * $this->FontSizePt);
    }

    protected function _parseimage(string $file): array
    {
        $size = getimagesize($file);
        if (!$size) {
            return ['w' => 100, 'h' => 100, 'type' => 'jpeg', 'data' => file_get_contents($file)];
        }
        $type = match ($size[2]) {
            IMAGETYPE_JPEG => 'jpeg',
            IMAGETYPE_PNG => 'png',
            default => 'jpeg',
        };
        return [
            'w' => $size[0],
            'h' => $size[1],
            'cs' => 'DeviceRGB',
            'bpc' => 8,
            'f' => 'DCTDecode',
            'data' => file_get_contents($file),
            'type' => $type
        ];
    }

    protected function _checkoutput(): void
    {
        if (PHP_SAPI !== 'cli') {
            if (headers_sent($file, $line)) {
                throw new \RuntimeException("Headers already sent in {$file} on line {$line}");
            }
        }
        if (ob_get_length()) {
            ob_end_clean();
        }
    }

    protected function _out(string $s): void
    {
        if ($this->state === 2) {
            $this->pages[$this->page] .= $s . "\n";
        } else {
            $this->buffer .= $s . "\n";
        }
    }

    protected function _newobj(?int $n = null): void
    {
        if ($n === null) $n = ++$this->n;
        $this->offsets[$n] = strlen($this->buffer);
        $this->_out($n . ' 0 obj');
    }

    protected function _putpages(): void
    {
        $nb = $this->page;
        for ($n = 1; $n <= $nb; $n++) {
            $this->_newobj();
            $this->_out('<</Type /Page');
            $this->_out('/Parent 1 0 R');
            $this->_out('/Resources 2 0 R');
            $this->_out('/Contents ' . ($this->n + 1) . ' 0 R>>');
            $this->_out('endobj');

            $p = $this->pages[$n];
            $this->_newobj();
            $this->_out('<<' . ($this->compress ? '/Filter /FlateDecode ' : '') . '/Length ' . strlen($this->compress ? gzcompress($p) : $p) . '>>');
            $this->_putstream($this->compress ? gzcompress($p) : $p);
            $this->_out('endobj');
        }
        $this->offsets[1] = strlen($this->buffer);
        $this->_out('1 0 obj');
        $this->_out('<</Type /Pages');
        $kids = '/Kids [';
        for ($i = 0; $i < $nb; $i++) {
            $kids .= (3 + 2 * $i) . ' 0 R ';
        }
        $this->_out($kids . ']');
        $this->_out('/Count ' . $nb);
        $this->_out(sprintf('/MediaBox [0 0 %.2F %.2F]', $this->DefPageSize[0] * $this->k, $this->DefPageSize[1] * $this->k));
        $this->_out('>>');
        $this->_out('endobj');
    }

    protected function _putstream(string $s): void
    {
        $this->_out('stream');
        $this->_out($s);
        $this->_out('endstream');
    }

    protected function _putresources(): void
    {
        // Fonts
        foreach ($this->fonts as $k => $font) {
            $this->_newobj();
            $this->fonts[$k]['n'] = $this->n;
            $name = $font['name'];
            $this->_out('<</Type /Font');
            $this->_out('/BaseFont /' . $name);
            $this->_out('/Subtype /Type1');
            $this->_out('/Encoding /WinAnsiEncoding');
            $this->_out('>>');
            $this->_out('endobj');
        }

        // Images
        foreach ($this->images as $file => $info) {
            $this->_newobj();
            $this->images[$file]['n'] = $this->n;
            $this->_out('<</Type /XObject');
            $this->_out('/Subtype /Image');
            $this->_out('/Width ' . $info['w']);
            $this->_out('/Height ' . $info['h']);
            $this->_out('/ColorSpace /DeviceRGB');
            $this->_out('/BitsPerComponent 8');
            $this->_out('/Filter /DCTDecode');
            $this->_out('/Length ' . strlen($info['data']) . '>>');
            $this->_putstream($info['data']);
            $this->_out('endobj');
        }

        // Resource dictionary
        $this->offsets[2] = strlen($this->buffer);
        $this->_out('2 0 obj');
        $this->_out('<<');
        $this->_out('/ProcSet [/PDF /Text /ImageB /ImageC /ImageI]');
        $this->_out('/Font <<');
        foreach ($this->fonts as $font) {
            $this->_out('/F' . $font['i'] . ' ' . $font['n'] . ' 0 R');
        }
        $this->_out('>>');
        if (!empty($this->images)) {
            $this->_out('/XObject <<');
            foreach ($this->images as $image) {
                $this->_out('/I' . $image['i'] . ' ' . $image['n'] . ' 0 R');
            }
            $this->_out('>>');
        }
        $this->_out('>>');
        $this->_out('endobj');
    }

    protected function _putinfo(): void
    {
        $this->_out('/Producer (HIMATIKA Connect CI4 PDF Generator)');
        if ($this->title) $this->_out('/Title (' . $this->title . ')');
        if ($this->subject) $this->_out('/Subject (' . $this->subject . ')');
        if ($this->author) $this->_out('/Author (' . $this->author . ')');
        $this->_out('/CreationDate (D:' . date('YmdHis') . ')');
    }

    protected function _putheader(): void
    {
        $this->_out('%PDF-' . $this->PDFVersion);
    }

    protected function _enddoc(): void
    {
        $this->state = 1;
        $this->_putheader();
        $this->_putpages();
        $this->_putresources();

        // Info
        $this->_newobj();
        $this->_out('<<');
        $this->_putinfo();
        $this->_out('>>');
        $this->_out('endobj');

        // Catalog
        $this->_newobj();
        $this->_out('<<');
        $this->_out('/Type /Catalog');
        $this->_out('/Pages 1 0 R');
        $this->_out('>>');
        $this->_out('endobj');

        // Cross-ref
        $o = strlen($this->buffer);
        $this->_out('xref');
        $this->_out('0 ' . ($this->n + 1));
        $this->_out('0000000000 65535 f ');
        for ($i = 1; $i <= $this->n; $i++) {
            $this->_out(sprintf('%010d 00000 n ', $this->offsets[$i]));
        }

        // Trailer
        $this->_out('trailer');
        $this->_out('<<');
        $this->_out('/Size ' . ($this->n + 1));
        $this->_out('/Root ' . $this->n . ' 0 R');
        $this->_out('/Info ' . ($this->n - 1) . ' 0 R');
        $this->_out('>>');
        $this->_out('startxref');
        $this->_out((string)$o);
        $this->_out('%%EOF');
        $this->state = 3;
    }
}
