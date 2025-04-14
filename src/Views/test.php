<?php
namespace Backoffice\Views;

$text = <<<EOT
# Owner
OWNER="Xexu Lara"

# Data Base
DB_HOST=Backoffice\.local
DB_NAME=xexu
DB_PORT=3307
DB_USER=sql2025
DB_PSWD="%Vilnius25#45~54"

# JWT
JWT_DEFAULT_EXPIRATION=900 # 15 min (900 s)

# Secret
PRIVKEY_PASSPHRASE=CrlPn!250301^49:3$
PRIVKEY_GLOSS="Amigo \"mio\""

# Obfuscate Base64 enconding
OBFUSCATED_BASE64_ALPHABET=KuFvrIOWzfLcmnAj2tlJQ5kV49UgZa7iebDYT0RhBXpMxoqwP3HdGSCN1E6s8y~_-
EOT;

function parseLine(string $line): array
{
    $pattern = '/^(?<key>[^=\s]+)\s*=\s*(?:(?<quote>["\'])(?<quoted>(?:\\\\.|(?!\k<quote>).)*)\k<quote>|(?<unquoted>[^#\s]+(?:\s+[^#\s]+)*))?(?:\s*#.*)?$/';
    if (preg_match($pattern, $line, $matches)) {
        $key = $matches['key'];
        
        if ($matches['quoted']) {
            $value = $matches['quoted'];
            $value = preg_replace('/\\\\(.)/', '$1', $value); // Déséchapper tous les caractères échappés
        } else {
            $value = trim($matches['unquoted'] ?? '');
        }

        return [$key, $value];
    }
    return ['', ''];
}

// Expresión regular corregida
$result = [];
$lines = explode("\r\n", $text, substr_count($text, "\n")+1);
foreach($lines as $line) {
    [$key, $val] = parseLine($line);
    $result[strtolower($key)] = $val;
}

echo "<pre>";
foreach($result as $key => $val) {
    echo "$key: $val\n";
}
echo "</pre>";