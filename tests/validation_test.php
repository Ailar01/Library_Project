<?php
// tests/validation_test.php
require __DIR__ . '/../lib/validation.php';

function contains_msg(array $errors, string $needle): bool {
    foreach ($errors as $e) {
        if (strpos($e, $needle) !== false) return true;
    }
    return false;
}

$cases = [
    [
        'name' => 'Empty title should error',
        'data' => ['title' => '', 'author' => 'A', 'genre' => 'G', 'published_year' => (string)date('Y')],
        'expect' => function($errs) { return in_array('Title is required.', $errs, true); }
    ],
    [
        'name' => 'Non-numeric year should error',
        'data' => ['title' => 'T', 'author' => 'A', 'genre' => 'G', 'published_year' => 'abcd'],
        'expect' => function($errs) { return contains_msg($errs, 'must be a number'); }
    ],
    [
        'name' => 'Future year should error',
        'data' => ['title' => 'T', 'author' => 'A', 'genre' => 'G', 'published_year' => (string)(date('Y') + 1)],
        'expect' => function($errs) { return contains_msg($errs, 'between 1000'); }
    ],
    [
        'name' => 'Valid data should have no errors',
        'data' => ['title' => 'T', 'author' => 'A', 'genre' => 'G', 'published_year' => (string)date('Y')],
        'expect' => function($errs) { return count($errs) === 0; }
    ],
];

$passed = 0; $failed = 0;

foreach ($cases as $case) {
    $errors = validate_book_input($case['data']);
    $ok = $case['expect']($errors);

    if ($ok) {
        $passed++;
        echo "OK  - " . $case['name'] . PHP_EOL;
    } else {
        $failed++;
        echo "FAIL - " . $case['name'] . " | got: " . implode('; ', $errors) . PHP_EOL;
    }
}

echo "Passed: $passed, Failed: $failed" . PHP_EOL;
exit($failed > 0 ? 1 : 0);
