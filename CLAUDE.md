# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

---

## Project Overview

**Laravel Spanish Validator** is a Laravel package that provides custom validation rules for Spanish documents and numbers:
- **ID Numbers**: NIF (Número de Identificación Fiscal), NIE (Número de Identificación para Extranjeros), CIF (Código de Identificación Fiscal)
- **Social Security**: NSS (Número de la Seguridad Social)
- **Banking**: IBAN validation
- **Postal**: Spanish postal codes
- **Telecom**: Spanish phone numbers

**Package Details:**
- Namespace: `Danielmrdev\SpanishValidator` (v2.0.0+)
- Previous namespace: `Orumad\SpanishValidator` (v1.x)
- PHP: ^8.2
- Laravel: ^11.0 | ^12.0
- Package Manager: Composer
- Current version: 2.0.0 (major rewrite with breaking changes)

---

## Important Notes

### v2.0.0 Breaking Changes (2026-03-21)

**This is a major version with breaking changes.** See README.md for migration guide.

- ✅ Namespace changed: `Orumad\SpanishValidator` → `Danielmrdev\SpanishValidator`
- ✅ Package name: `orumad/laravel-spanish-validator` → `danielmrdev/laravel-spanish-validator`
- ✅ All validation logic unchanged — only namespace/package refactor
- ✅ Migrated to GitHub Actions for CI/CD

Users on v1.x should update:
1. `composer require danielmrdev/laravel-spanish-validator:^2.0`
2. Update imports: `Orumad\SpanishValidator` → `Danielmrdev\SpanishValidator`
3. See README.md "Migration from v1" section for details

---

## Development Commands

```bash
# Run all tests
composer test

# Run tests with coverage report (HTML in ./coverage, text in build/coverage.txt)
composer test-coverage

# Run a specific test file
vendor/bin/phpunit tests/SpanishValidatorTest.php

# Run a specific test method
vendor/bin/phpunit --filter is_a_valid_nif tests/SpanishValidatorTest.php

# Install dependencies
composer install

# Update dependencies
composer update
```

---

## Continuous Integration & Automation

### GitHub Actions Workflows

**Tests** (`.github/workflows/tests.yml`)
- Runs automatically on push/PR to main
- Tests across PHP 8.2, 8.3, 8.4 and Laravel 11, 12
- Tests both `--prefer-lowest` and default composer flags
- Uploads coverage to Codecov

**Quality** (`.github/workflows/quality.yml`)
- Runs automatically on push/PR to main
- PHPUnit with coverage reports
- PSR-2 code style checks
- PHPStan static analysis (level 5)

**Release** (`.github/workflows/release.yml`)
- Triggers automatically on git tag push (pattern: `v*`)
- Automatically:
  1. Extracts version from tag (e.g., `v1.4.6` → `1.4.6`)
  2. Updates `composer.json` version
  3. Updates `CHANGELOG.md` with new entry template
  4. Commits changes to main
  5. Creates GitHub Release page
- Composer automatically detects the new tag and updates Packagist within 15 minutes

**How to release:**
```bash
# 1. Bump version in composer.json manually (or prepare via commit)
# 2. Create and push the tag
git tag v1.4.6
git push origin v1.4.6
# 3. Workflow runs automatically
# 4. Check GitHub Releases page for the release
```

Composer will detect the tag and publish the new version automatically.

---

## Code Architecture

### Layer 1: Service Provider (`src/SpanishValidatorServiceProvider.php`)

**Purpose**: Bootstrap the package within Laravel.

- **Registers custom validators** with Laravel's `Validator` facade using `Validator::extend()`
- **Sets up error messages** via `Validator::replacer()` that use translation keys from `spanishValidator::spanishValidator.*`
- **Handles Laravel version differences** for language file paths (Laravel 9+ vs earlier)
- Handles 9 total validators: `nif`, `nie`, `cif`, `spanish_tax_number`, `spanish_personal_id`, `nss`, `iban`, `spanish_postal_code`, `spanish_phone`

### Layer 2: Internal Validator (`src/InternalValidator.php`)

**Purpose**: Adapter between Laravel's validation callback system and the core validator logic.

- Instantiates `Validator` class in constructor
- Each method signature: `public function validate{Rule}($attribute, $value, $parameters, $validator): bool`
- Delegates to corresponding method on `Validator` instance (e.g., `validateNif()` → `$this->validator->isValidNif()`)
- Called via string reference in ServiceProvider: `'Orumad\\SpanishValidator\\InternalValidator@validateNif'`

### Layer 3: Core Validator (`src/Validator.php`)

**Purpose**: Pure validation logic with no Laravel dependencies.

