<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

// Polyfill for Locale & NumberFormatter when ext-intl is missing in minimal environments
if (! class_exists('Locale', false)) {
    class Locale
    {
        protected static string $default = 'id_ID';

        public static function getDefault(): string
        {
            return static::$default;
        }

        public static function setDefault(string $locale): bool
        {
            static::$default = $locale;
            return true;
        }

        public static function __callStatic(string $name, array $arguments)
        {
            return null;
        }
    }
}

if (! class_exists('NumberFormatter', false)) {
    class NumberFormatter
    {
        public const DECIMAL = 1;
        public const CURRENCY = 2;
        public const PERCENT = 3;
        public const SCIENTIFIC = 4;
        public const SPELLOUT = 5;
        public const ORDINAL = 6;
        public const DURATION = 7;
        public const PATTERN_RULEBASED = 9;
        public const DEFAULT_STYLE = 1;

        public function __construct(string $locale, int $style, ?string $pattern = null) {}

        public function format($value, int $type = 0)
        {
            return is_numeric($value) ? number_format((float) $value, 2, ',', '.') : (string) $value;
        }

        public function setAttribute(int $attribute, $value): bool
        {
            return true;
        }
    }
}

