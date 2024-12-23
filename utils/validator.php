<?

function validate(array $values, array $regexps, array $errors)
{
    for ($i = 0; $i < count($values); $i++) {
        if (!preg_match('/' . $regexps[$i] . '/u', $values[$i])) {
            return $errors[$i];
        }
    }
    return null;
}
