# 3. Actualización de PHP

## Fase 1: PHP 7.1 → 7.4

### 1. Instalar PHP 7.4

#### Ubuntu/Debian

```bash
sudo apt update
sudo apt install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install php7.4 php7.4-fpm php7.4-mysql php7.4-xml \
    php7.4-mbstring php7.4-curl php7.4-zip php7.4-gd \
    php7.4-sqlite3 php7.4-bcmath php7.4-json
```

#### Verificar Instalación

```bash
php7.4 -v
```

### 2. Cambios Requeridos en Código

#### Reemplazar `each()`

```php
// Antes
while (list($key, $value) = each($array)) {
    // código
}

// Después
foreach ($array as $key => $value) {
    // código
}
```

#### Reemplazar `create_function()`

```php
// Antes
$func = create_function('$a,$b', 'return $a + $b;');

// Después
$func = function($a, $b) {
    return $a + $b;
};
```

#### Cambiar Acceso a Arrays

```php
// Antes
$char = $string{0};

// Después
$char = $string[0];
```

### 3. Actualizar composer.json

```json
{
    "require": {
        "php": "^7.4"
    }
}
```

### 4. Testing

```bash
composer install
php artisan config:clear
php artisan cache:clear
php artisan test
```

## Fase 2: PHP 7.4 → 8.1

### 1. Instalar PHP 8.1

```bash
sudo apt install php8.1 php8.1-fpm php8.1-mysql php8.1-xml \
    php8.1-mbstring php8.1-curl php8.1-zip php8.1-gd \
    php8.1-sqlite3 php8.1-bcmath
```

### 2. Cambios Requeridos

#### Manejo de Null

```php
// Antes
if ($patient->birthdate) {
    $age = calcule_age($patient->birthdate);
}

// Después (usar nullsafe operator)
$age = $patient->birthdate?->format('Y-m-d');
```

#### Named Arguments (Opcional)

```php
// Nuevo en PHP 8.0
Patient::create(
    name: 'Juan',
    lastname: 'Pérez',
    sex: 'M'
);
```

#### Match Expression (Opcional)

```php
// Antes
switch ($status) {
    case 'active':
        $message = 'Activo';
        break;
    case 'inactive':
        $message = 'Inactivo';
        break;
    default:
        $message = 'Desconocido';
}

// Después
$message = match($status) {
    'active' => 'Activo',
    'inactive' => 'Inactivo',
    default => 'Desconocido'
};
```

### 3. Actualizar composer.json

```json
{
    "require": {
        "php": "^8.1"
    }
}
```

### 4. Testing

```bash
composer install
php artisan test
```

## Fase 3: PHP 8.1 → 8.3

### 1. Instalar PHP 8.3

```bash
sudo apt install php8.3 php8.3-fpm php8.3-mysql php8.3-xml \
    php8.3-mbstring php8.3-curl php8.3-zip php8.3-gd \
    php8.3-sqlite3 php8.3-bcmath
```

### 2. Cambios Opcionales

#### Readonly Classes

```php
readonly class PatientDTO
{
    public function __construct(
        public string $name,
        public string $lastname,
        public string $sex,
    ) {}
}
```

#### Typed Constants

```php
class Patient extends Model
{
    public const string TABLE = 'patients';
    public const int MAX_NAME_LENGTH = 255;
}
```

### 3. Actualizar composer.json

```json
{
    "require": {
        "php": "^8.3"
    }
}
```

### 4. Testing Final

```bash
composer install
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan test
```

## Configuración de Servidor

### Nginx con PHP 8.3

```nginx
location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
    fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    include fastcgi_params;
}
```

### Apache con PHP 8.3

```apache
<FilesMatch \.php$>
    SetHandler "proxy:unix:/var/run/php/php8.3-fpm.sock|fcgi://localhost"
</FilesMatch>
```

## Optimización PHP 8.3

### php.ini

```ini
; OPcache
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0
opcache.jit=tracing
opcache.jit_buffer_size=100M

; Performance
memory_limit=512M
max_execution_time=300
upload_max_filesize=20M
post_max_size=20M
```

## Verificación de Compatibilidad

### Script de Verificación

```php
<?php
// verify-php.php

echo "PHP Version: " . PHP_VERSION . "\n";
echo "Extensions:\n";

$required = [
    'openssl', 'pdo', 'mbstring', 'tokenizer',
    'xml', 'ctype', 'json', 'bcmath', 'fileinfo'
];

foreach ($required as $ext) {
    echo "  - $ext: " . (extension_loaded($ext) ? '✓' : '✗') . "\n";
}

echo "\nOPcache: " . (extension_loaded('Zend OPcache') ? '✓' : '✗') . "\n";
echo "JIT: " . (ini_get('opcache.jit') ? '✓' : '✗') . "\n";
```

Ejecutar:

```bash
php verify-php.php
```

## Troubleshooting

### Error: Extension no encontrada

```bash
# Instalar extensión faltante
sudo apt install php8.3-[extension]
sudo systemctl restart php8.3-fpm
```

### Error: Composer requiere versión anterior

```bash
# Actualizar Composer
composer self-update
composer update
```

### Error: Sintaxis no compatible

```bash
# Usar Rector para actualizar código
vendor/bin/rector process app/
```

## Checklist de Actualización PHP

- [ ] PHP 7.4 instalado
- [ ] Código compatible con PHP 7.4
- [ ] Tests pasando en PHP 7.4
- [ ] PHP 8.1 instalado
- [ ] Código compatible con PHP 8.1
- [ ] Tests pasando en PHP 8.1
- [ ] PHP 8.3 instalado
- [ ] Código compatible con PHP 8.3
- [ ] Tests pasando en PHP 8.3
- [ ] Servidor web configurado
- [ ] OPcache y JIT habilitados
- [ ] Performance verificada

## Siguiente Paso

Proceder con:
- [Actualización de Laravel](04-actualizacion-laravel.md)
