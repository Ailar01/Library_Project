<?php
/**
 * Validate book input data.
 * Returns an array of error messages (empty if valid).
 *
 * @param array $data
 * @return string[]
 */
function validate_book_input(array $data): array {
    $errors = [];

    $title          = trim($data['title'] ?? '');
    $author         = trim($data['author'] ?? '');
    $genre          = trim($data['genre'] ?? '');
    $published_year = trim($data['published_year'] ?? '');

    if ($title === '')  { $errors[] = 'Title is required.'; }
    if ($author === '') { $errors[] = 'Author is required.'; }
    if ($genre === '')  { $errors[] = 'Genre is required.'; }

    $currentYear = (int)date('Y');
    if ($published_year === '' || !ctype_digit($published_year)) {
        $errors[] = 'Published year must be a number.';
    } else {
        $py = (int)$published_year;
        if ($py < 1000 || $py > $currentYear) {
            $errors[] = 'Published year must be between 1000 and ' . $currentYear . '.';
        }
    }

    return $errors;
}
