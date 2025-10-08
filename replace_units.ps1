$content = Get-Content 'C:\laragon\www\giro\database\seeders\IngredientSeeder.php' -Raw

$content = $content -replace "'default_unit' => 'ml'", "'default_unit' => 'unidade'"
$content = $content -replace "'default_unit' => 'g'", "'default_unit' => 'unidade'"
$content = $content -replace "'default_unit' => 'dente'", "'default_unit' => 'unidade'"
$content = $content -replace "'default_unit' => 'folha'", "'default_unit' => 'unidade'"
$content = $content -replace "'default_unit' => 'fatia'", "'default_unit' => 'unidade'"
$content = $content -replace "'default_unit' => 'colher de chá'", "'default_unit' => 'unidade'"
$content = $content -replace "'default_unit' => 'envelope'", "'default_unit' => 'unidade'"

Set-Content 'C:\laragon\www\giro\database\seeders\IngredientSeeder.php' -Value $content

Write-Host "Substituição concluída!"