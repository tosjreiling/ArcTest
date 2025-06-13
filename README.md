# ArcTest

**ArcTest** is a blazing-fast, lightweight, and dependency-free testing framework built in PHP. It is designed for use in the [Arc Suite](https://github.com/tosjreiling) but works standalone as well. ArcTest offers modern test features like attribute-based annotations, test lifecycle hooks, data providers, and customizable printers, including JSON, JUnit, and human-readable console output.

![PHP](https://img.shields.io/badge/php-8.2+-blue)
![License](https://img.shields.io/github/license/tosjreiling/ArcTest)
![Build](https://img.shields.io/github/actions/workflow/status/tosjreiling/ArcTest/test.yml)
![Version](https://img.shields.io/github/v/release/tosjreiling/ArcTest)

---

## 🚀 Features

- ✅ Minimal, zero-dependency core
- 🧪 Test discovery via reflection
- 🧷 Test attributes: `Depends`, `Group`, `Skip`, `ExpectedException`
- 🔁 Lifecycle hooks: `beforeAll`, `afterAll`, `beforeEach`, `afterEach`
- 📂 Group filtering and exclusions
- 🧵 Data providers
- 🖨️ Customizable output: Console, JSON, JUnit (XML)
- 🧩 Extensible with result printers and lifecycle listeners
- 🧠 Built for fast local runs and CI pipelines

---

## 📦 Installation

You can install ArcTest via Composer:

```bash
composer require --dev arc/test
```

Make sure to register autoloading correctly for your tests/ folder in composer.json.

## 🧪 Writing Tests
Create your test class by extending `ArcTest\Core\TestCase`:

```php
use ArcTest\Core\TestCase;
use ArcTest\Attributes\Depends;

class MathTest extends TestCase {
    public function testAdd(): void {
        $this->assertEquals(2, 1 + 1);
    }

    #[Depends("testAdd")]
    public function testSubtract(): void {
        $this->assertEquals(0, 2 - 2);
    }
}
```

### Lifecycle Hooks
```php
public static function beforeAll(): void { /* runs once before all */ }
public static function afterAll(): void { /* runs once after all */ }
public function beforeEach(): void { /* runs before each */ }
public function afterEach(): void { /* runs after each */ }
```

## ⚙️ Running Tests
Use the built-in CLI runner:

```bash
php bin/arctest
```

### Common Options

```bash
--filter=LoginTest          # Only run tests that match filter
--group=core,db             # Only include tests with these groups
--exclude=integration       # Skip these groups
--format=json               # Output format: console, json, junit
--output=output/result.xml  # Save output to file
--fail-fast                 # Stop on first failure
--verbose                   # Show detailed information
```

## 📄 Example
```bash
php bin/arctest --filter=UserTest --group=auth --format=junit --output=reports/junit.xml
```

## ✨ Attributes
| Attribute              | Description                                    |
| ---------------------- | ---------------------------------------------- |
| `#[Depends("method")]` | Specifies method dependencies                  |
| `#[Group("group")]`    | Marks test with a specific group label         |
| `#[Skip("reason")]`    | Skips a test with optional reason              |
| `#[ExpectedException]` | Marks a test to pass if an exception is thrown |

## 📊 Output Formats
- console: Human-readable
- json: Machine-readable for CI pipelines
- html: Webbrowser
- junit: JUnit-compatible XML (e.g., for GitHub Actions)