- Contains all regex patterns and validation algorithms
- All methods are public and have signature: `public function isValid{DocumentType}(?string $value): bool`
- Methods check for null/empty first, then apply regex, then (if needed) calculate/verify checksums
- Examples:
  - **NIF/NIE**: Use modulo 23 with letter lookup table
  - **CIF**: Multi-step validation with Luhn-like checksum
  - **NSS**: Modulo 97 validation
  - **IBAN**: Uses external `globalcitizen/php-iban` library
  - **Postal Code**: Regex validates range 01000-52999 (valid Spanish postal codes)
  - **Phone**: Regex validates format (starts with 6/7/8/9, followed by 8 digits)

### Layer 4: Facade (`src/Facades/SpanishValidator.php`)

**Purpose**: Syntactic sugar for accessing the validator.

- Extends Laravel's `Facade` base class
- Returns `'Validator'` as the IoC binding (the Laravel validator instance)
- Allows usage like `\Orumad\SpanishValidator\Facades\SpanishValidator::validate()`

### Translation Files (`resources/lang/`)

- **`es/spanishValidator.php`**: Spanish error messages for each validation rule
- **`en/spanishValidator.php`**: English error messages
- During development, use config command to publish these: `php artisan vendor:publish --provider="Orumad\SpanishValidator\SpanishValidatorServiceProvider"`
- Keys are used in `ServiceProvider::replacer()` callbacks

---

## Testing Strategy

- **Framework**: PHPUnit 11.x (via Orchestra TestBench for Laravel testing)
- **Test File**: `tests/SpanishValidatorTest.php`
- **Test Style**: Direct instantiation of `Validator` class, no Laravel integration testing
- **Pattern**: Each validator method has two test methods—valid cases and invalid cases
- **Coverage**: Includes edge cases (nulls, invalid formats, wrong checksums)
- **Config**: `phpunit.xml.dist` configures coverage reports in `build/` directory

---

## Code Patterns & Conventions

- **Null Handling**: All validator methods explicitly check `if ($value)` before validation to handle null/empty values gracefully
- **Case Insensitivity**: All methods use `strtoupper()` on input to normalize case
- **Regex Modifiers**: Use `(?:...)` for non-capturing groups where needed, `/i` flag for case-insensitive matching
- **Return Type**: Always return `bool` (true if valid, false otherwise)
- **Validation Composition**: `isValidTaxNumber()` and `isValidPersonalId()` compose calls to other validators using logical `or`

---

## Common Development Tasks

### Adding a New Validator Rule

1. Add validation logic to `Validator` class: `public function isValid{RuleName}(?string $value): bool`
2. Add corresponding test cases in `SpanishValidatorTest`
3. Register the rule in `SpanishValidatorServiceProvider::addValidators()`:
   - Call `Validator::extend('rule_name', 'Orumad\\SpanishValidator\\InternalValidator@validate{RuleName}')`
   - Call `Validator::replacer('rule_name', function(...) { return __('spanishValidator::spanishValidator.rule_name'); })`
4. Create `InternalValidator` method if needed: `public function validate{RuleName}($attribute, $value, $parameters, $validator)`
5. Add translation keys to `resources/lang/es/spanishValidator.php` and `resources/lang/en/spanishValidator.php`

### Running a Single Test

```bash
vendor/bin/phpunit --filter is_a_valid_nif
```

### Debugging a Validator

1. Instantiate `Validator` directly (no Laravel needed for core logic)
2. Call the method directly: `(new Validator())->isValidNif('12345678Z')`
3. Trace regex patterns in the method body

### Checking Coverage

```bash
composer test-coverage
# Then open coverage/index.html or read build/coverage.txt
```

---

## Dependencies

- **illuminate/support** (^11.0 | ^12.0): Laravel facade/service provider base classes
- **globalcitizen/php-iban** (^4.2.3): External library for IBAN validation
- **orchestra/testbench** (^9.0 | ^10.0): Laravel testing utilities
- **phpunit/phpunit** (^11.0): Testing framework

---

## Key Files & Locations

| File | Purpose |
|------|---------|
| `src/SpanishValidatorServiceProvider.php` | Package bootstrap & validator registration |
| `src/Validator.php` | Core validation logic |
| `src/InternalValidator.php` | Laravel callback adapter |
| `src/Facades/SpanishValidator.php` | Facade for accessing validator |
| `tests/SpanishValidatorTest.php` | Unit tests for all validators |
| `resources/lang/{es,en}/spanishValidator.php` | Translation files |
| `composer.json` | Dependencies and package metadata |
| `phpunit.xml.dist` | Test configuration |

---

## Code Style

- **Standard**: PSR-2 / Laravel
- **Indentation**: 4 spaces
- **Line Ending**: LF
- **Charset**: UTF-8
- **Tools**: GitHub Actions workflows validate on push/PR
  - PSR-2 checks via phpcs
  - Static analysis via phpstan
  - Coverage reports via Codecov
