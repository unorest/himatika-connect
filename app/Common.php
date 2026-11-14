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

// Polyfill for Locale class when ext-intl is missing in minimal environments (e.g. GitHub Codespaces)
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

